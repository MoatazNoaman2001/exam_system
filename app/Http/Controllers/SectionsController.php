<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\SlideAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'title' => __('Chapter: Project Management Fundamentals'),
            'subtitle' => __('Learn the fundamentals and principles of successful project management'),
            'slides' => $slideData,
            'totalSlides' => $totalSlides,
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
            'title' => __('Domain: Project Management Fundamentals'),
            'subtitle' => __('Learn the fundamentals and principles of successful project management'),
            'slides' => $slideData,
            'totalSlides' => $totalSlides,
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
}
