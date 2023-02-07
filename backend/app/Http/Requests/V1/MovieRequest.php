<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'=>['required','numeric'],
            'title'=>['required','string','max:255','unique:movies,id,title'],
            'fa_title'=>['required','string','max:255','unique:movies,id,fa_title'],
            'type'=>['required','string','in:serial,movie'],
            'image'=>['required','string','regex:/(jpg|jpeg|png)$/im'],
            'quality'=>['nullable','string','max:255'],
            'genre'=>['nullable','string','max:255'],
            'product'=>['nullable','string','max:255'],
            'lang'=>['nullable','string','max:255'],
            'published_at'=>['nullable','string','max:255'],
            'time'=>['nullable','string','max:255'],
            'director'=>['nullable','string','max:255'],
            'actor'=>['nullable','string'],
            'imdb'=>['nullable','string','max:255'],
            'critics'=>['nullable','string','max:255'],
            'rank'=>['nullable','string','max:255'],
            'pg'=>['nullable','string','max:255'],
            'play_status'=>['nullable','string','in:yes,no'],
            'broadcast_day'=>['nullable','string','max:255'],
            'network'=>['nullable','string','max:255'],
            'subtitle'=>['required','string','in:yes,no'],
            'dubbed'=>['required','string','in:yes,no'],
            'status'=>['required','string','in:yes,no'],
            'status_comment'=>['required','string','in:yes,no'],
            'soon'=>['required','string','in:yes,no'],
            'awards'=>['required','string','max:255'],
            'imdbId'=>['required','string','max:255'],
            'description'=>['required','string','max:1000'],
            'moreDescription'=>['nullable','string','max:3000'],
            'keyword'=>['nullable','string','max:3000'],
        ];
    }
}
