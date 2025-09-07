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
    use AuthenticatesUsers;

    public function showLoginForm(Request $request)
    {
        if (Auth::user() != null) {
            $request->session()->regenerate();
            $user = Auth::user();



            if ($user->role == "admin") {
                return redirect()->intended('/admin/dashboard');
            }
            // $isFirstTime = !IntroAnswer::where('user_id', $user->id)->exists();

            // dd($isFirstTime);
            if ($user->first_visit) {
                $user->first_visit= false;
                $user->save();
                return redirect()->route('student.index');
            }else{
                return redirect()->route('student.sections.index');
            }

            return redirect()->route('completed-action', ['userId' => $user->id]);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();



            if ($user->role == "admin") {
                return redirect()->intended('/admin/dashboard');
            }
            // $isFirstTime = !IntroAnswer::where('user_id', $user->id)->exists();

            $isFirstTime = $user->first_visit;
            
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


        return back()->withErrors([
            'email' => __('lang.invalid_credentials'),
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        if ($user->isAdmin) {
            return '/admin/dashboard';
        }
        return IntroAnswer::where('user_id', $user->id)->exists() ? '/student/home' : route('student.intro.step', 1);
    }
}