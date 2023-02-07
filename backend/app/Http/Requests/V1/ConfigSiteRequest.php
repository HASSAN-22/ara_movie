<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ConfigSiteRequest extends FormRequest
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
            'site_name'=>['required','string','max:255'],
            'logo'=>['required','string','regex:/(jpg|jpeg|png)$/im'],
            'logo_mobile'=>['required','string','regex:/(jpg|jpeg|png)$/im'],
            'copy_right'=>['required','string','max:255'],
//            'captcha_key'=>['required','string','max:255'],
            'front_link'=>['required','string','max:255','url'],
            'bc_link'=>['required','string','max:255','url'],
            'min_amount'=>['required','numeric'],
            'max_amount'=>['required','numeric'],
            'alert_link'=>['nullable','string','max:255','url'],
            'alert'=>['nullable','string','max:700'],
            'email'=>['nullable','email','string','max:255'],
            'mobile'=>['nullable','numeric','digits:11'],
            'phone'=>['nullable','numeric','digits:11'],
            'description'=>['nullable','string','max:700'],
            'telegram'=>['nullable','string','max:255'],
            'twitter'=>['nullable','string','max:255'],
            'facebook'=>['nullable','string','max:255'],
            'instagram'=>['nullable','string','max:255'],
            'omdb_api'=>['nullable','string','max:255'],
            'about_us'=>['required','in:yes,no','string','max:255'],
            'contact_us'=>['required','in:yes,no','string','max:255'],
            'vip'=>['required','string','in:yes,no'],
//            'captcha'=>['required','string','in:yes,no'],
            'page'=>['required','string','in:yes,no'],
        ];
    }
}
