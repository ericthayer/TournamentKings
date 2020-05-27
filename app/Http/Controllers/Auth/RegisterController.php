<?php

namespace App\Http\Controllers\Auth;

use App\Rules\AccessCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Tournamentkings\Entities\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Tournamentkings\Entities\Models\Player;
use App\Tournamentkings\Entities\Models\PlatformType;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Overridden - Show the application registration form or the thank you screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $platform_types = PlatformType::all();

        return view('auth.register')->with(['platform_types' => $platform_types]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'         => ['required', 'string',
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
//                'regex:/[@$!%*#?&]/', // must contain a special character'confirmed'
                'confirmed', ],          // password_confirm field must match
//            'access_code'      => ['required', new AccessCode],
            'gamer_tag'        => ['required', 'string', 'max:255', 'unique:players'],
//            'platform_type_id' => ['required', 'integer', 'exists:platform_types,id'],
            'location_id'      => ['required', 'integer', 'exists:locations,id'],
            'location'         => ['required', 'string', 'max:255'],
            'terms'            => ['required'],
        ], [
            'password.regex' => 'The Password Format is invalid. The Password must contain at least: 8 characters, one lowercase letter, one uppercase letter, at least one digit.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $player = new Player([
            'gamer_tag'        => $data['gamer_tag'],
            'platform_type_id' => $data['platform_type_id'],
            'location_id'      => $data['location_id'],
        ]);

        $user->player()->save($player);

        return $user;
    }
}
