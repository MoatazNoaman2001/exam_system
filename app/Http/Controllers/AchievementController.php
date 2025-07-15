<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use App\Models\ExamAttempt;
use App\Models\Domain;
use App\Models\Achievement;
use App\Models\Chapter;
use App\Models\SlideAttempt;
use App\Models\UserProgress;
use App\Notifications\UserAchievementNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch user progress
        $progress = UserProgress::where('user_id', $user->id)->first();

        // Plan duration variables
        $planDuration = session('plan_duration', 0);
        $planEndDate = session('plan_end_date');
        $daysLeft = $planEndDate ? max(0, Carbon::today()->diffInDays(Carbon::parse($planEndDate), false)) : 0;
        $progressPercent = ($planDuration > 0) ? (($planDuration - $daysLeft) / $planDuration) * 100 : 0;

        // Handle plan duration submission
        if ($request->has('plan_duration') && $request->filled('plan_duration')) {
            $planDuration = (int)$request->input('plan_duration');
            if ($planDuration > 0) {
                $planEndDate = Carbon::today()->addDays($planDuration);
                session([
                    'plan_duration' => $planDuration,
                    'plan_end_date' => $planEndDate,
                ]);
                $daysLeft = $planDuration;
                $progressPercent = 0;

                return redirect()->route('setting')->with('success', 'مبروك! لقد حددت خطتك الزمنية بنجاح! 🎯 لنبدأ الرحلة نحو التميز!');
            }
        }

        // حساب النقاط
        $completedLessons = SlideAttempt::where('user_id', $user->id)
                                       ->whereNotNull('end_date')
                                       ->count();
        $lessonPoints = $completedLessons * 50;

        $completedExams = ExamAttempt::where('user_id', $user->id)
                                     ->whereNotNull('ended_at')
                                     ->count();
        $examPoints = $completedExams * 100;

        $totalQuestions = QuizAttempt::where('user_id', $user->id)->sum('score') +
                          TestAttempt::where('user_id', $user->id)->sum('score');
        $questionPoints = floor($totalQuestions / 20) * 75;

        $completedAchievements = Achievement::where('user_id', $user->id)->count();
        $achievementPoints = $completedAchievements * 30;

        // حساب streak ونقاطه
        $activityDates = SlideAttempt::where('user_id', $user->id)
                                    ->whereNotNull('end_date')
                                    ->pluck('end_date')
                                    ->merge(
                                        ExamAttempt::where('user_id', $user->id)
                                                   ->whereNotNull('ended_at')
                                                   ->pluck('ended_at')
                                    )
                                    ->merge(
                                        QuizAttempt::where('user_id', $user->id)
                                                   ->whereNotNull('created_at')
                                                   ->pluck('created_at')
                                    )
                                    ->merge(
                                        TestAttempt::where('user_id', $user->id)
                                                   ->whereNotNull('created_at')
                                                   ->pluck('created_at')
                                    )
                                    ->map(fn($date) => Carbon::parse($date)->startOfDay())
                                    ->unique()
                                    ->sort()
                                    ->values();

        $streakDays = 0;
        $today = Carbon::today();
        $currentStreak = 0;
        $hasRecentActivity = false;

        foreach ($activityDates as $date) {
            if ($date->equalTo($today) || $date->equalTo($today->subDay())) {
                $hasRecentActivity = true;
                break;
            }
        }

        if ($hasRecentActivity) {
            for ($i = $activityDates->count() - 1; $i >= 0; $i--) {
                $currentDate = $activityDates[$i];
                if ($currentDate->equalTo($today)) {
                    $currentStreak++;
                    $today = $today->subDay();
                } elseif ($currentDate->equalTo($today->subDay())) {
                    $currentStreak++;
                    $today = $today->subDay();
                } else {
                    break;
                }
            }
        }
        $streakDays = $currentStreak;
        $streakPoints = floor($streakDays / 3) * 20;

        $totalPoints = $lessonPoints + $examPoints + $questionPoints + $achievementPoints + $streakPoints;

        $latestExamAttempt = ExamAttempt::where('user_id', $user->id)
                                        ->whereNotNull('ended_at')
                                        ->latest('ended_at')
                                        ->first();

        if ($latestExamAttempt && $latestExamAttempt->score) {
            $totalScore = $latestExamAttempt->exam->total_score ?? 100;
            $scorePercentage = ($latestExamAttempt->score / $totalScore) * 100;

            $existingNotification = \DB::table('notifications')
                                       ->where('user_id', $user->id)
                                       ->where('text', 'like', '%امتحان%')
                                       ->where('created_at', '>=', $latestExamAttempt->ended_at)
                                       ->exists();

            if (!$existingNotification) {
                if ($scorePercentage < 60) {
                    $user->notify(new UserAchievementNotification(
                        'exam_score_low',
                        'لا تقلق! هذه مجرد بداية رحلتك الرائعة! 🌟',
                        'الامتحان القادم سيكون أفضل بكثير! استمر في التعلم والمحاولة!'
                    ));
                } elseif ($scorePercentage < 70) {
                    $user->notify(new UserAchievementNotification(
                        'exam_score_mid',
                        'أنت على الطريق الصحيح! 🚀',
                        'بالمزيد من الجهد ستصل إلى القمة! نحن نؤمن بك!'
                    ));
                } elseif ($scorePercentage < 85) {
                    $user->notify(new UserAchievementNotification(
                        'exam_score_high',
                        'أحسنت! أنت قريب جداً من التميز! ✨',
                        'استمر في التقدم، النجاح الكبير ينتظرك!'
                    ));
                }
            }
        }

        // تحقق من إكمال المجالات
        $allDomains = Domain::count();
        $completedDomains = Domain::whereHas('slides', fn($query) => 
            $query->whereHas('slideAttempts', fn($q) => 
                $q->where('user_id', $user->id)->whereNotNull('end_date')
            )
        )->get()->filter(function ($domain) use ($user) {
            $totalSlides = $domain->slides()->count();
            $completedSlides = $domain->slides()->whereHas('slideAttempts', fn($q) => 
                $q->where('user_id', $user->id)->whereNotNull('end_date')
            )->count();
            return $totalSlides > 0 && $totalSlides === $completedSlides;
        })->count();

        if ($completedDomains > ($progress->domains_completed ?? 0)) {
            $user->notify(new UserAchievementNotification(
                'domain_completed',
                'إنجاز رائع! لقد أتقنت مجالاً كاملاً! 🏆',
                'كل خطوة تقربك من تحقيق أحلامك الكبيرة!'
            ));
        }

        // تحقق من إكمال الفصول
        $allChapters = Chapter::count();
        $completedChapters = Chapter::whereHas('slides', fn($query) => 
            $query->whereHas('slideAttempts', fn($q) => 
                $q->where('user_id', $user->id)->whereNotNull('end_date')
            )
        )->get()->filter(function ($chapter) use ($user) {
            $totalSlides = $chapter->slides()->count();
            $completedSlides = $chapter->slides()->whereHas('slideAttempts', fn($q) => 
                $q->where('user_id', $user->id)->whereNotNull('end_date')
            )->count();
            return $totalSlides > 0 && $totalSlides === $completedSlides;
        })->count();

        if ($completedChapters > ($progress->lessons_completed ?? 0)) {
            $user->notify(new UserAchievementNotification(
                'chapter_completed',
                'تهانينا الحارة! فصل جديد أضفته إلى سجل إنجازاتك! 🎉',
                'العلم نور، وكل فصل تكمله يضيء طريقك أكثر!'
            ));
        }

        // تحقق من إنجازات الأسئلة
        $completedQuestions = QuizAttempt::where('user_id', $user->id)->sum('score') +
                             TestAttempt::where('user_id', $user->id)->sum('score');
        $questionMilestone = floor($completedQuestions / 100) * 100;

        if ($completedQuestions >= $questionMilestone && $questionMilestone > ($progress->questions_completed ?? 0)) {
            $user->notify(new UserAchievementNotification(
                'question_milestone',
                "مذهل! لقد تجاوزت {$questionMilestone} سؤال! 🤯",
                'كل سؤال تحله يبني عقلًا أقوى! استمر في التحدي!'
            ));
        }

        // تحديث بيانات التقدم
        if ($progress) {
            $progress->points = $totalPoints;
            $progress->streak_days = $streakDays;
            $progress->domains_completed = $completedDomains;
            $progress->domains_total = $allDomains;
            $progress->lessons_completed = $completedChapters;
            $progress->lessons_total = $allChapters;
            $progress->exams_completed = $completedExams;
            $progress->questions_completed = $completedQuestions;
            $progress->save();
        }

        // حساب المستوى الحالي
        $pointsMap = [
            'مبتدئ' => 0,
            'متوسط' => 150,
            'متقدم' => 300,
            'خبير' => 500,
            'أسطورة' => 800,
        ];

        $currentLevel = 'مبتدئ';
        $nextLevel = null;
        $pointsToNext = 0;

        foreach ($pointsMap as $level => $threshold) {
            if ($totalPoints >= $threshold) {
                $currentLevel = $level;
            } else {
                $nextLevel = $level;
                $pointsToNext = $threshold - $totalPoints;
                break;
            }
        }

        if ($progress) {
            $progress->current_level = $currentLevel;
            $progress->save();
        }

        // تحقق إذا تم إكمال كل المحتوى
        $allContentCompleted = ($allDomains > 0 && $allChapters > 0 && $completedDomains === $allDomains && $completedChapters === $allChapters);

        // عرض البيانات على الصفحة
        return view('student.Achievement', compact(
            'user',
            'progress',
            'totalPoints',
            'completedDomains',
            'completedChapters',
            'completedExams',
            'completedQuestions',
            'streakDays',
            'currentLevel',
            'nextLevel',
            'pointsToNext',
            'allContentCompleted',
            'planDuration',
            'planEndDate',
            'daysLeft',
            'progressPercent'
        ));
    }
}