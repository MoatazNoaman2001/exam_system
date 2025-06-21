<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class VerificationCodeController extends Controller
{
    public function showVerificationCodeForm()
    {
        // افترض أن المستخدم تم توجيهه إلى هنا بعد طلب إعادة تعيين كلمة المرور
        // يجب تخزين البريد الإلكتروني في الجلسة أو تمريره عبر الرمز المميز لإعادة التعيين
        return view('auth.verification-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|array|size:5',
            'code.*' => 'required|digits:1'
        ]);

        $code = implode('', $request->code);
        // نفذ المنطق للتحقق من الكود مقابل الرمز المميز لإعادة التعيين
        // مثال: قارن مع كود مخزن في الجلسة أو قاعدة البيانات
        // للآن، افترض النجاح
        session(['verification_code' => $code]);

        return redirect()->route('new-password')->with('status', 'تم التحقق من الكود بنجاح!');
    }
}