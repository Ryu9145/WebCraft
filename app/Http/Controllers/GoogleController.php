<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // 1. Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle Callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $finduser = User::where('google_id', $googleUser->id)->first();

            if($finduser){
                Auth::login($finduser);
                
                // Cek Role
                if ($finduser->role == 'admin' || $finduser->role == 'superadmin') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                $checkEmail = User::where('email', $googleUser->email)->first();

                if($checkEmail) {
                    $checkEmail->update(['google_id' => $googleUser->id]);
                    Auth::login($checkEmail);
                } else {
                    $newUser = User::create([
                        'username' => Str::slug($googleUser->name) . rand(100, 999),
                        'email' => $googleUser->email,
                        'google_id'=> $googleUser->id,
                        'password' => Hash::make('123456dummy'),
                        'role' => 'user',
                    ]);

                    Auth::login($newUser);
                }
                return redirect()->route('home');
            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}