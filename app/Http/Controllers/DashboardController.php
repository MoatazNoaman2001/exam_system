<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\IntroQuestion;
use App\Models\IntroSelection;
use App\Models\IntroAnswer;
use App\Models\Domain;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\Slide;
use App\Models\SlideAttempt;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\TestAttempt;
use App\Models\Mission;
use App\Models\Chapter;

class DashboardController extends Controller
{
    public function index(){
        $stats = [
            'total_users' => User::count(),
            'total_exams' => Exam::count(),
            'total_quizzes' => Quiz::count(),
            'total_tests' => Test::count(),
            'total_missions' => Mission::count(),
            'total_domains' => Domain::count(),
            'total_slides' => Slide::count(),
            'total_chapters' => Chapter::count(),
            'total_notifications' => Notification::count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_exams = Exam::latest()->take(5)->get();
        $recent_notifications = Notification::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_exams', 'recent_notifications'));
    }
}
