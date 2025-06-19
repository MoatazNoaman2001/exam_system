<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationCodeController extends Controller
{
      public function verificationCode()
    {
        return view('student.verificationCode'); 
    }
}
