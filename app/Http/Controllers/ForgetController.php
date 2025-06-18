<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForgetController extends Controller
{  public function forgetPassword()
    {
        return view('student.forget-password'); 
    }
}
