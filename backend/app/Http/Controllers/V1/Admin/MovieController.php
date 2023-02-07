<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MovieRequest;
use App\Http\Resources\V1\MovieResourceCollection;
use App\Models\Movie;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use function App\Auxiliary\micro_time;
use Intervention\Image\ImageManagerStatic as Image;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Movie::class);
        $movies = Movie::latest()->with(['category'=>fn($q)=>$q->select('id','title')])->paginate(10);
        return new MovieResourceCollection($movies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovieRequest $request)
    {
        $this->authorize('create',Movie::class);
        $movie = Movie::create([
            'category_id'=>$request->category_id,
            'title'=>$request->title,
            'fa_title'=>$request->fa_title,
            'slug'=>Str::slug($request->title),
            'type'=>$request->type,
            'image'=>$this->downloadImage($request->image),
            'quality'=>$request->quality,
            'genre'=>$request->genre,
            'product'=>$request->product,
            'lang'=>$request->lang,
            'published_at'=>$request->published_at,
            'time'=>$request->time,
            'director'=>$request->director,
            'actor'=>$request->actor,
            'imdb'=>$request->imdb,
            'imdb_number'=>!is_null($request->imdb) ? explode(' ',$request->imdb)[0] : null,
            'critics'=>$request->critics,
            'rank'=>$request->rank,
            'pg'=>$request->pg,
            'play_status'=>$request->play_status,
            'broadcast_day'=>$request->broadcast_day,
            'network'=>$request->network,
            'subtitle'=>$request->subtitle,
            'dubbed'=>$request->dubbed,
            'status'=>$request->status,
            'status_comment'=>$request->status_comment,
            'soon'=>$request->soon,
            'awards'=>$request->awards,
            'imdbId'=>$request->imdbId,
            'description'=>$request->description,
            'moreDescription'=>$request->moreDescription,
            'keyword'=>$request->keyword,
            'short_link'=>micro_time()
        ]);
        return $movie ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        $this->authorize('view',$movie);
        $movie = $movie->where('id',$movie->id)->with(['category'=>fn($q)=>$q->select('id','title')])->first();
        return response(['status'=>'success','data'=>$movie]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(MovieRequest $request, Movie $movie)
    {
        $this->authorize('update',$movie);

        $movie->category_id = $request->category_id;
        $movie->title = $request->title;
        $movie->fa_title = $request->fa_title;
        $movie->slug = Str::slug($request->title);
        $movie->type = $request->type;
        $movie->image = $this->downloadImage($request->image);
        $movie->quality = $request->quality;
        $movie->genre = $request->genre;
        $movie->product = $request->product;
        $movie->lang = $request->lang;
        $movie->published_at = $request->published_at;
        $movie->time = $request->time;
        $movie->director = $request->director;
        $movie->actor = $request->actor;
        $movie->imdb = $request->imdb;
        $movie->critics = $request->critics;
        $movie->rank = $request->rank;
        $movie->pg = $request->pg;
        $movie->play_status = $request->play_status;
        $movie->broadcast_day = $request->broadcast_day;
        $movie->network = $request->network;
        $movie->subtitle = $request->subtitle;
        $movie->dubbed = $request->dubbed;
        $movie->status = $request->status;
        $movie->status_comment = $request->status_comment;
        $movie->soon = $request->soon;
        $movie->awards = $request->awards;
        $movie->imdbId = $request->imdbId;
        $movie->description = $request->description;
        $movie->moreDescription = $request->moreDescription;
        $movie->keyword = $request->keyword;
        return $movie->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $this->authorize('delete',$movie);
        return $movie->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    public function getInfo(Request $request){
        $title = $request->title;
        $apiKey = config('app.omdb_api');
        $client = new Client();
        try {
            $request = $client->get("http://www.omdbapi.com/?t=${title}&apikey=${apiKey}");
            $response = (array)json_decode($request->getBody()->getContents());
            return $this->checkResponse($response);

        }catch (\Exception $e){
            return strstr($e->getMessage(),'401 Unauthorized')
                ? response(['status'=>'error','errors'=>['file'=>['خطا: API معتبر نیست']]],422)
                : response(['status'=>'error'],500);
        }

    }

    private function translateMovieInfo($results){
        foreach($results as $key=>$result){
            if(!in_array($key,['Ratings','Type','Poster','Rated','imdbID'])){
                $results[$key] = $this->translateText($result);
            }
        }
        return $results;
    }

    private function checkResponse($response){
        if($response['Response'] == 'True'){
            $response = $this->translateMovieInfo($response);
            return response(['status'=>'success','data'=>$response]);
        }else{
            return response(['status'=>'error','errors'=>['file'=>['خطا: فیلم یا سریال پیدا نشد']]],422);
        }
    }
    private function downloadImage($imageUrl){
        if(strstr($imageUrl,'https://m.media-amazon.com/')){
            $img = last(explode('/',$imageUrl));
            $path = '/uploader/gallery/'.$img;
            $fullPath = public_path($path);
            file_put_contents($fullPath, file_get_contents($imageUrl));
            $image_resize = Image::make($path);
            $image_resize->resize(340, 90);
            $image_resize->save(public_path('/uploader/gallery/resize/' .$img));
            return $path;
        }
        return $imageUrl;
    }

    private function translateText($text){
        $tr = new GoogleTranslate();
        $tr->setSource('en');
        $tr->setSource();
        $tr->setTarget('fa');
        return $tr->translate($text);
    }
}
