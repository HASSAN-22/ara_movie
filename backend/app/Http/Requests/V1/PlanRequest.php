<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'title'=>['required','string','max:255'],
            'price'=>['required','numeric'],
            'days'=>['required','numeric'],
            'image'=>['nullable','string','regex:/(jpg|jpeg|png)$/im'],
            'description'=>['nullable','string','max:700']
        ];
    }
}
