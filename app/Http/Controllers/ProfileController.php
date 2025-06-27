<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {
        return view('student.Profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('student.profile.show')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح!');
    }

    public function updateImage(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete('avatars/' . $user->image);
            }

            // Store new image
            $path = $request->file('image')->store('avatars', 'public');
            $user->image = basename($path);
            \Log::info('Saving image', ['path' => $path, 'filename' => $user->image]);
            $user->save();
        }

        return redirect()->route('student.profile.show')
            ->with('success', 'تم تحديث الصورة الشخصية بنجاح!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        // حذف العلاقات التابعة
        $user->introAnswers()->delete();
        $user->slideAttempts()->delete();
        $user->testAttempts()->delete();
        $user->examAttempts()->delete(); // إذا كان موجودًا، تحقق من النموذج
        $user->missions()->delete();
        $user->notifications()->delete();
        $user->progress()->delete();
        $user->tasks()->delete();
        $user->quizAttempts()->delete(); // إذا كان موجودًا

        // حذف الصورة الشخصية إذا وجدت
        if ($user->image) {
            Storage::disk('public')->delete('avatars/' . $user->image);
        }

        // حذف المستخدم نفسه (حذف نهائي بسبب forceDelete)
        $user->forceDelete();

        // تسجيل الخروج بعد الحذف
        Auth::logout();

        return redirect('/login')->with('success', 'تم حذف الحساب بنجاح.');
    }
}
