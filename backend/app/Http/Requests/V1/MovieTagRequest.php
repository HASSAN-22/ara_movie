<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MovieTagRequest extends FormRequest
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
            'titles'=>['required','array'],
            'titles.*'=>['required','string','max:255'],
            'links'=>['required','array'],
            'links.*'=>['required','string','max:255','url'],
        ];
    }
}
