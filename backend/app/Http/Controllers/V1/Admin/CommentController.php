<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CommentResourceCollection;
use App\Models\Comment;
use App\Models\CommentAnswer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function App\Auxiliary\avatar;
use function App\Auxiliary\deleteNotification;
use function App\Auxiliary\sendNotification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Comment::class);
        $comments = Comment::latest()->with('commentAnswers')->paginate();
        return new CommentResourceCollection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',Comment::class);
        $request->validate(['comment'=>['required','string','max:1000']]);
        $answer = CommentAnswer::create([
            'parent_id'=>$request->answer_id,
            'name'=>'ادمین سایت',
            'email'=>'N/A',
            'comment_id'=>$request->comment_id,
            'answer'=>$request->comment,
            'status'=>'yes',
        ]);
        return $answer ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($comment)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param    $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('create',Comment::class);
        $data = $this->getComment($request->action ,$id);
        $data->status = $request->status;
        if($data->save()){
            if($request->action != 'comment'){
                deleteNotification('App\Notifications\CommentAnswerNotify',$data->id,'commentAnswer');
                $this->deleteAndUpdateAChildes($data,false,$request->status);
            }else{
                deleteNotification('App\Notifications\CommentNotify',$data->id,'comment');
                $data->commentAnswers()->update(['status'=>$request->status]);
            }
            return response(['status'=>'success']);
        }
        return response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $comment)
    {
        $this->authorize('create',Comment::class);
        $data = $this->getComment($request->action ,$comment);
        if($request->action != 'comment'){
            $this->deleteAndUpdateAChildes($data);
            if($data->delete()){
                return response(['status'=>'success']);
            }
            return response(['status'=>'error'],500);

        }
        deleteNotification('App\Notifications\CommentNotify',$data->id,'comment');
        $data->commentAnswers->map(function($item){
            deleteNotification('App\Notifications\CommentAnswerNotify',$item->id,'commentAnswer');
        });
        return $data->delete() ? response(['status'=>'success']) : response(['status'=>'error'],500);

    }

    public function commentShow($id, $action){
        $this->authorize('viewAny',Comment::class);
        $data = $this->getComment($action,$id);
        $data['action'] = $action;
        return response(['status'=>'success','data'=>$data]);
    }

    private function getComment($action,$id){
        return $action == 'comment' ? Comment::find($id) : CommentAnswer::find($id);
    }

    private function deleteAndUpdateAChildes($answer, $isDelete=true, $status='yes'){
        $childes = $answer->children()->get();
        if(count($childes) > 0){
            foreach($childes as $child){
                if($isDelete){
                    $child->delete();
                    deleteNotification('App\Notifications\CommentAnswerNotify',$child->id,'commentAnswer');
                }else{
                    $child->status = $status;
                    $child->save();
                }
                $this->deleteAndUpdateAChildes($child,$isDelete,$status);
            }
        }
    }
}
