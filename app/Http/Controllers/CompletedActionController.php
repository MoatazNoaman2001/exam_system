<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\UserProgress;
use Carbon\Carbon;

class CompletedActionController extends Controller
{
    public function completedAction($userId)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $userId) {
            abort(403, 'غير مسموح بالوصول.');
        }

        $progress = $user->progress ?? $user->progress()->create([
            'days_left' => 45,
            'progress' => 60,
            'points' => 1350,
            'current_level' => 'مجتهد',
            'points_to_next_level' => 150,
            'plan_duration' => 45,
            'plan_end_date' => now()->addDays(45)->toDateString(),
            'domains_completed' => 0,
            'domains_total' => 5,
            'lessons_completed' => 8,
            'lessons_total' => 40,
            'exams_completed' => 0,
            'exams_total' => 10,
            'questions_answered' => 200,
            'questions_total' => 200,
            'achievements_completed' => 4,
            'streak_days' => 7,
        ]);

 // حساب الأيام المتبقية بناءً على plan_end_date
        $planEndDate = Carbon::parse($progress->plan_end_date);
        $daysLeft = max(0, $planEndDate->diffInDays(Carbon::now(), false));
        $progress->days_left = $daysLeft; // تحديث القيمة يوميًا
        $progress->save();


        // تحديث نسبة التقدم بناءً على الدروس المكتملة
        $lessonsTotal = $progress->lessons_total > 0 ? $progress->lessons_total : 1; 
        $progress->progress = round(($progress->lessons_completed / $lessonsTotal) * 100);

        $progress->save();

        // حساب نسبة المستخدم من حيث النقاط مقارنة بالآخرين
        $userPoints = $progress->points ?? 0;
        $allPoints = UserProgress::pluck('points')->filter()->sortDesc()->values();
        $rank = $allPoints->search($userPoints);
        $totalUsers = $allPoints->count();
        $percent = $rank !== false ? round(100 - ($rank / $totalUsers * 100)) : 0;

        $tasks = $user->tasks()->whereDate('created_at', now()->toDateString())->get();
        $notifications = $user->notifications;

        if ($progress->lessons_completed < $progress->lessons_total) {
            $currentStep = 'فصول';
        } elseif ($progress->domains_completed < $progress->domains_total) {
            $currentStep = 'مجالات';
        } elseif ($progress->exams_completed < $progress->exams_total) {
            $currentStep = 'تدريبات';
        } else {
            $currentStep = 'اختبارات';
        }

        return view('student.completedAction', compact(
            'user',
            'progress',
            'tasks',
            'notifications',
            'percent',
            'currentStep'
        ));
    }

    // زيادة النقاط عند إكمال درس
    public function completeLesson()
    {
        $user = Auth::user();
        $progress = $user->progress;

        $progress->points += 50;
        $progress->lessons_completed += 1;

        if (method_exists($progress, 'updateLevel')) {
            $progress->updateLevel();
        }
        $progress->save();

        return response()->json(['message' => 'تم تحديث النقاط بعد إكمال الدرس']);
    }

    // زيادة النقاط عند إكمال اختبار
    public function completeExam()
    {
        $user = Auth::user();
        $progress = $user->progress;

        $progress->points += 100;
        $progress->exams_completed += 1;

        if (method_exists($progress, 'updateLevel')) {
            $progress->updateLevel();
        }
        $progress->save();

        return response()->json(['message' => 'تم تحديث النقاط بعد إكمال الاختبار']);
    }

    // زيادة النقاط عند حل 20 سؤال تدريبي
    public function completeQuestions($count)
    {
        $user = Auth::user();
        $progress = $user->progress;

        $setsOf20 = floor($count / 20);
        $pointsToAdd = $setsOf20 * 75;

        $progress->points += $pointsToAdd;
        $progress->questions_completed += $count;

        if (method_exists($progress, 'updateLevel')) {
            $progress->updateLevel();
        }
        $progress->save();

        return response()->json(['message' => 'تم تحديث النقاط بعد حل الأسئلة']);
    }

    // زيادة النقاط عند تحقيق إنجاز
    public function completeAchievement()
    {
        $user = Auth::user();
        $progress = $user->progress;

        $progress->points += 30;
        $progress->achievements_completed += 1;

        if (method_exists($progress, 'updateLevel')) {
            $progress->updateLevel();
        }
        $progress->save();

        return response()->json(['message' => 'تم تحديث النقاط بعد تحقيق الإنجاز']);
    }

    public function dailyStreak($days)
    {
        $user = Auth::user();
        $progress = $user->progress;

        if ($days >= 3) {
            $progress->points += 20;
            $progress->streak_days = $days;
        }

        if (method_exists($progress, 'updateLevel')) {
            $progress->updateLevel();
        }
        $progress->save();

        return response()->json(['message' => 'تم تحديث النقاط بعد الدراسة اليومية']);
    }
}
