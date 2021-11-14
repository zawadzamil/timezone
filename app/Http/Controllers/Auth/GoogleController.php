<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Socialite;
use Auth;
use Exception;


class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();


            $finduser = User::where('google_id', $user->id)->first();

            if($finduser){

                Auth::login($finduser);

                return redirect(RouteServiceProvider::HOME);

            }
            else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => encrypt('123456dummy'),
                    'role' =>'user'
                ]);

                Auth::login($newUser);

                return redirect(RouteServiceProvider::HOME);
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
