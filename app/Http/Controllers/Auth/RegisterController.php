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

    use RegistersUsers;

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/'
            ],
            'phone' => ['required', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'role' => ['required', 'in:student,admin'],
            'preferred_language' => ['required', 'in:ar,en'],
            'is_agree' => ['accepted'],
        ], [
            'password.regex' => __('lang.password_requirements'),
            'username.regex' => __('lang.username_requirements'),
            'is_agree.accepted' => __('lang.must_agree_terms'),
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'preferred_language' => $data['preferred_language'],
            'is_agree' => true,
            'verified' => false,
        ]);

    
        $user->sendEmailVerificationNotification();
    

 if ($user->role === 'student') {
        return redirect()->route('completedAction');


        if ($user->role === 'student') {
            return redirect()->route('completedAction');
        }
    }
}

    protected function registered(Request $request, $user)
    {
        $user->sendEmailVerificationNotification();
        return redirect()->route('verification.notice');
    }
}

