<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SquarePayment extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nonce' => 'required',
            // Inspired by http://regexlib.com/REDetails.aspx?regexp_id=41
            'amount' => 'required|numeric|regex:/[0-9]+(\.[0-9][0-9])?/',
        ];
    }
}
