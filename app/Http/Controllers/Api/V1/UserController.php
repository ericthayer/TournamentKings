<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Tournamentkings\Entities\Models\User;
use App\Tournamentkings\Entities\Models\Player;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $newUser        = new User;
        $playerProfile  = new Player;
        $password       = Str::random(8);

        $newUser->name       = $request->input('gamer_tag');
        $newUser->email      = $request->input('email');
        $newUser->discord_id = $request->input('discord_id');
        $newUser->photo_url  = $request->input('photo_url');
        $newUser->password   = Hash::make($password);
        $newUser->save();

        $playerProfile->platform_network        = $request->platform_network;
        $playerProfile->gamer_tag               = $request->input('gamer_tag');
        $playerProfile->user_id                 = $newUser->id;
        $playerProfile->platform_type_id        = 1;
        $playerProfile->location_id             = 6488;
        $playerProfile->save();

        $newUser->registerUrl = URL::temporarySignedRoute('complete-register.edit', now()->addDays(7), ['user' => $newUser->id, 'redirect' => $request->input('redirect')]);

        return response($newUser->load('player')->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response($user->load('player'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
