<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\UserProgress;
use App\Models\Slide;
use App\Models\Exam;
use App\Models\Achievement;
use App\Models\TestAttempt;
use App\Models\QuizAttempt;
use App\Models\Domain;

class AchievementController extends Controller
{
   public function index()
{
    $user = Auth::user();
    if (!$user) {
        abort(403, 'يجب تسجيل الدخول أولاً.');
    }

    $progress = $user->progress()->firstOrCreate([
        'user_id' => $user->id,
    ], [
        'plan_id' => (string) Str::ulid(),
        'days_left' => 30,
        'progress' => 0,
        'points' => 0,
        'current_level' => 'مبتدئ',
        'points_to_next_level' => 150,
        'plan_duration' => 30,
        'plan_end_date' => Carbon::now()->addDays(30)->toDateString(),
        'domains_completed' => 0,
        'domains_total' => Domain::count(),
        'lessons_completed' => 0,
        'lessons_total' => Slide::count(),
        'exams_completed' => 0,
        'exams_total' => Exam::count(),
        'questions_completed' => 0,
        'questions_total' => 200,
        'lessons_milestone' => 0,
        'questions_milestone' => 0,
        'streak_days' => 0,
    ]);

    // تحديث الإنجازات والإحصائيات
    $progress->lessons_completed = UserProgress::where('user_id', $user->id)->whereNotNull('slide_id')->where('status', 'completed')->count();
    $progress->exams_completed = UserProgress::where('user_id', $user->id)->whereNotNull('exam_id')->where('status', 'completed')->count();
    $progress->questions_completed = TestAttempt::where('user_id', $user->id)->count() + QuizAttempt::where('user_id', $user->id)->count();

    // المجالات المكتملة
    $domains = Domain::with('slides')->get();
    $progress->domains_completed = $domains->filter(function ($domain) use ($user) {
        $slides = $domain->slides;
        $completed = UserProgress::where('user_id', $user->id)->whereIn('slide_id', $slides->pluck('id'))->where('status', 'completed')->count();
        return $completed == $slides->count();
    })->count();

    // حساب النقاط
    $totalPoints = 0;
    $totalPoints += $progress->lessons_completed * 50;
    $totalPoints += $progress->exams_completed * 100;
    $totalPoints += floor($progress->questions_completed / 20) * 75;
    $totalPoints += Achievement::where('user_id', $user->id)->count() * 30;

    // أيام الاستمرارية
    $activityDates = UserProgress::where('user_id', $user->id)
        ->where('status', 'completed')
        ->pluck('completed_at')
        ->map(fn($date) => Carbon::parse($date)->startOfDay())
        ->unique()
        ->sort()
        ->values();

    $streakDays = 0;
    if ($activityDates->isNotEmpty()) {
        $currentStreak = 1;
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastDate = $activityDates->last();

        if ($lastDate->equalTo($today) || $lastDate->equalTo($yesterday)) {
            for ($i = $activityDates->count() - 2; $i >= 0; $i--) {
                $curr = $activityDates[$i];
                $next = $activityDates[$i + 1];
                if ($curr->diffInDays($next) == 1) {
                    $currentStreak++;
                } else {
                    break;
                }
            }
            $streakDays = $currentStreak;
        }
    }

    $totalPoints += floor($streakDays / 3) * 20;

    // ✅ المستويات
    $levels = [
        ['name' => 'مبتدئ', 'min_points' => 0],
        ['name' => 'متوسط', 'min_points' => 150],
        ['name' => 'محترف', 'min_points' => 300],
        ['name' => 'خبير', 'min_points' => 500],
        ['name' => 'أسطورة', 'min_points' => 800],
    ];

    $currentLevel = 'مبتدئ';
    $nextLevel = null;
    $pointsToNext = null;

    foreach ($levels as $index => $level) {
        if ($totalPoints >= $level['min_points']) {
            $currentLevel = $level['name'];
            if (isset($levels[$index + 1])) {
                $nextLevel = $levels[$index + 1]['name'];
                $pointsToNext = $levels[$index + 1]['min_points'] - $totalPoints;
            }
        }
    }

    $progress->points = $totalPoints;
    $progress->streak_days = $streakDays;
    $progress->current_level = $currentLevel;
    $progress->points_to_next_level = $pointsToNext ?? 0;

    // باقي الحسابات
    $progress->days_left = max(0, Carbon::parse($progress->plan_end_date)->diffInDays(Carbon::now(), false));
    $totalTasks = $progress->lessons_total + $progress->exams_total;
    $completedTasks = $progress->lessons_completed + $progress->exams_completed;
    $progress->progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    $progress->save();

    return view('student.Achievement', compact('user', 'progress', 'totalPoints', 'streakDays', 'nextLevel', 'pointsToNext'));
}
}
