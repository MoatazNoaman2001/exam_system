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

        $progress = UserProgress::where('user_id', $user->id)->first();

        $planDuration = session('plan_duration', 0); 
        $planEndDate = session('plan_end_date'); 
        $daysLeft = $planEndDate ? max(0, Carbon::today()->diffInDays(Carbon::parse($planEndDate), false)) : 0; 
        $progressPercent = ($planDuration > 0) ? (($planDuration - $daysLeft) / $planDuration) * 100 : 0;

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

                return redirect()->route('setting')->with('success', __('lang.plan_set_success'));
            }
        }

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
                                       ->where('text', 'like', '%'.__('lang.exam').'%')
                                       ->where('created_at', '>=', $latestExamAttempt->ended_at)
                                       ->exists();

            if (!$existingNotification) {
                if ($scorePercentage < 60) {
                    $user->notify(new UserAchievementNotification(
                        'exam_score_low',
                        __('lang.exam_score_low'),
                        __('lang.exam_score_low_subtext')
                    ));
                } elseif ($scorePercentage < 70) {
                    $user->notify(new UserAchievementNotification(
                        'exam_score_mid',
                        __('lang.exam_score_mid'),
                        __('lang.exam_score_mid_subtext')
                    ));
                } elseif ($scorePercentage < 85) {
                    $user->notify(new UserAchievementNotification(
                        'exam_score_high',
                        __('lang.exam_score_high'),
                        __('lang.exam_score_high_subtext')
                    ));
                }
            }
        }

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
                __('lang.domain_completed'),
                __('lang.domain_completed_subtext')
            ));
        }

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
                __('lang.chapter_completed'),
                __('lang.chapter_completed_subtext')
            ));
        }

        $completedQuestions = QuizAttempt::where('user_id', $user->id)->sum('score') +
                             TestAttempt::where('user_id', $user->id)->sum('score');
        $questionMilestone = floor($completedQuestions / 100) * 100;

        if ($completedQuestions >= $questionMilestone && $questionMilestone > ($progress->questions_completed ?? 0)) {
            $user->notify(new UserAchievementNotification(
                'question_milestone',
                __('lang.question_milestone', ['milestone' => $questionMilestone]),
                __('lang.question_milestone_subtext')
            ));
        }

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

        $pointsMap = [
            __('lang.level_beginner') => 0,
            __('lang.level_intermediate') => 150,
            __('lang.level_advanced') => 300,
            __('lang.level_expert') => 500,
            __('lang.level_legend') => 800,
        ];

        $currentLevel = __('lang.level_beginner');
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

        $allContentCompleted = ($allDomains > 0 && $allChapters > 0 && $completedDomains === $allDomains && $completedChapters === $allChapters);

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