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
use App\Models\Chapter;
use App\Models\SlideAttempt;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fetch user progress
        $progress = UserProgress::where('user_id', $user->id)->first();

        // Initialize plan duration variables
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

                // Redirect to setting after saving
                return redirect()->route('setting')->with('success', 'تم حفظ الخطة الزمنية بنجاح!');
            }
        }

        // Calculate points based on activities
        // 1. Completing a slide = 50 points
        $completedLessons = SlideAttempt::where('user_id', $user->id)
                                       ->whereNotNull('end_date')
                                       ->count();
        $lessonPoints = $completedLessons * 50;

        // 2. Completing an exam = 100 points
        $completedExams = ExamAttempt::where('user_id', $user->id)
                                     ->whereNotNull('ended_at')
                                     ->count();
        $examPoints = $completedExams * 100;

        // 3. Solving 20 questions = 75 points
        $totalQuestions = QuizAttempt::where('user_id', $user->id)->sum('score') +
                          TestAttempt::where('user_id', $user->id)->sum('score');
        $questionPoints = floor($totalQuestions / 20) * 75;

        // 4. Achieving a milestone = 30 points
        $completedAchievements = Achievement::where('user_id', $user->id)->count();
        $achievementPoints = $completedAchievements * 30;

        // 5. Daily learning streak (3 days) = 20 points
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
                                    ->map(function ($date) {
                                        return Carbon::parse($date)->startOfDay();
                                    })
                                    ->unique()
                                    ->sort()
                                    ->values();

        $streakDays = 0;
        $today = Carbon::today();
        $currentStreak = 0;
        $hasRecentActivity = false;

        // Check if there's activity today or yesterday
        foreach ($activityDates as $date) {
            if ($date->equalTo($today) || $date->equalTo($today->subDay())) {
                $hasRecentActivity = true;
                break;
            }
        }

        // Only calculate streak if there's recent activity
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
                    break; // Streak breaks if there's a gap
                }
            }
        }
        $streakDays = $currentStreak;
        $streakPoints = floor($streakDays / 3) * 20;

        // Total points
        $totalPoints = $lessonPoints + $examPoints + $questionPoints + $achievementPoints + $streakPoints;

        // Update UserProgress
        if ($progress) {
            $progress->points = $totalPoints;
            $progress->streak_days = $streakDays;
            $progress->save();
        }

        // Determine level based on points
        $pointsMap = [
            'Beginner' => 0,
            'Intermediate' => 150,
            'Advanced' => 300,
            'Expert' => 500,
            'Legend' => 800,
        ];

        $currentLevel = 'Beginner';
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

        // Fetch completed domains and chapters
        $allDomains = Domain::count();
        $completedDomains = Domain::whereHas('slides', function ($query) use ($user) {
            $query->whereHas('slideAttempts', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNotNull('end_date');
            });
        })->get()->filter(function ($domain) use ($user) {
            $totalSlides = $domain->slides()->count();
            $completedSlides = $domain->slides()->whereHas('slideAttempts', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNotNull('end_date');
            })->count();
            return $totalSlides > 0 && $totalSlides === $completedSlides;
        })->count();

        $allChapters = Chapter::count();
        $completedChapters = Chapter::whereHas('slides', function ($query) use ($user) {
            $query->whereHas('slideAttempts', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNotNull('end_date');
            });
        })->get()->filter(function ($chapter) use ($user) {
            $totalSlides = $chapter->slides()->count();
            $completedSlides = $chapter->slides()->whereHas('slideAttempts', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNotNull('end_date');
            })->count();
            return $totalSlides > 0 && $totalSlides === $completedSlides;
        })->count();

        // Check if all content is completed
        $allContentCompleted = ($allDomains > 0 && $allChapters > 0 && $completedDomains === $allDomains && $completedChapters === $allChapters);
        
        $completedQuestions = QuizAttempt::where('user_id', $user->id)->sum('score') +
                             TestAttempt::where('user_id', $user->id)->sum('score');

        // Update UserProgress with domains and chapters
        if ($progress) {
            $progress->domains_completed = $completedDomains;
            $progress->domains_total = $allDomains;
            $progress->lessons_completed = $completedChapters;
            $progress->lessons_total = $allChapters;
            $progress->exams_completed = $completedExams;
            $progress->questions_completed = $completedQuestions;
            $progress->save();
        }

        // Pass data to the view
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