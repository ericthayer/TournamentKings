<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use App\Tournamentkings\Entities\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\TournamentKings\Entities\Data\PlatformNetworkType;

class CreateUserRequest extends BaseAPIRequest
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
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gamer_tag'        => ['required', 'string', 'max:255', 'unique:players'],
            'discord_id'       => ['required', 'unique:users'],
//            'platform_network' => ['required', 'string', 'in:'.$platformTypes],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $user = $this->doesUserAlreadyExist($validator);

        if ($user) {
            if (is_null($user->discord_id) && ! is_null($this->request->get('discord_id'))) {
                $user->discord_id = $this->request->get('discord_id');
                $user->save();
            }
        } else {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(response()->json(
            $user,
            JsonResponse::HTTP_CONFLICT
        ));
    }

    protected function doesUserAlreadyExist(Validator $validator): ?User
    {
        if (array_key_exists('email', $validator->errors()->messages())) {
            if (in_array('The email has already been taken.', $validator->errors()->messages()['email'])) {
                return $this->getUserByColumn('email');
            }
        } elseif (array_key_exists('discord_id', $validator->errors()->messages())) {
            if (in_array('The discord id has already been taken.', $validator->errors()->messages()['discord_id'])) {
                return $this->getUserByColumn('discord_id');
            }
        }
    }

    protected function getUserByColumn($column): ?User
    {
        return User::where($column, $this->request->get($column))->first();
    }
}
