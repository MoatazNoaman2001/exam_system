<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
            'role' => 'required|in:student,admin',
            'preferred_language' => 'required|in:ar,en',
            'is_agree' => 'accepted',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password, // Hashed via setPasswordAttribute
            'phone' => $request->phone,
            'role' => $request->role,
            'preferred_language' => $request->preferred_language,
            'is_agree' => true,
            'verified' => false,
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }
}
