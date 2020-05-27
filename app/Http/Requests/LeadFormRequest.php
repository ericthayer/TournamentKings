<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadFormRequest extends FormRequest
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
            'Name'     => 'required',
            'Email'    => 'required|email|unique:leads,email',
            'Phone'    => 'required|regex:/^[0-9]{7,15}$/',
        ];
    }

    public function messages()
    {
        return [
            'Name.required'    => 'The Name field is required.',
            'Email.required'   => 'The Email field is required.',
            'Email.email'      => 'The Email field must be a valid email address.',
            'Email.unique'     => 'The Email you entered has already been registered.',
            'Phone.required'   => 'The Phone field is required.',
            'Phone.regex'      => 'The Phone number field must be a numeric value between 7 and 15 digits.',
        ];
    }
}
