<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Tournamentkings\Entities\Models\User;
use App\Tournamentkings\Entities\Models\Player;
use App\Tournamentkings\Entities\Models\PlatformType;
use App\TournamentKings\Entities\Data\PlatformNetworkType;

class RegisterUserController extends Controller
{
    public function edit(Request $request, User $user)
    {
        $platformTypes = PlatformType::all();
        $user->load('player');

        $platformNetworks = [
                $user->player->platform_network,
            ];

        if ($user->player->platform_network === PlatformNetworkType::CROSS_PLATFORM) {
            $platformNetworks = PlatformNetworkType::getValues();
            array_pop($platformNetworks);
        }

        return view('auth.complete-registration')->with(['user' => $user, 'platform_types' => $platformTypes, 'platform_networks' => $platformNetworks]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $player   = Player::find($user->player->id);
        $redirect = $request->input('redirect');

        $updatedUserData['name']        = $request->input('name');
        $updatedUserData['email']       = $request->input('email');
        $updatedUserData['password']    = $request->input('password');

        $updatedPlayerData['gamer_tag']           = $request->input('gamer_tag');
        $updatedPlayerData['platform_type_id']    = $request->input('platform_type_id');
        $updatedPlayerData['location_id']         = $request->input('location_id');
        $updatedPlayerData['platform_network_id'] = $request->input('platform_network_id');

        $user->update($updatedUserData);
        $player->update($updatedPlayerData);

        return redirect($redirect);
    }
}
