<?php

   namespace App\Http\Controllers;

   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;

   class ContactUsController extends Controller
   {
       public function index()
       {
           $user = Auth::user();
           return view('student.ContactUs', compact('user'));
       }
   }