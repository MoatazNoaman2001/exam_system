<?php

namespace App\Http\Controllers;

use App\Models\IntroAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function welcome()
    {
        
        return view('student.welcome'); 
    }

    public function root(Request $request){ 
        if (Auth::user() != null) {
            $user = Auth::user(); 
    
            if ($user->role == "admin") {
                return redirect('/admin/dashboard');
            } else {
                $isFirstTime = !IntroAnswer::where('user_id', $user->id)->exists();
                if ($isFirstTime) {
                    $user->first_visit= false;
                    $user->save();
                    return redirect()->route('index');
                }else{
                    return redirect()->route('student.sections');
                }
            }
        }
    
        return view('welcome');
    }
}
