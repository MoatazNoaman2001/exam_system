<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    public function delete(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            \Log::error('No authenticated user found for deletion');
            return redirect()->route('setting')->with('error', 'لم يتم العثور على مستخدم مسجل.');
        }

        \Log::info('Account deletion attempt', ['user_id' => $user->id]);

        DB::beginTransaction();
        try {
            // حذف كل البيانات المرتبطة (غيّري أسماء الجداول حسب قاعدة بياناتك)
            DB::table('certifications')->where('user_id', $user->id)->delete();
            DB::table('leaderboard_entries')->where('user_id', $user->id)->delete();
            // أضيفي أي جداول تانية هنا، مثل:
            // DB::table('posts')->where('user_id', $user->id)->delete();
            // DB::table('comments')->where('user_id', $user->id)->delete();

            // حذف الصورة من التخزين
            if ($user->image) {
                Storage::disk('public')->delete('avatars/' . $user->image);
                \Log::info('Deleted user image', ['image' => $user->image]);
            }

            // حذف المستخدم
            $user->delete();
            \Log::info('User account deleted', ['user_id' => $user->id]);

            DB::commit();

            // تسجيل خروج المستخدم
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'تم حذف حسابك بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to delete account', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->route('setting')->with('error', 'فشل في حذف الحساب، حاولي مرة أخرى.');
        }
    }
}