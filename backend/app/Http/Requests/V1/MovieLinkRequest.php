<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MovieLinkRequest extends FormRequest
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
            'movie_id'=>['required','numeric'],
            'caption_movie'=>['nullable','string','max:255'],
            'title'=>['required','string','max:255'],
            'screenShot'=>['nullable','string','regex:/(jpg|jpeg|png)$/im'],
            'trailers'=>['array','nullable'],
            'trailers.*'=>['nullable','string','regex:/(mp4|mkv|avi)$/im'],
            'trailerCaptions'=>['array','nullable'],
            'trailerCaptions.*'=>['nullable','string','max:255'],
            'titles'=>['required','array'],
            'titles.*'=>['required','string','max:255'],
            'links'=>['required','array'],
            'links.*'=>['required','url','string','max:255'],
            'captions'=>['nullable','array'],
            'captions.*'=>['nullable','string','max:255'],
            'subtitles'=>['nullable','array'],
            'subtitles.*'=>['nullable','string','regex:/(srt)$/im'],
            'vip'=>['required','string','in:yes,no'],
        ];
    }
}
