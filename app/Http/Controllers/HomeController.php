<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fake data
        $notifications = 3;
        $progress = 65;
        $daysLeft = 12;
        $todayMissions = [
            (object) ['title' => 'Review Chapter 1 Slides', 'type' => 'Slide', 'status' => 'completed'],
            (object) ['title' => 'Complete Domain 2 Quiz', 'type' => 'Exam', 'status' => 'pending'],
            (object) ['title' => 'Study PMP Framework', 'type' => 'Slide', 'status' => 'pending'],
        ];
        $slidesCompleted = 25;
        $examsCompleted = 4;

        return view('student.home', compact(
            'notifications',
            'progress',
            'daysLeft',
            'todayMissions',
            'slidesCompleted',
            'examsCompleted'
        ));
    }
}
