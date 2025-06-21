<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\IntroAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // OPTION 1: Use Laravel's built-in Auth::attempt (RECOMMENDED)
        $credentials = $request->only('email', 'password');
        
        // Check if user exists and is verified
        $user = User::where('email', $credentials['email'])->first();
        // print($credentials['password']);
        // print($user->password);
        // // Temporarily add this to your login method
        // $test = Hash::make('QTJKLas4321');
        // dd([
        //     'new_hash' => $test,
        //     'check_new' => Hash::check('QTJKLas4321', $test), // Should be true
        //     'check_stored' => Hash::check('QTJKLas4321', $user->password) // Your check
        // ]);
        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->onlyInput('email');
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('message', 'Please verify your email address before logging in.');
        }

        // Use Laravel's built-in authentication
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            if( $user->is_admin){
                return redirect()->intended('/admin/dashboard');
            } else{

                $isFirstTime = IntroAnswer::where('user_id' , $user->id)->count() > 0;
                if (!$isFirstTime) {
                    return redirect()->route('student.intro.step', 1);
                }else{
                    return redirect()->intended('/student/home');
                }
            }
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}