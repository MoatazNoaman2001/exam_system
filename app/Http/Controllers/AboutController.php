<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    public function index()
      {
          return view('student.About', [
              'user' => Auth::user()
          ]);
      }
}
