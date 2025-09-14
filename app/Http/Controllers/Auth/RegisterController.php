<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\IntroAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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

    public function showRegistrationForm(Request $request)
    {
        if (Auth::user() != null) {
            $request->session()->regenerate();
            $user = Auth::user();



            if ($user->role == "admin") {
                return redirect()->intended('/admin/dashboard');
            }
            $isFirstTime = !IntroAnswer::where('user_id', $user->id)->exists();

            // dd($isFirstTime);
            if ($isFirstTime) {
                $user->first_visit= false;
                $user->save();
                return redirect()->route('student.index');
            }else{
                return redirect()->route('student.sections.index');
            }

            return redirect()->route('completed-action', ['userId' => $user->id]);
        }
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
        $user = User::create([
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

        return $user;
    }
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        
        if (!$email) {
            return response()->json([
                'exists' => false,
                'message' => 'Email is required'
            ], 400);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'exists' => false,
                'message' => 'Invalid email format'
            ], 400);
        }
        
        $emailExists = User::where('email', $email)->exists();
        
        return response()->json([
            'exists' => $emailExists,
            'message' => $emailExists ? 
                __('lang.email_already_exists') : 
                __('lang.email_available')
        ]);
    }
    protected function registered(Request $request, $user)
    {
        $user->sendEmailVerificationNotification();
        return redirect()->route('verification.notice');
    }
}

