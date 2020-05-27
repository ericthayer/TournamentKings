<?php

namespace App\Http\Requests;

use App\Rules\MultipleOfTwo;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TournamentByeFormatLimitations;
use App\Tournamentkings\Entities\Models\Tournament;

class TournamentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tournamentTypes = implode(',', array_keys(Tournament::TOURNAMENT_TYPES));
        $tournament      = Tournament::where('start_datetime', $this->request->get('start_datetime'))->where('name', $this->request->get('name'))->first();

        $rules = [
            'name'                  => 'required|string|max:255',
            'game_type_id'          => 'required|integer|exists:game_types,id',
            'tournament_type'       => 'required|in:'.$tournamentTypes,
            'description'           => 'required|string|max:255',
            'start_datetime'        => 'required|date|after_or_equal:Carbon::now()',
            'total_slots'           => [
                'required',
                'integer',
                "gte:$tournament->total_slots", // on update, this number cannot decrease to below the current size
                new MultipleOfTwo, // power of 2, for poc just list valid values up to 256
                new TournamentByeFormatLimitations,
            ],
            'entry_fee_type_name'        => 'exists:entry_fee_types,name',
        ];

        if ($this->request->get('tournament_type') == 'private') {
            $rules['password'] = [
                'required_if:tournament_type,private',
                'string',
                'min:8',
                'confirmed',
            ];
        }

        switch ($this->request->get('entry_fee_type_name')) {
            case 'flat-fee':
                $rules['entry_fee'] = [
                    'required_if:entry_fee_type_name,flat-fee',
                    'numeric',
                    'regex:/^[0-9]+(\.[0-9][0-9])?$/',
                ];
                break;

            case 'target-pot':
                $rules['target_pot'] = [
                    'required_if:entry_fee_type_name,target-pot',
                    'numeric',
                    'regex:/^[0-9]+(\.[0-9][0-9])?$/',
                ];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'start_datetime.after_or_equal' => 'The start datetime must be a date after or equal to now.',
            'total_slots.gte'               => 'The available slots can not be less then the existing total slots. ',
            'password.regex'                => 'The Password Format is invalid. The Password must contain at least: 8 characters, one lowercase letter, one uppercase letter, at least one digit, and one special character.',
            'entry_fee.regex'               => __('balance.amount-format'),
            'target_pot.regex'              => __('balance.amount-format'),
            '',
        ];
    }
}
