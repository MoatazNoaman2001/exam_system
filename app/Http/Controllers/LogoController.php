<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoController extends Controller
{
    public function index()
    {
        return view('student.logo'); // يطابق المسار resources/views/student/logo.blade.php
    }
}