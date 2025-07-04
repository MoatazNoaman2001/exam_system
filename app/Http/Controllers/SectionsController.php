<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\SlideAttempt;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SectionsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Initialize sets to track unique chapter and domain IDs
        $completedChapterIds = [];
        $achievedDomainIds = [];

        // Count total chapters and domains (no chunking needed for small datasets)
        $totalChapters = Chapter::count();
        $totalDomains = Domain::count();

        // Chunk slide_attempts to collect chapter and domain IDs
        SlideAttempt::where('user_id', $user->id)
            ->select('slide_id')
            ->join('slides', 'slide_attempts.slide_id', '=', 'slides.id')
            ->selectRaw('slides.chapter_id, slides.domain_id')
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

        // Fetch exams data
        $totalExams = Exam::count();
        $achievedExams = Exam::whereHas('examAttempts', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        return view('student.sections.index', [
            'totalChapters' => $totalChapters,
            'completedChapters' => $completedChapters,
            'totalDomains' => $totalDomains,
            'achievedDomains' => $achievedDomains,
            'totalExams' => $totalExams,
            'achievedExams' => $achievedExams,
        ]);
    }

    public function chapters(){
        $user = auth()->user();
        $chapters = Chapter::all();

        $chapterData = [];

        $slideCounts = Slide::select('chapter_id', DB::raw('count(*) as total_slides'))
            ->groupBy('chapter_id')
            ->pluck('total_slides', 'chapter_id');

        $completedSlides = SlideAttempt::where('user_id', $user->id)
            ->whereNotNull('end_date')
            ->select('slide_id')
            ->get()
            ->pluck('slide_id');

        $completedSlidesPerChapter = Slide::whereIn('id', $completedSlides)
            ->select('chapter_id', DB::raw('count(*) as completed_slides'))
            ->groupBy('chapter_id')
            ->pluck('completed_slides', 'chapter_id');

        // Static chapter metadata (replace with actual data from chapters table if available)
        $chapterMetadata = [
            1 => [
                'name' => 'Chapter: Project Management Fundamentals',
                'description' => 'Learn the fundamentals and principles of successful project management',
            ],
            // Add more chapters as needed
            2 => [
                'name' => 'Chapter: Advanced Project Management',
                'description' => 'Explore advanced techniques for managing complex projects',
            ],
        ];

        foreach ($chapters as $chapter) {
            $totalSlides = $slideCounts[$chapter->id] ?? 0;
            $completedSlidesCount = $completedSlidesPerChapter[$chapter->id] ?? 0;

            $meta = $chapterMetadata[$chapter->id] ?? [
                'name' => 'Chapter ' . $chapter->text,
            ];

            $chapterData[] = [
                'id' => $chapter->id,
                'name' => $meta['name'],
                'total_slides' => $totalSlides,
                'completed_slides' => $completedSlidesCount,
            ];
        }

        return view('student.sections.chaptersList', [
            'chapters' => collect($chapterData),
        ]);
    }

    public function domains() {
        $user = auth()->user();
        $domains = Domain::all();

        $domainData = [];

        $slideCounts = Slide::select('domain_id', DB::raw('count(*) as total_slides'))
            ->groupBy('domain_id')
            ->pluck('total_slides', 'domain_id');

        $completedSlides = SlideAttempt::where('user_id', $user->id)
            ->whereNotNull('end_date')
            ->select('slide_id')
            ->get()
            ->pluck('slide_id');

        $completedSlidesPerDomain = Slide::whereIn('id', $completedSlides)
            ->select('domain_id', DB::raw('count(*) as completed_slides'))
            ->groupBy('domain_id')
            ->pluck('completed_slides', 'domain_id');

        // Static chapter metadata (replace with actual data from chapters table if available)
        $domainMetadata = [
            1 => [
                'name' => 'Domain: Project Management Fundamentals',
                'description' => 'Learn the fundamentals and principles of successful project management',
            ],
            // Add more chapters as needed
            2 => [
                'name' => 'Domain: Advanced Project Management',
                'description' => 'Explore advanced techniques for managing complex projects',
            ],
        ];

        foreach ($domains as $domain) {
            $totalSlides = $slideCounts[$domain->id] ?? 0;
            $completedSlidesCount = $completedSlidesPerDomain[$domain->id] ?? 0;

            $meta = $domainMetadata[$domain->id] ?? [
                'name' => 'Domain ' . $domain->text,
            ];

            $domainData[] = [
                'id' => $domain->id,
                'name' => $meta['name'],
                'description' => $domain->description ?? 'No description available',
                'total_slides' => $totalSlides,
                'completed_slides' => $completedSlidesCount,
            ];
        }
        return view('student.sections.domainList', [
            'domains' => collect($domainData),
        ]);
    } 
    public function chapterShow(Request $request){
        $user = auth()->user();
        $chapterId = $request->chapterId;

        // Fetch the chapter
        $chapter = Chapter::findOrFail($chapterId);

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

        // Static slide metadata (replace with actual data from slides table if available)
        $slideMetadata = [
            1 => [
                'title' => 'Introduction to Project Management',
                'description' => 'Comprehensive introduction to the concept and importance of project management in modern work',
                'duration' => 15,
                'difficulty' => 'Easy',
                'icon' => 'fa-play-circle',
            ],
            2 => [
                'title' => 'Project Lifecycle',
                'description' => 'Understanding the different phases of a project lifecycle from start to finish',
                'duration' => 25,
                'difficulty' => 'Medium',
                'icon' => 'fa-presentation',
            ],
            3 => [
                'title' => 'Team Management',
                'description' => 'How to build and lead an effective and cohesive project team',
                'duration' => 30,
                'difficulty' => 'Medium',
                'icon' => 'fa-users',
            ],
            4 => [
                'title' => 'Risk Management',
                'description' => 'Identifying, assessing, and managing risks in projects',
                'duration' => 35,
                'difficulty' => 'Hard',
                'icon' => 'fa-lock',
            ],
            5 => [
                'title' => 'Planning and Scheduling',
                'description' => 'Using planning tools to create effective schedules',
                'duration' => 40,
                'difficulty' => 'Medium',
                'icon' => 'fa-chart-gantt',
            ],
            6 => [
                'title' => 'Budget Management',
                'description' => 'Planning, monitoring, and controlling the project budget',
                'duration' => 28,
                'difficulty' => 'Medium',
                'icon' => 'fa-dollar-sign',
            ],
        ];
        // Process slides
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
                    $progress = 60; // Placeholder: calculate actual progress if available
                    $action = 'Continue';
                    $secondary_action = 'Save';
                    $secondary_icon = 'fa-bookmark';
                }
            } elseif ($index > $completedSlides) {
                // Lock slides that come after the last completed slide (sequential access)
                $locked = true;
                $status = 'locked';
                $action = 'Locked';
                $secondary_action = 'Requirements';
                $secondary_icon = 'fa-question-circle';
            }

            $meta = $slideMetadata[$slide->id] ?? [
                'title' => 'Slide ' . ($index + 1),
                'description' => 'Description for slide ' . ($index + 1),
                'duration' => 20,
                'difficulty' => 'Medium',
                'icon' => 'fa-file-alt',
            ];

            $slideData[] = [
                'id' => $slide->id,
                'title' => $meta['title'],
                'description' => $meta['description'],
                'duration' => $meta['duration'],
                'difficulty' => $meta['difficulty'],
                'icon' => $meta['icon'],
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
        ]);
    } 


    public function domainShow(Request $request){
        $user = auth()->user();
        $domainId = $request->domainId;
    
        // Fetch the domain
        $domain = Domain::findOrFail($domainId);

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

        // Static slide metadata (replace with actual data from slides table if available)
        $slideMetadata = [
            1 => [
                'title' => 'Introduction to Project Management',
                'description' => 'Learn the fundamentals of project management',
                'duration' => '15 min',
                'difficulty' => 'Beginner',
                'icon' => 'fas fa-play-circle',
            ],
            2 => [
                'title' => 'Project Lifecycle',
                'description' => 'Understanding project phases and lifecycle',
                'duration' => '20 min',
                'difficulty' => 'Intermediate',
                'icon' => 'fas fa-chart-line',
            ],
            3 => [
                'title' => 'Stakeholder Management',
                'description' => 'Managing project stakeholders effectively',
                'duration' => '18 min',
                'difficulty' => 'Advanced',
                'icon' => 'fas fa-users',
            ],
        ];

        foreach ($slides as $slide) {
            $attempt = $attempts->get($slide->id);
            $meta = $slideMetadata[$slide->id] ?? [
                'title' => 'Slide ' . $slide->id,
                'description' => 'Slide description',
                'duration' => '15 min',
                'difficulty' => 'Beginner',
                'icon' => 'fas fa-play-circle',
            ];

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
                'title' => $meta['title'],
                'description' => $meta['description'],
                'duration' => $meta['duration'],
                'difficulty' => $meta['difficulty'],
                'icon' => $meta['icon'],
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
            'subtitle' => $domain->description ?? __('Learn the fundamentals and principles of successful project management'),
            'slides' => $slideData,
            'totalSlides' => $totalSlides,
            'isDomain' => true,
            'completedSlides' => $completedSlides,
        ]);
    }

    public function slideShow($slideId){
        $user = auth()->user();
        $slide = Slide::findOrFail($slideId);   
        // dd($slide);
        $pdfUrl = Storage::url($slide->content);
        return view("student.sections.slideShow", [
            'slide' => [
                'id' => $slide->id,
                'title' => $slide->text,
                'chapter_id' => $slide->chapter_id,
            ],
            'pdf_url' => $pdfUrl,
        ]);  
    }

    public function recordAttempt(Request $request)
    {
        $user = auth()->user();
        $slideId = $request->slideId;
        $action = $request->action; // start, complete, reset

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
        $user = auth()->user();
        
        // Check if user has a plan
        $hasPlan = $user->progress && $user->progress->plan_duration;
        
        if (!$hasPlan) {
            return redirect()->route('student.plan.selection');
        }
        
        // If user has a plan, redirect to exams list
        return redirect()->route('student.exams.index');
    }

    /**
     * Show plan selection page
     */
    public function showPlanSelection()
    {
        $user = auth()->user();
        
        // Check if user already has a plan
        if ($user->progress && $user->progress->plan_duration) {
            return redirect()->route('student.exams.index');
        }
        
        return view('student.plan-selection');
    }

    /**
     * Store the selected plan
     */
    public function storePlan(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'plan_type' => 'required|in:experienced,beginner,custom',
            'start_date' => 'required_if:plan_type,custom|date|after_or_equal:today',
            'end_date' => 'required_if:plan_type,custom|date|after:start_date',
        ]);

        // Get or create user progress
        $progress = $user->progress ?? $user->progress()->create([
            'points' => 0,
            'current_level' => 'مبتدئ',
            'points_to_next_level' => 100,
            'days_left' => 0,
            'plan_duration' => 0,
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

        $startDate = now();
        $endDate = null;
        $planDuration = 0;

        switch ($request->plan_type) {
            case 'experienced':
                // 6-8 weeks for experienced learners
                $planDuration = 49; // 7 weeks
                $endDate = $startDate->copy()->addWeeks(7);
                break;
                
            case 'beginner':
                // 8-10 weeks for beginners
                $planDuration = 63; // 9 weeks
                $endDate = $startDate->copy()->addWeeks(9);
                break;
                
            case 'custom':
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $planDuration = $startDate->diffInDays($endDate);
                break;
        }

        // Update user progress with plan details
        $progress->update([
            'plan_duration' => $planDuration,
            'plan_end_date' => $endDate,
            'start_date' => $startDate,
        ]);

        // Create a plan record
        $plan = Plan::create([
            'user_id' => $user->id,
            'plan_type' => $request->plan_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return redirect()->route('student.exams.index')
            ->with('success', __('plan_selection.plan_created_successfully'));
    }

    /**
     * Display available exams
     */
    public function examsIndex()
    {
        $user = auth()->user();
        
        // Check if user has a plan
        if (!$user->progress || !$user->progress->plan_duration) {
            return redirect()->route('student.plan.selection');
        }
        
        $exams = Exam::with('examQuestions')->get()->map(function ($exam) {
            $exam->questions_count = $exam->examQuestions->count();
            return $exam;
        });
        
        return view('student.exams.index', compact('exams'));
    }

    /**
     * Take an exam
     */
    public function takeExam(Exam $exam)
    {
        $user = auth()->user();
        
        // Check if user has a plan
        if (!$user->progress || !$user->progress->plan_duration) {
            return redirect()->route('student.plan.selection');
        }
        
        // For now, just redirect back with a message
        return redirect()->route('student.exams.index')
            ->with('info', __('lang.exam_feature_coming_soon'));
    }
}
