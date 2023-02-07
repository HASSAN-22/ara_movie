<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'mobile'=>['required','numeric','digits:11','unique:users,id,mobile'],
            'email'=>['required','email','string','unique:users,id,email'],
            'password'=>['required','string','min:6','max:255'],
            'p_password'=>['required','string','min:6','max:255'],
            'confirm_code'=>['required','numeric','digits:6'],
        ];
        if($this->avatar != 'null'){
            $rules['avatar'] = ['image','mimes:jpg,jpeg,png','max:700'];
        }
        return $rules;
    }
}
