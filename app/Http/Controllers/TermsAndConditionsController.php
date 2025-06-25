<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class TermsAndConditionsController extends Controller
{
  public function showTermsAndConditions()
{
    return view('student.TermsAndConditions', ['user' => Auth::user()]);
}
}
