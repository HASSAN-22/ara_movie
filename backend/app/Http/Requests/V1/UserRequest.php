<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'name'=>['required','string','max:255'],
            'access'=>['required','string','in:admin,user'],
            'email'=>['required','string','email','max:255','unique:users,id,email'],
            'mobile'=>['required','numeric','digits:11','unique:users,id,mobile'],
            'status'=>['required','string','in:yes,no'],
            'password'=>['required','string','min:6','max:255'],
        ];
        if($this->isMethod('patch')){
            $rules['password'][0] = 'nullable';
        }
        return $rules;
    }
}
