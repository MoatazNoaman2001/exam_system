<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $certificates = Certificate::active()->ordered()->get();
        
        // Get user progress for each certificate
        $certificateProgress = [];
        
        foreach ($certificates as $certificate) {
            $progress = UserProgress::where('user_id', $user->id)
                ->where('certificate_id', $certificate->id)
                ->first();
                
            $certificateProgress[$certificate->id] = $progress ? $progress->progress_percentage : 0;
        }

        $notifications = 3; // This should come from actual notification count

        return view('student.certificate-home', compact(
            'certificates',
            'certificateProgress',
            'notifications'
        ));
    }

    public function show($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        $user = Auth::user();
        
        // Get progress for this specific certificate
        $progress = UserProgress::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->first();
            
        $progressPercentage = $progress ? $progress->progress_percentage : 0;
        
        // Get counts for this certificate
        $totalChapters = $certificate->chapters()->count();
        $totalDomains = $certificate->domains()->count();
        $totalExams = $certificate->exams()->count();
        
        // Get completed counts
        $completedChapters = $certificate->chapters()
            ->whereHas('slides.slideAttempts', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('end_date');
            })->count();
            
        $completedDomains = $certificate->domains()
            ->whereHas('slides.slideAttempts', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('end_date');
            })->count();
            
        $completedExams = $certificate->exams()
            ->whereHas('attempts', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

        return view('student.sections.index', compact(
            'certificate',
            'progressPercentage',
            'totalChapters',
            'completedChapters',
            'totalDomains',
            'completedDomains',
            'totalExams',
            'completedExams'
        ));
    }
} 