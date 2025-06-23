<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProgress; // غيّرنا من Progress إلى UserProgress

class LeaderBoardController extends Controller
{
    public function showLeaderBoard($userId)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $userId) {
            abort(403, 'غير مسموح بالوصول.');
        }

        $progress = $user->progress ?? $user->progress()->create([
            'points' => 1350,
            'top_users_percent' => 'أعلى 10%',
            'plan_duration' => 70,
            'plan_start_date' => now()->toDateString(),
            'plan_end_date' => now()->addDays(70)->toDateString(),
        ]);

        // جلب أعلى 10 مستخدمين بناءً على النقاط
        $topUsers = User::leftJoin('user_progress', 'users.id', '=', 'user_progress.user_id')
            ->select('users.id', 'users.username', 'users.image', \DB::raw('COALESCE(user_progress.points, 0) as points'))
            ->orderBy('points', 'desc')
            ->take(10)
            ->get();

        // حساب ترتيب اليوزر الحالي
        $userRank = User::leftJoin('user_progress', 'users.id', '=', 'user_progress.user_id')
            ->where(\DB::raw('COALESCE(user_progress.points, 0)'), '>', $progress->points ?? 0)
            ->count() + 1;

        return view('student.LeaderBoard', compact('user', 'progress', 'topUsers', 'userRank'));
    }
}