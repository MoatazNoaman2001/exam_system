<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class certificationController extends Controller
{
    
       public function certification()
    {

    $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }
        $progress = $user->progress ?? $user->progress()->create([
            'points' => 0,
            'current_level' => 'مبتدئ',
            'progress' => 0,
            'domains_completed' => 0,
            'domains_total' => 0,
            'lessons_completed' => 0,
            'lessons_total' => 0,
            'exams_completed' => 0,
            'exams_total' => 0,
            'questions_completed' => 0,
            'questions_total' => 0,
            'streak_days' => 0,
        ]);

        return view('student.certification', compact('user', 'progress'));
    }

    public function download()
    {
        $user = Auth::user();
        $progress = $user->progress;

        if ($progress->lessons_completed == $progress->lessons_total &&
            $progress->exams_completed == $progress->exams_total &&
            $progress->questions_completed == $progress->questions_total) {
            // Logic to generate PDF (e.g., using Laravel DomPDF)
            $certificatePath = public_path('images/canva-blue-and-gold-simple-certificate-zxaa-6-y-b-ua-u-10.png');
            return response()->download($certificatePath, 'certificate_' . $user->name . '.png');
        }

        return redirect()->back()->with('error', 'يجب إكمال جميع الإنجازات أولاً!');
    }

    public function view()
    {
        $user = Auth::user();
        $progress = $user->progress;
        if ($progress->lessons_completed == $progress->lessons_total &&
            $progress->exams_completed == $progress->exams_total &&
            $progress->questions_completed == $progress->questions_total) {
            return view('certificate', compact('user', 'progress'));
        }
        return redirect()->route('home')->with('error', 'يجب إكمال جميع الإنجازات أولاً!');
    }
}