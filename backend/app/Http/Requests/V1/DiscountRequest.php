<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
            'plan_id'=>['required','numeric'],
            'discount'=>['required','numeric'],
            'code'=>['required','string','max:255','unique:discounts,code,id'],
            'expire'=>['required','date_format:Y-m-d']
        ];
    }
}
