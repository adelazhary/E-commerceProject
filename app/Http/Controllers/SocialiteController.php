<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }
    public function callback(string $driver)
    {
        try {
            $socilite = Socialite::driver($driver)->user();
        } catch (Exception) {
            return to_route('login')->with('error', 'Something went during. Please try again.');
        }

        $user = Socialite::driver($driver)->user();
        $this->_registerOrLoginUser($user,$driver);
        return redirect('/');
    }
    private function _registerOrLoginUser($data,$providerName)
    {
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->password = bcrypt('12345678');
            $user->email_verified_at = now();
            $user->provider_id = $data->id;
            $user->provider_name = $providerName;
            $user->save();
        }
        event(new Registered($user));
        Auth::login($user);
        session()->regenerate();
    }
}
