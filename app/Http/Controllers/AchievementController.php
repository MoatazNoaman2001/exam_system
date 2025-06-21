<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
       public function Achievement()
    {

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

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
        return view('student.Achievement' , compact('user', 'progress'));
    }
}
