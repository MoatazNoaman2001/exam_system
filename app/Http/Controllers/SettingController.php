<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SettingController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }
        return view('student.setting', compact('user'));
    }


    public function deleteAccount(Request $request)
{
    $user = Auth::user();

    if ($user) {
        Auth::logout(); // تسجيل خروج المستخدم أولاً
        DB::table('users')->where('id', $user->id)->delete(); // حذف نهائي من الجدول

        return redirect('/')->with('success', 'تم حذف حسابك نهائيًا.');
    }

    return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الحساب.');
}
 
}
 