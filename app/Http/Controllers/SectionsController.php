<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Plan;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\Certificate;
use App\Models\ExamAttempt;
use App\Models\SlideAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class SectionsController extends Controller
{
    /**
     * Show certificate selection page
     */
    public function showCertificates()
    {
        $certificates = Certificate::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('student.sections.certificates', compact('certificates'));
    }

    /**
     * Select a certificate and store in session
     */
    public function selectCertificate(Request $request)
    {
        $request->validate([
            'certificate_id' => 'required|exists:certificates,id'
        ]);

        $certificate = Certificate::findOrFail($request->certificate_id);
        
        // Store selected certificate in session
        Session::put('selected_certificate_id', $certificate->id);
        Session::put('selected_certificate', $certificate);

        return redirect()->route('student.sections.index')
            ->with('success', __('Certificate selected successfully'));
    }

    /**
     * Check if certificate is selected, redirect if not
     */
    private function ensureCertificateSelected()
    {
        if (!Session::has('selected_certificate_id')) {
            return redirect()->route('student.certificates.index')
                ->with('error', __('Please select a certificate first'));
        }
        return null;
    }

    /**
     * Get the selected certificate ID from session
     */
    private function getSelectedCertificateId()
    {
        return Session::get('selected_certificate_id');
    }

    public function index()
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();

        if ($user->first_visit) {
            $user->first_visit = false;
            $user->save();
        }

        // Initialize sets to track unique chapter and domain IDs
        $completedChapterIds = [];
        $achievedDomainIds = [];

        // Count total chapters and domains for selected certificate
        $totalChapters = Chapter::where('certificate_id', $certificateId)->count();
        $totalDomains = Domain::where('certificate_id', $certificateId)->count();

        // Get chapter and domain IDs for the selected certificate
        $chapterIds = Chapter::where('certificate_id', $certificateId)->pluck('id');
        $domainIds = Domain::where('certificate_id', $certificateId)->pluck('id');

        // Chunk slide_attempts to collect chapter and domain IDs for selected certificate
        SlideAttempt::where('user_id', $user->id)
            ->select('slide_id')
            ->join('slides', 'slide_attempts.slide_id', '=', 'slides.id')
            ->selectRaw('slides.chapter_id, slides.domain_id')
            ->where(function($query) use ($chapterIds, $domainIds) {
                $query->whereIn('slides.chapter_id', $chapterIds)
                      ->orWhereIn('slides.domain_id', $domainIds);
            })
            ->orderBy('slide_attempts.slide_id')
            ->orderBy('slide_attempts.user_id')
            ->chunk(1000, function ($attempts) use (&$completedChapterIds, &$achievedDomainIds) {
                foreach ($attempts as $attempt) {
                    // Collect unique chapter_id if present
                    if ($attempt->chapter_id) {
                        $completedChapterIds[$attempt->chapter_id] = true;
                    }
                    // Collect unique domain_id if present
                    if ($attempt->domain_id) {
                        $achievedDomainIds[$attempt->domain_id] = true;
                    }
                }
            });

        // Count completed chapters and achieved domains
        $completedChapters = count($completedChapterIds);
        $achievedDomains = count($achievedDomainIds);

        // Fetch exams data for selected certificate
        $totalExams = Exam::where('certificate_id', $certificateId)->count();
        $achievedExams = Exam::where('certificate_id', $certificateId)
            ->whereHas('attempts', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

        return view('student.sections.index', [
            'totalChapters' => $totalChapters,
            'completedChapters' => $completedChapters,
            'totalDomains' => $totalDomains,
            'achievedDomains' => $achievedDomains,
            'totalExams' => $totalExams,
            'achievedExams' => $achievedExams,
            'selectedCertificate' => Session::get('selected_certificate'),
        ]);
    }

    public function chapters()
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();
        
        $chapters = Chapter::where('certificate_id', $certificateId)->get();

        $chapterData = [];

        $slideCounts = Slide::whereHas('chapter', function($query) use ($certificateId) {
                $query->where('certificate_id', $certificateId);
            })
            ->select('chapter_id', DB::raw('count(*) as total_slides'))
            ->groupBy('chapter_id')
            ->pluck('total_slides', 'chapter_id');

        $completedSlides = SlideAttempt::where('user_id', $user->id)
            ->whereNotNull('end_date')
            ->select('slide_id')
            ->get()
            ->pluck('slide_id');

        $completedSlidesPerChapter = Slide::whereIn('id', $completedSlides)
            ->whereHas('chapter', function($query) use ($certificateId) {
                $query->where('certificate_id', $certificateId);
            })
            ->select('chapter_id', DB::raw('count(*) as completed_slides'))
            ->groupBy('chapter_id')
            ->pluck('completed_slides', 'chapter_id');

        foreach ($chapters as $chapter) {
            $totalSlides = $slideCounts[$chapter->id] ?? 0;
            $completedSlidesCount = $completedSlidesPerChapter[$chapter->id] ?? 0;

            $chapterData[] = [
                'id' => $chapter->id,
                'name' => $chapter->text,
                'total_slides' => $totalSlides,
                'completed_slides' => $completedSlidesCount,
            ];
        }

        return view('student.sections.chaptersList', [
            'chapters' => collect($chapterData),
            'selectedCertificate' => Session::get('selected_certificate'),
        ]);
    }

    public function domains()
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();
        
        $domains = Domain::where('certificate_id', $certificateId)->get();

        $domainData = [];

        $slideCounts = Slide::whereHas('domain', function($query) use ($certificateId) {
                $query->where('certificate_id', $certificateId);
            })
            ->select('domain_id', DB::raw('count(*) as total_slides'))
            ->groupBy('domain_id')
            ->pluck('total_slides', 'domain_id');

        $completedSlides = SlideAttempt::where('user_id', $user->id)
            ->whereNotNull('end_date')
            ->select('slide_id')
            ->get()
            ->pluck('slide_id');

        $completedSlidesPerDomain = Slide::whereIn('id', $completedSlides)
            ->whereHas('domain', function($query) use ($certificateId) {
                $query->where('certificate_id', $certificateId);
            })
            ->select('domain_id', DB::raw('count(*) as completed_slides'))
            ->groupBy('domain_id')
            ->pluck('completed_slides', 'domain_id');

        foreach ($domains as $domain) {
            $totalSlides = $slideCounts[$domain->id] ?? 0;
            $completedSlidesCount = $completedSlidesPerDomain[$domain->id] ?? 0;

            $domainData[] = [
                'id' => $domain->id,
                'name' => $domain->text,
                'description' => app()->getLocale() === 'ar' ? $domain->description_ar : $domain->description,
                'total_slides' => $totalSlides,
                'completed_slides' => $completedSlidesCount,
            ];
        }
        
        return view('student.sections.domainList', [
            'domains' => collect($domainData),
            'selectedCertificate' => Session::get('selected_certificate'),
        ]);
    }

    public function chapterShow(Request $request)
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $chapterId = $request->chapterId;
        $certificateId = $this->getSelectedCertificateId();

        // Fetch the chapter and verify it belongs to selected certificate
        $chapter = Chapter::where('id', $chapterId)
            ->where('certificate_id', $certificateId)
            ->firstOrFail();

        // Get slides for the chapter
        $slides = Slide::where('chapter_id', $chapterId)->get();

        // Initialize slide data
        $slideData = [];
        $completedSlides = 0;
        $totalSlides = $slides->count();

        // Fetch slide attempts for the user
        $attempts = SlideAttempt::where('user_id', $user->id)
            ->whereIn('slide_id', $slides->pluck('id'))
            ->select('slide_id', 'end_date')
            ->get()
            ->keyBy('slide_id');

        // Process slides (keeping your existing logic)
        foreach ($slides as $index => $slide) {
            $attempt = $attempts->get($slide->id);
            $status = 'not-started';
            $progress = 0;
            $action = 'Start';
            $secondary_action = 'Preview';
            $secondary_icon = 'fa-info-circle';
            $locked = false;

            if ($attempt) {
                if ($attempt->end_date) {
                    $status = 'completed';
                    $progress = 100;
                    $action = 'Review';
                    $secondary_action = 'Download';
                    $secondary_icon = 'fa-download';
                    $completedSlides++;
                } else {
                    $status = 'in-progress';
                    $progress = 60;
                    $action = 'Continue';
                    $secondary_action = 'Save';
                    $secondary_icon = 'fa-bookmark';
                }
            } elseif ($index > $completedSlides) {
                $locked = true;
                $status = 'locked';
                $action = 'Locked';
                $secondary_action = 'Requirements';
                $secondary_icon = 'fa-question-circle';
            }

            $slideData[] = [
                'id' => $slide->id,
                'title' => 'Slide ' . ($index + 1),
                'description' => 'Description for slide ' . ($index + 1),
                'duration' => 20,
                'difficulty' => 'Medium',
                'icon' => 'fa-file-alt',
                'status' => $status,
                'progress' => $progress,
                'action' => $action,
                'secondary_action' => $secondary_action,
                'secondary_icon' => $secondary_icon,
                'locked' => $locked,
            ];
        }

        return view('student.sections.slides', [
            'title' => __('lang.chapter') . " ( " . $chapter->text . " )",
            'subtitle' => __('Learn the fundamentals and principles of successful project management'),
            'slides' => $slideData,
            'totalSlides' => $totalSlides,
            'isDomain' => false,
            'completedSlides' => $completedSlides,
            'selectedCertificate' => Session::get('selected_certificate'),
        ]);
    }

    public function domainShow(Request $request)
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $domainId = $request->domainId;
        $certificateId = $this->getSelectedCertificateId();

        // Fetch the domain and verify it belongs to selected certificate
        $domain = Domain::where('id', $domainId)
            ->where('certificate_id', $certificateId)
            ->firstOrFail();

        // Get slides for the domain
        $slides = Slide::where('domain_id', $domainId)->get();

        // Initialize slide data
        $slideData = [];
        $completedSlides = 0;
        $totalSlides = $slides->count();

        // Fetch slide attempts for the user
        $attempts = SlideAttempt::where('user_id', $user->id)
            ->whereIn('slide_id', $slides->pluck('id'))
            ->select('slide_id', 'end_date')
            ->get()
            ->keyBy('slide_id');

        foreach ($slides as $slide) {
            $attempt = $attempts->get($slide->id);

            $status = 'not_started';
            $progress = 0;
            $action = 'start';
            $secondary_action = 'preview';
            $secondary_icon = 'fas fa-eye';
            $locked = false;

            if ($attempt) {
                if ($attempt->end_date) {
                    $status = 'completed';
                    $progress = 100;
                    $action = 'review';
                    $secondary_action = 'retake';
                    $secondary_icon = 'fas fa-redo';
                    $completedSlides++;
                } else {
                    $status = 'in_progress';
                    $progress = 50;
                    $action = 'continue';
                    $secondary_action = 'reset';
                    $secondary_icon = 'fas fa-undo';
                }
            }

            $slideData[] = [
                'id' => $slide->id,
                'title' => 'Slide ' . $slide->id,
                'description' => 'Slide description',
                'duration' => '15 min',
                'difficulty' => 'Beginner',
                'icon' => 'fas fa-play-circle',
                'status' => $status,
                'progress' => $progress,
                'action' => $action,
                'secondary_action' => $secondary_action,
                'secondary_icon' => $secondary_icon,
                'locked' => $locked,
            ];
        }

        return view('student.sections.slides', [
            'title' => __('lang.domain') . " ( " . $domain->text . " )",
            'subtitle' => app()->getLocale() === 'ar' ? $domain->description_ar : $domain->description,
            'slides' => $slideData,
            'totalSlides' => $totalSlides,
            'isDomain' => true,
            'completedSlides' => $completedSlides,
            'selectedCertificate' => Session::get('selected_certificate'),
        ]);
    }

    // Keep the rest of your existing methods (slideShow, recordAttempt, etc.)
    // just make sure they also check for certificate selection where needed

    public function slideShow($slideId)
    {
        $user = auth()->user();
        $slide = Slide::findOrFail($slideId);
        $pdfUrl = Storage::url($slide->content);
        
        return view("student.sections.slideShow", [
            'slide' => [
                'id' => $slide->id,
                'title' => $slide->text,
                'chapter_id' => $slide->chapter_id,
                'domain_id' => $slide->domain_id,
            ],
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function recordAttempt(Request $request)
    {
        $user = auth()->user();
        $slideId = $request->slide_id;
        $action = $request->action;

        $attempt = SlideAttempt::where('user_id', $user->id)
            ->where('slide_id', $slideId)
            ->first();

        if (!$attempt) {
            $attempt = new SlideAttempt();
            $attempt->user_id = $user->id;
            $attempt->slide_id = $slideId;
        }

        switch ($action) {
            case 'start':
                $attempt->start_date = now();
                break;
            case 'complete':
                $attempt->end_date = now();
                break;
            case 'reset':
                $attempt->start_date = null;
                $attempt->end_date = null;
                break;
        }

        $attempt->save();

        return response()->json(['success' => true]);
    }

    /**
     * Check if user has a plan and redirect to plan selection if needed
     */
    public function checkPlanAndRedirect()
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();
        
        // Check if user has a plan for the selected certificate
        $hasPlan = Plan::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->exists();
        
        if (!$hasPlan) {
            return redirect()->route('student.plan.selection');
        }
        
        return redirect()->route('student.exams.index');
    }

    /**
     * Show plan selection page
     */
    public function showPlanSelection()
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();
        
        if (Plan::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->exists()) {
            return redirect()->route('student.exams.index');
        }
        
        return view('student.plan-selection', [
            'selectedCertificate' => Session::get('selected_certificate'),
        ]);
    }

    /**
     * Store the selected plan
     */
    public function storePlan(Request $request)
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();

        $existingPlan = Plan::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->first();

        if ($existingPlan) {
            return redirect()->route('student.exams.index')
                ->with('info', __('You already have a plan for this certificate'));
        }
    
        
        $request->validate([
            'plan_type' => 'required|in:6_weeks,10_weeks,custom',
            'start_date' => 'required_if:plan_type,custom|date|after_or_equal:today',
            'end_date' => 'required_if:plan_type,custom|date|after:start_date',
        ]);

        $startDate = now();
        $endDate = null;
        $planDuration = 0;

        switch ($request->plan_type) {
            case '10_weeks':
                $planDuration = 63;
                $endDate = $startDate->copy()->addWeeks(9);
                break;
            case '6_weeks':
                $planDuration = 49;
                $endDate = $startDate->copy()->addWeeks(7);
                break;
            case 'custom':
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $planDuration = $startDate->diffInDays($endDate);
                break;
        }

        if (Plan::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->exists()) {
            return redirect()->route('student.plan.selection')
                ->with('error', __('plan_selection.plan_already_exists'));
        }

        $plan = Plan::create([
            'user_id' => $user->id,
            'certificate_id' => $certificateId,
            'plan_type' => $request->plan_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

         $progress = $user->progress()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan->id,
                'certificate_id' => $certificateId,
                'plan_duration' => $planDuration,
                'plan_end_date' => $endDate,
                'start_date' => $startDate,
                'points' => 0,
                'current_level' => 'مبتدئ',
                'points_to_next_level' => 100,
                'days_left' => 0,
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
            ]
        );

        $progress->update([
            'plan_duration' => $planDuration,
            'plan_end_date' => $endDate,
            'start_date' => $startDate,
        ]);

        return redirect()->route('student.exams.index')
            ->with('success', __('plan_selection.plan_created_successfully'));
    }

    /**
     * Display available exams
     */
    public function examsIndex()
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();
        
        if (!Plan::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->exists()) {
            return redirect()->route('student.plan.selection');
        }
        
        $exams = Exam::where('certificate_id', $certificateId)
            ->with('examQuestions')
            ->get()
            ->map(function ($exam) {
                $exam->questions_count = $exam->examQuestions->count();
                return $exam;
            });
        
        return view('student.exams.index', compact('exams'));
    }

    /**
     * Show exam details
     */
    public function takeExam(Exam $exam)
    {
        // Check if certificate is selected
        $redirect = $this->ensureCertificateSelected();
        if ($redirect) return $redirect;

        $user = auth()->user();
        $certificateId = $this->getSelectedCertificateId();
        
        // Verify exam belongs to selected certificate
        if ($exam->certificate_id !== $certificateId) {
            abort(404);
        }
        
        if (!Plan::where('user_id', $user->id)
            ->where('certificate_id', $certificateId)
            ->exists()) {
            return redirect()->route('student.plan.selection');
        }
        
        $exam->load(['examQuestions' => function($query) {
            $query->with('answers');
        }]);
        
        $previousAttempts = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalQuestions = $exam->examQuestions->count();
        $totalDuration = $exam->time ?? ($totalQuestions * 2);
        $userAttempts = $previousAttempts->count();
        $bestScore = $previousAttempts->max('score') ?? 0;
        
        return view('student.exams.details', compact(
            'exam', 
            'totalQuestions', 
            'totalDuration', 
            'userAttempts', 
            'bestScore',
            'previousAttempts'
        ));
    }
}