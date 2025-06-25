<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangPasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('student.changePassword');
    }

    public function updatePassword(Request $request)
    {
        try {
            \Log::info('Request received: ' . json_encode($request->all()));

            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'لم يتم العثور على المستخدم الحالي']);
            }

            // تحقق من صحة البيانات مع تأكيد كلمة المرور الجديدة
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            // تحقق من كلمة المرور الحالية
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'كلمة المرور الحالية غير صحيحة']);
            }

            // منع استخدام نفس كلمة المرور القديمة
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'كلمة المرور الجديدة لا يجب أن تكون مثل الحالية']);
            }

            // تحديث كلمة المرور بدون تشفير يدوي (لأن الموديل فيه setter يشفر تلقائياً)
            $user->password = $request->new_password;
            $user->save();

            // تسجيل دخول تلقائي بالمستخدم الجديد
            Auth::login($user);

            return response()->json(['success' => true, 'message' => 'تم تحديث كلمة المرور وتسجيل الدخول بنجاح']);
        } catch (\Exception $e) {
            \Log::error('Error updating password: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء تحديث كلمة المرور']);
        }
    }
}
