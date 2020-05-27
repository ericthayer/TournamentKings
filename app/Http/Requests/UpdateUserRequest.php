<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\TournamentKings\Entities\Data\PlatformNetworkType;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $platformTypes = implode(',', PlatformNetworkType::getValues());

        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255'],
            'password'              => ['required', 'string',
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
//                'regex:/[@$!%*#?&]/', // must contain a special character'confirmed'
                'confirmed', ],          // password_confirm field must match
            'gamer_tag'             => ['required', 'string', 'max:255'],
//            'platform_type_id'      => ['required', 'integer', 'exists:platform_types,id'],
            'platform_network'      => ['required', 'string', 'in:'.$platformTypes],
            'platform_network_id'   => ['required', 'string'],
            'location_id'           => ['required', 'integer', 'exists:locations,id'],
            'location'              => ['required', 'string', 'max:255'],
            'terms'                 => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'The Password Format is invalid. The Password must contain at least: 8 characters, one lowercase letter, one uppercase letter, at least one digit.',
        ];
    }
}
