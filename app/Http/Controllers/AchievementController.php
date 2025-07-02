<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use App\Models\ExamAttempt;
use App\Models\Plan;
use App\Models\Domain;
use App\Models\Achievement;
use App\Models\Slide;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'يجب تسجيل الدخول أولاً.');
        }

        // التحقق من وجود محاولات اختبارات
        $test_attempts = TestAttempt::where('user_id', $user->id)->count();
        $quiz_attempts = QuizAttempt::where('user_id', $user->id)->count();
        $exam_attempts = ExamAttempt::where('user_id', $user->id)->count();
        $has_attempts = ($test_attempts > 0 || $quiz_attempts > 0 || $exam_attempts > 0);

        // تعريف المتغيرات الافتراضية
        $totalPoints = 0;
        $streakDays = 0;
        $nextLevel = null;
        $pointsToNext = 150;
        $progress = (object) [
            'questions_completed' => $test_attempts + $quiz_attempts,
            'domains_completed' => 0,
            'exams_completed' => 0,
            'lessons_completed' => 0,
            'points' => 0,
            'streak_days' => 0,
            'current_level' => 'مبتدئ',
            'points_to_next_level' => 150,
        ];

        // إنشاء أو جلب بيانات الـ plan إذا كان فيه محاولات
        if ($has_attempts) {
            $planId = (string) Str::uuid();
            Plan::firstOrCreate(
                ['id' => $planId],
                ['name' => 'Default Plan', 'description' => 'A default plan for users']
            );

            $progress = $user->progress()->firstOrCreate([
                'user_id' => $user->id,
            ], [
                'plan_id' => $planId,
                'days_left' => 30,
                'progress' => 0,
                'points' => 0,
                'current_level' => 'مبتدئ',
                'points_to_next_level' => 150,
                'plan_duration' => 30,
                'plan_end_date' => date('Y-m-d', strtotime('+30 days')),
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

            // تحديث الإحصائيات
            $progress->lessons_completed = UserProgress::where('user_id', $user->id)
                ->whereNotNull('slide_id')
                ->where('status', 'completed')
                ->count();

            $progress->exams_completed = UserProgress::where('user_id', $user->id)
                ->whereNotNull('exam_id')
                ->where('status', 'completed')
                ->count();

            $progress->questions_completed = $test_attempts + $quiz_attempts;

            // المجالات المكتملة
            $domains = Domain::with('slides')->get();
            $progress->domains_completed = $domains->filter(function ($domain) use ($user) {
                $slides = $domain->slides;
                $completed = UserProgress::where('user_id', $user->id)
                    ->whereIn('slide_id', $slides->pluck('id'))
                    ->where('status', 'completed')
                    ->count();
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
                ->map(function ($date) {
                    return date('Y-m-d', strtotime($date));
                })
                ->unique()
                ->sort()
                ->values();

            $streakDays = 0;
            if ($activityDates->isNotEmpty()) {
                $currentStreak = 1;
                $today = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $lastDate = $activityDates->last();

                if ($lastDate == $today || $lastDate == $yesterday) {
                    for ($i = $activityDates->count() - 2; $i >= 0; $i--) {
                        $curr = $activityDates[$i];
                        $next = $activityDates[$i + 1];
                        if (strtotime($curr) == strtotime($next . ' -1 day')) {
                            $currentStreak++;
                        } else {
                            break;
                        }
                    }
                    $streakDays = $currentStreak;
                }
            }

            $totalPoints += floor($streakDays / 3) * 20;

            // المستويات
            $levels = [
                ['name' => 'مبتدئ', 'min_points' => 0],
                ['name' => 'متوسط', 'min_points' => 150],
                ['name' => 'محترف', 'min_points' => 300],
                ['name' => 'خبير', 'min_points' => 500],
                ['name' => 'أسطورة', 'min_points' => 800],
            ];

            $currentLevel = 'مبتدئ';
            $nextLevel = null;
            $pointsToNext = 150;

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
            $progress->points_to_next_level = $pointsToNext;

            // تحديث الأيام المتبقية
            $progress->days_left = $progress->plan_end_date
                ? max(0, (int) ((strtotime($progress->plan_end_date) - strtotime(date('Y-m-d'))) / (60 * 60 * 24)))
                : 0;

            // تحديث نسبة التقدم
            $totalTasks = $progress->lessons_total + $progress->exams_total;
            $completedTasks = $progress->lessons_completed + $progress->exams_completed;
            $progress->progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            $progress->save();
        }

        // جيب المجالات المكتملة بس
        $completed_domains = Domain::whereHas('slides', function ($query) use ($user) {
            $query->whereIn('id', UserProgress::where('user_id', $user->id)
                ->where('status', 'completed')
                ->pluck('slide_id'));
        })->get();

        // جيب الامتحانات
        $exams = Exam::with(['questions.answers'])->where('id', '0197b25d-c661-713d-86f5-5f834824314e')->get();

        return view('student.Achievement', compact('user', 'progress', 'totalPoints', 'streakDays', 'nextLevel', 'pointsToNext', 'completed_domains', 'exams'));
    }
}