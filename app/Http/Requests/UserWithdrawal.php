<?php

namespace App\Http\Requests;

use App\Rules\SufficientBalance;
use Illuminate\Foundation\Http\FormRequest;
use App\TournamentKings\Entities\Models\WithdrawalType;

class UserWithdrawal extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number' => [
                'required_if:withdrawal_type_name,'.WithdrawalType::SQUARE_CASH,
                // Inspired by http://regexlib.com/REDetails.aspx?regexp_id=61
                'regex:/((\(\d{3}\)?)|(\d{3}))([ -.x]?)(\d{3})([ -.x]?)(\d{4})/',
                'nullable',
            ],
            'amount' => [
                'required',
                'numeric',
                'regex:/[0-9]+(\.[0-9][0-9])?/',
                'gt:0',
                new SufficientBalance,
            ],
            'email' => [
                'email',
                'nullable',
            ],
            'withdrawal_type_name' => [
                'required',
                'exists:withdrawal_types,name',
            ],
        ];
    }
}
