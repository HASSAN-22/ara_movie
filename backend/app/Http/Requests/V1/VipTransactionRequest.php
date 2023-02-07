<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class VipTransactionRequest extends FormRequest
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
            'cart_id'=>['required','numeric'],
            'plan_id'=>['required','numeric'],
            'discount'=>['nullable','string','max:255'],
            'type_pay'=>['required','string','in:wallet,bank'],
            'bank_portal_id'=>['nullable','numeric'],
        ];
    }
}
