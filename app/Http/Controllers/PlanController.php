<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProgress;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function Plan()
    {
        if (!Auth::check()) {
            $progress = new \stdClass();
            $progress->plan_duration = 90;
            $progress->plan_end_date = null;
            $days_left = 0;
        } else {
            $user = Auth::user();
            $progress = $user->progress ?? $user->progress()->create([
                'points' => 0,
                'current_level' => 'مبتدئ',
                'points_to_next_level' => 100,
                'days_left' => 0,
                'plan_duration' => 90,
                'plan_end_date' => null,
                'progress' => 0,
                'domains_completed' => 0,
                'domains_total' => 0,
                'lessons_completed' => 0,
                'lessons_total' => 0,
                'exams_completed' => 0,
                'exams_total' => 0,
                'questions_completed' => 0,
                'questions_total' => 0,
                'lessons_milestone' => 0,
                'questions_milestone' => 0,
                'streak_days' => 0,
            ]);
            // حساب الأيام المتبقية
            $days_left = $progress->plan_end_date ? max(0, Carbon::parse($progress->plan_end_date)->diffInDays(now())) : 0;
            $progress->days_left = $days_left;
            $progress->save();
        }

        $weeklyLessons = 4;
        $weeklyQuestions = 30;
        if ($progress->plan_end_date && $progress->plan_duration == 0) {
            $startDate = now();
            $endDate = Carbon::parse($progress->plan_end_date);
            $days = $startDate->diffInDays($endDate);
            $weeks = max(1, ceil($days / 7));
            $weeklyLessons = ceil(40 / $weeks);
            $weeklyQuestions = ceil(300 / $weeks);
        } elseif ($progress->plan_duration) {
            $weeks = ceil($progress->plan_duration / 30);
            $weeklyLessons = ceil(40 / $weeks);
            $weeklyQuestions = ceil(300 / $weeks);
        }

        return view('student.plan', compact('progress', 'weeklyLessons', 'weeklyQuestions', 'days_left'));
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'هذه الميزة متاحة فقط بعد تسجيل الدخول');
        }

        $user = Auth::user();
        $progress = $user->progress;

        if ($request->has('plan_duration') && in_array($request->plan_duration, [30, 60, 90])) {
            $progress->update(['plan_duration' => $request->plan_duration, 'plan_end_date' => null]);
            $endDate = now()->addDays($request->plan_duration);
            $progress->plan_end_date = $endDate;
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $start = Carbon::createFromFormat('Y-m-d', $request->start_date);
            $end = Carbon::createFromFormat('Y-m-d', $request->end_date);
            if ($start->lte($end)) {
                $progress->update(['plan_duration' => 0, 'plan_end_date' => $end]);
            }
        }

        // حساب الأيام المتبقية بعد التحديث
        $days_left = $progress->plan_end_date ? max(0, Carbon::parse($progress->plan_end_date)->diffInDays(now())) : 0;
        $progress->days_left = $days_left;
        $progress->save();

        return redirect()->back()->with('success', 'تم تحديث الخطة بنجاح');
    }
}