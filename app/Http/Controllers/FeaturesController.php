<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeaturesController extends Controller
{
    public function features()
    {
        return view('student.features'); // يطابق المسار resources/views/student/logo.blade.php
    }
}
