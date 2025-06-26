<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('student.profile.index');
    }

    public function edit()
    {
        return view('student.profile.edit');
    }

    public function update(Request $request)
    {

        return redirect()->route('student.profile.index')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
