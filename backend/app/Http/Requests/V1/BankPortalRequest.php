<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class BankPortalRequest extends FormRequest
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
            'bank_name'=>['required','string','in:melat,parsian,zarinpal'],
            'code_id'=>['required','string','max:255'],
            'username'=>['nullable','string','max:255'],
            'password'=>['nullable','string','max:255'],
            'status'=>['required','string','in:yes,no'],
        ];
    }

}
