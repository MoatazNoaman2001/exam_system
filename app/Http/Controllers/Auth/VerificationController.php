<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{

    use VerifiesEmails;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('auth')->except(['verify', 'show']);
        $this->middleware('guest')->only(['show']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('login')->with('error', __('lang.user_not_found'));
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', __('lang.invalid_verification_link'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    public function show()
    {
        return view('auth.verify');
    }

    protected function redirectTo()
    {
        $user = auth()->user();
        if ($user && $user->isAdmin) {
            return '/admin/dashboard';
        }
        return '/student/home';
    }
}