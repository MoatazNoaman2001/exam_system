<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Plan;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\User;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\TestAnswer;
use App\Models\Application;
use App\Models\ExamSession;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use App\Imports\ExamsImport;
use App\Models\Notification;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateExamRequest;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add admin role check middleware if you have role-based access
        // $this->middleware('role:admin');
    }

    /**
     * Display the admin dashboard
     */

    public function dashboard()
    {
        // Users Statistics
        $totalUsers = User::count();
        $activeUsers = User::whereHas('examSessions', function($query) {
            $query->where('last_activity_at', '>=', Carbon::now()->subDays(7));
        })->count();
        
        // PostgreSQL compatible month filter
        $newUsersThisMonth = User::whereRaw('EXTRACT(MONTH FROM created_at) = ?', [Carbon::now()->month])
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [Carbon::now()->year])
            ->count();
            
        $verifiedUsers = User::where('verified', true)->count();

        // Content Statistics
        $totalExams = Exam::count();
        $totalSlides = Slide::count();
        $totalChapters = Chapter::count();
        $totalDomains = Domain::count();

        // Learning Progress
        $totalPlans = Plan::count();
        $activePlans = Plan::where('end_date', '>=', Carbon::now()->toDateString())->count();
        $totalQuizzes = Quiz::count();
        $totalTests = Test::count();

        // Exam Performance
        $examSessions = ExamSession::where('status', 'completed')
            ->select(DB::raw('AVG(score) as avg_score'), DB::raw('COUNT(*) as total_attempts'))
            ->first();

        $avgExamScore = $examSessions->avg_score ?? 0;
        $totalExamAttempts = $examSessions->total_attempts ?? 0;

        // Recent Activity
        $recentExamSessions = ExamSession::with(['user', 'exam'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();

        // User Progress Summary
        $userProgressStats = UserProgress::select(
            DB::raw('AVG(progress) as avg_progress'),
            DB::raw('AVG(points) as avg_points'),
            DB::raw('AVG(streak_days) as avg_streak')
        )->first();

        // Monthly Registration Chart Data (PostgreSQL compatible)
        $monthlyRegistrations = User::select(
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [Carbon::now()->year])
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRegistrations[$i] ?? 0;
        }

        // Exam Performance by Domain (PostgreSQL compatible)
        $domainPerformance = Domain::with(['slides.tests.testAttempts'])
            ->get()
            ->map(function($domain) {
                $totalAttempts = $domain->slides->flatMap(function($slide) {
                    return $slide->tests->flatMap->testAttempts;
                })->count();
                
                $avgScore = $domain->slides->flatMap(function($slide) {
                    return $slide->tests->flatMap->testAttempts;
                })->avg('score') ?? 0;
                
                return [
                    'name' => $domain->text,
                    'attempts' => $totalAttempts,
                    'avg_score' => round($avgScore, 2)
                ];
            });

        // User Activity Levels
        $userActivityLevels = [
            'high' => User::whereHas('examSessions', function($query) {
                $query->where('last_activity_at', '>=', Carbon::now()->subDays(3));
            })->count(),
            'medium' => User::whereHas('examSessions', function($query) {
                $query->whereBetween('last_activity_at', [
                    Carbon::now()->subDays(7), 
                    Carbon::now()->subDays(3)
                ]);
            })->count(),
            'low' => User::whereHas('examSessions', function($query) {
                $query->whereBetween('last_activity_at', [
                    Carbon::now()->subDays(30), 
                    Carbon::now()->subDays(7)
                ]);
            })->count(),
        ];

        // Additional metrics for better dashboard insights
        $todayRegistrations = User::whereDate('created_at', Carbon::today())->count();
        
        // Weekly exam completion rate
        $weeklyExamSessions = ExamSession::where('completed_at', '>=', Carbon::now()->subWeek())->count();
        $weeklyExamCompletions = ExamSession::where('completed_at', '>=', Carbon::now()->subWeek())
            ->where('status', 'completed')
            ->count();
        $weeklyCompletionRate = $weeklyExamSessions > 0 ? round(($weeklyExamCompletions / $weeklyExamSessions) * 100, 1) : 0;

        // Top performing users this month
        $topUsers = User::select('users.*', DB::raw('AVG(exam_sessions.score) as avg_score'))
            ->join('exam_sessions', 'users.id', '=', 'exam_sessions.user_id')
            ->where('exam_sessions.status', 'completed')
            ->whereRaw('EXTRACT(MONTH FROM exam_sessions.completed_at) = ?', [Carbon::now()->month])
            ->whereRaw('EXTRACT(YEAR FROM exam_sessions.completed_at) = ?', [Carbon::now()->year])
            ->groupBy('users.id')
            ->orderBy('avg_score', 'desc')
            ->limit(5)
            ->get();

        return view('admin.admindashboard', compact(
            'totalUsers',
            'activeUsers',
            'newUsersThisMonth',
            'verifiedUsers',
            'totalExams',
            'totalSlides',
            'totalChapters',
            'totalDomains',
            'totalPlans',
            'activePlans',
            'totalQuizzes',
            'totalTests',
            'avgExamScore',
            'totalExamAttempts',
            'recentExamSessions',
            'recentUsers',
            'userProgressStats',
            'chartData',
            'domainPerformance',
            'userActivityLevels',
            'todayRegistrations',
            'weeklyCompletionRate',
            'topUsers'
        ));
    }

    /**
     * Get dashboard statistics for API endpoints
     */
    public function getStats()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
                'active' => User::whereHas('examSessions', function($query) {
                    $query->where('last_activity_at', '>=', Carbon::now()->subDays(7));
                })->count(),
                'verified' => User::where('verified', true)->count(),
            ],
            'content' => [
                'exams' => Exam::count(),
                'slides' => Slide::count(),
                'chapters' => Chapter::count(),
                'domains' => Domain::count(),
            ],
            'performance' => [
                'avg_score' => ExamSession::where('status', 'completed')->avg('score') ?? 0,
                'total_attempts' => ExamSession::where('status', 'completed')->count(),
            ]
        ]);
    }

    /**
     * Get chart data for specific time period
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'month'); // month, week, year

        switch ($period) {
            case 'week':
                $data = User::select(
                    DB::raw('EXTRACT(DOW FROM created_at) as day'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', Carbon::now()->subWeek())
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->pluck('count', 'day')
                ->toArray();
                
                // Fill missing days with 0 (0 = Sunday, 6 = Saturday)
                $chartData = [];
                for ($i = 0; $i <= 6; $i++) {
                    $chartData[] = $data[$i] ?? 0;
                }
                break;

            case 'year':
                $data = User::select(
                    DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [Carbon::now()->year])
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();
                
                $chartData = [];
                for ($i = 1; $i <= 12; $i++) {
                    $chartData[] = $data[$i] ?? 0;
                }
                break;

            default: // month
                $data = User::select(
                    DB::raw('EXTRACT(DAY FROM created_at) as day'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereRaw('EXTRACT(MONTH FROM created_at) = ?', [Carbon::now()->month])
                ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [Carbon::now()->year])
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->pluck('count', 'day')
                ->toArray();
                
                $chartData = [];
                $daysInMonth = Carbon::now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $chartData[] = $data[$i] ?? 0;
                }
                break;
        }

        return response()->json([
            'chartData' => $chartData,
            'period' => $period
        ]);
    }

    /**
     * Users Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Handle verified filter only if explicitly set to '1' or '0'
        if ($request->has('verified') && in_array($request->input('verified'), ['0', '1'])) {
            $query->where('verified', $request->input('verified') === '1');
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'role' => 'required|in:admin,student',
            'phone' => 'nullable|string|max:20|regex:/^[+\-\d\s]+$/',
            'preferred_language' => 'nullable|string|in:en,fr,es,de,it',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'username.regex' => 'Username may only contain letters, numbers, and underscores.',
        ]);
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('profile-images', 'public');
            }
    
            $user = User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'role' => $validatedData['role'],
                'phone' => $validatedData['phone'] ?? null,
                'preferred_language' => $validatedData['preferred_language'] ?? 'en',
                'email_verified_at' => $request->verified ? now() : null,
                'profile_image' => $imagePath,
                'is_agree' => true,
            ]);
    
            if (!$request->verified) {
                $user->sendEmailVerificationNotification();
            }
    
            return redirect()->route('admin.users')
                   ->with('success', 'User created successfully.');
    
        } catch (\Exception $e) {
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
    
            return back()->withInput()
                   ->with('error', 'Error creating user: '.$e->getMessage());
        }
    }

    public function showUser(User $user)
    {
        $user->load(['quizAttempts', 'testAttempts', 'missions', 'notifications']);
        return view('admin.users.show', compact('user'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id,
                'regex:/^[a-zA-Z0-9_]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $user->id
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'role' => 'required|in:admin,student',
            'phone' => 'nullable|string|max:20|regex:/^[+\-\d\s]+$/',
            'preferred_language' => 'nullable|string|in:en,fr,es,ar',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_verified' => 'boolean',
            'is_active' => 'boolean'
        ], [
            'password.regex' => 'Password must contain at least one uppercase, one lowercase, one number and one special character.',
            'username.regex' => 'Username may only contain letters, numbers and underscores.',
            'phone.regex' => 'Please enter a valid phone number.'
        ]);

        // dd($request->verified == "on");
        try {
            if ($request->hasFile('image')) {
                if ($user->profile_image) {
                    Storage::delete('public/'.$user->profile_image);
                }
                $validatedData['profile_image'] = $request->file('image')->store('profile-images', 'public');
            }
    
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = $validatedData['password'];
            } else {
                unset($validatedData['password']);
            }
    
            $validatedData['email_verified_at'] = $request->verified == "on" ? now() : null;
            $validatedData['verified'] = $request->verified == "on";
            unset($validatedData['email_verified']);

            \Illuminate\Database\Eloquent\Model::unguard();
            $user->update($validatedData);
            \Illuminate\Database\Eloquent\Model::reguard();
    
            return redirect()->route('admin.users')
                   ->with('success', 'User updated successfully.');
    
        } catch (\Exception $e) {
            if (isset($validatedData['profile_image'])) {
                Storage::delete('public/'.$validatedData['profile_image']);
            }
    
            return back()->withInput()
                   ->with('error', 'Error updating user: '.$e->getMessage());
        }
    }
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Domains Management
     */
    public function domains()
    {
        $domains = Domain::withCount('slides')->latest()->paginate(20);
        return view('admin.domains.index', compact('domains'));
    }

    public function createDomain()
    {
        return view('admin.domains.create');
    }

    public function storeDomain(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'required|string|max:200',
        ]);

        Domain::create($request->only(['text', 'description']));

        return redirect()->route('admin.domains')->with('success', 'Domain created successfully.');
    }

    public function editDomain(Domain $domain)
    {
        return view('admin.domains.edit', compact('domain'));
    }

    public function updateDomain(Request $request, Domain $domain)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'required|string|max:200',
        ]);

        $domain->update($request->only(['text', 'description']));

        return redirect()->route('admin.domains')->with('success', 'Domain updated successfully.');
    }

    public function destroyDomain(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('admin.domains')->with('success', 'Domain deleted successfully.');
    }

     /**
     * Chapters Management
     */
    public function chapters()
    {
        $chapters = Chapter::withCount('slides')->latest()->paginate(20);
        return view('admin.chapter.index', compact('chapters'));
    }

    public function createChapter()
    {
        return view('admin.chapter.create');
    }

    public function storeChapter(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        Chapter::create($request->only(['text']));

        return redirect()->route('admin.chapters')->with('success', 'Chapter created successfully.');
    }

    public function editChapter(Chapter $chapter)
    {
        return view('admin.chapter.edit', compact('chapter'));
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $chapter->update($request->only(['text']));

        return redirect()->route('admin.chapters')->with('success', 'Chapter updated successfully.');
    }

    public function destroyChapter(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->route('admin.chapters')->with('success', 'Chapter deleted successfully.');
    }

   /**
     * Slides Management
     */
    public function slides()
    {
        try {
            $slides = Slide::with(['domain', 'chapter'])
                ->withCount('tests')
                ->latest()
                ->paginate(20);
            
            return view('admin.slides.index', compact('slides'));
        } catch (\Exception $e) {
            Log::error('Error loading slides: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading slides.');
        }
    }

    public function createSlide()
    {
        try {
            $domains = Domain::orderBy('text')->get();
            $chapters = Chapter::orderBy('text')->get();
            
            return view('admin.slides.create', compact('domains', 'chapters'));
        } catch (\Exception $e) {
            Log::error('Error loading create slide form: ' . $e->getMessage());
            return redirect()->route('admin.slides')
                ->with('error', 'Error loading create form.');
        }
    }

    public function storeSlide(Request $request)
    {
        // Validate basic slide data and questions
        $validatedData = $this->validateSlideData($request);


        // dd($validatedData);
        try {
            DB::beginTransaction();

            // Store the PDF file
            $filePath = $request->file('content')->store('slides', 'public');

            // Create the slide
            $slide = Slide::create([
                'text' => $validatedData['text'],
                'content' => $filePath,
                'domain_id' => empty($validatedData['domain_id']) ? null : $validatedData['domain_id'],
                'chapter_id' => empty($validatedData['chapter_id']) ? null : $validatedData['chapter_id'],
            ]);

            // Create questions ONLY if domain is selected and questions are provided
            $questionsCreated = 0;
            if ($slide->domain_id && isset($validatedData['questions']) && is_array($validatedData['questions'])) {
                $questionsCreated = $this->createQuestionsForSlide($slide, $validatedData['questions']);
            }

            DB::commit();

            $message = $questionsCreated > 0 
                ? "Slide created successfully with {$questionsCreated} questions."
                : "Slide created successfully.";

            return redirect()
                ->route('admin.slides')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file on error
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            Log::error('Error creating slide: ' . $e->getMessage(), [
                'request_data' => $request->except(['content']),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error creating slide: ' . $e->getMessage());
        }
    }

    public function editSlide(Slide $slide)
    {
        try {
            $domains = Domain::orderBy('text')->get();
            $chapters = Chapter::orderBy('text')->get();
            
            // Load existing questions for editing
            $slide->load(['tests.answers']);
            
            return view('admin.slides.edit', compact('slide', 'domains', 'chapters'));
        } catch (\Exception $e) {
            Log::error('Error loading edit slide form: ' . $e->getMessage());
            return redirect()->route('admin.slides')
                ->with('error', 'Error loading edit form.');
        }
    }

    public function updateSlide(Request $request, Slide $slide)
    {
        // Validate the updated data
        $validatedData = $this->validateSlideData($request, false);

        try {
            DB::beginTransaction();

            $updateData = [
                'text' => $validatedData['text'],
                'domain_id' => $validatedData['domain_id'] ?? null,
                'chapter_id' => $validatedData['chapter_id'] ?? null,
            ];

            // Handle PDF file update if new file provided
            if ($request->hasFile('content')) {
                // Delete old file
                if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                    Storage::disk('public')->delete($slide->content);
                }
                
                $updateData['content'] = $request->file('content')->store('slides', 'public');
            }

            // Update the slide
            $slide->update($updateData);

            // Handle questions update - ONLY for slides with domains
            $questionsCreated = 0;
            if ($slide->domain_id) {
                // If domain is selected, handle questions
                if (isset($validatedData['questions']) && is_array($validatedData['questions'])) {
                    // Delete existing tests and answers
                    $slide->tests()->delete();
                    
                    // Create new questions
                    $questionsCreated = $this->createQuestionsForSlide($slide, $validatedData['questions']);
                }
            } else {
                // If no domain selected, remove all questions
                $slide->tests()->delete();
            }

            DB::commit();

            $message = $questionsCreated > 0 
                ? "Slide updated successfully with {$questionsCreated} questions."
                : "Slide updated successfully.";

            return redirect()->route('admin.slides')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating slide: ' . $e->getMessage(), [
                'slide_id' => $slide->id,
                'request_data' => $request->except(['content'])
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error updating slide: ' . $e->getMessage());
        }
    }

    public function destroySlide(Slide $slide)
    {
        try {
            DB::beginTransaction();

            // Delete the PDF file
            if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                Storage::disk('public')->delete($slide->content);
            }

            // Delete the slide (will cascade delete tests and answers)
            $slide->delete();
            
            DB::commit();

            return redirect()->route('admin.slides')
                ->with('success', 'Slide deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting slide: ' . $e->getMessage(), [
                'slide_id' => $slide->id
            ]);

            return redirect()->route('admin.slides')
                ->with('error', 'Error deleting slide: ' . $e->getMessage());
        }
    }

    /**
     * Show slide details with questions
     */
    public function showSlide(Slide $slide)
    {
        try {
            $slide->load(['domain', 'chapter', 'tests.answers']);
            return view('admin.slides.show', compact('slide'));
        } catch (\Exception $e) {
            Log::error('Error showing slide: ' . $e->getMessage());
            return redirect()->route('admin.slides')
                ->with('error', 'Error loading slide details.');
        }
    }

    /**
     * Download slide PDF
     */
    public function downloadSlidePdf(Slide $slide)
    {
        try {
            if (!$slide->content || !Storage::disk('public')->exists($slide->content)) {
                return redirect()->back()->with('error', 'PDF file not found.');
            }

            $fileName = $slide->text . '.pdf';
            return Storage::disk('public')->download($slide->content, $fileName);
            
        } catch (\Exception $e) {
            Log::error('Error downloading PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error downloading PDF file.');
        }
    }

    /**
     * Get questions for AJAX requests
     */
    public function getSlideQuestions(Slide $slide)
    {
        try {
            $questions = $slide->tests()
                ->with('answers')
                ->get()
                ->map(function ($test) {
                    return [
                        'id' => $test->id,
                        'question_ar' => $test->question_ar,
                        'question_en' => $test->question_en,
                        'answers' => $test->answers->map(function ($answer) {
                            return [
                                'id' => $answer->id,
                                'text_ar' => $answer->text_ar,
                                'text_en' => $answer->text_en,
                                'is_correct' => $answer->is_correct,
                            ];
                        }),
                    ];
                });

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting questions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading questions.'
            ], 500);
        }
    }

    /**
     * Private helper methods
     */
    
    /**
     * Validate slide data including questions
     */
    private function validateSlideData(Request $request, $isCreate = true)
    {
        $rules = [
            'text' => 'required|string|max:255',
            'domain_id' => 'nullable|exists:domains,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            
            // Question validation rules - only when questions are provided
            'questions' => 'nullable|array',
            'questions.*.question_ar' => 'required_with:questions|string|max:1000',
            'questions.*.question_en' => 'required_with:questions|string|max:1000',
            'questions.*.answers' => 'required_with:questions|array|min:2|max:6',
            'questions.*.answers.*.text_ar' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.answers.*.text_en' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.correct_answer' => 'required_with:questions|integer|min:0',
        ];

        // File validation based on create/update
        if ($isCreate) {
            $rules['content'] = 'required|file|mimes:pdf|max:5120';
        } else {
            $rules['content'] = 'nullable|file|mimes:pdf|max:5120';
        }

        $messages = [
            'text.required' => 'Slide title is required.',
            'content.required' => 'PDF file is required.',
            'content.mimes' => 'File must be a PDF.',
            'content.max' => 'File size must not exceed 5MB.',
            
            // Question validation messages
            'questions.*.question_ar.required_with' => 'Question in Arabic is required.',
            'questions.*.question_en.required_with' => 'Question in English is required.',
            'questions.*.answers.min' => 'Each question must have at least 2 answers.',
            'questions.*.answers.max' => 'Each question cannot have more than 6 answers.',
            'questions.*.answers.*.text_ar.required_with' => 'Answer text in Arabic is required.',
            'questions.*.answers.*.text_en.required_with' => 'Answer text in English is required.',
            'questions.*.correct_answer.required_with' => 'Please select the correct answer.',
        ];

        $validatedData = $request->validate($rules, $messages);

        // Additional validation: Either domain or chapter must be selected
        if (empty($validatedData['domain_id']) && empty($validatedData['chapter_id'])) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['domain_id' => 'Either Domain or Chapter must be selected.']
            );
        }

        // Additional validation: Questions can only be added if domain is selected
        if (!empty($validatedData['questions']) && empty($validatedData['domain_id'])) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['questions' => 'Questions can only be added to slides with a domain selected.']
            );
        }

        return $validatedData;
    }

    /**
     * Create questions for a slide
     */
    private function createQuestionsForSlide(Slide $slide, array $questions): int
    {
        $questionsCreated = 0;

        foreach ($questions as $questionData) {
            // Validate correct answer index
            $correctAnswerIndex = (int) $questionData['correct_answer'];
            
            if (!isset($questionData['answers'][$correctAnswerIndex])) {
                throw new \InvalidArgumentException('Invalid correct answer index for question.');
            }

            // Create the test (question)
            $test = Test::create([
                'question_ar' => $questionData['question_ar'],
                'question_en' => $questionData['question_en'],
                'slide_id' => $slide->id,
            ]);

            // Create answers
            foreach ($questionData['answers'] as $answerIndex => $answerData) {
                TestAnswer::create([
                    'text_ar' => $answerData['text_ar'],
                    'text_en' => $answerData['text_en'],
                    'is_correct' => $answerIndex == $correctAnswerIndex,
                    'test_id' => $test->id,
                ]);
            }

            $questionsCreated++;
        }

        return $questionsCreated;
    }

    /**
     * Exams Management
     */
    public function exams()
    {
        $exams = Exam::withCount('questions')->latest()->paginate(20);
        return view('admin.exams.index', compact('exams'));
    }

    public function createExam()
    {
        return view('admin.exams.create');
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.text_en' => 'required|string',
            'questions.*.text_ar' => 'required|string',
            'questions.*.type' => 'required|string|in:single_choice,multiple_choice',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text_en' => 'required|string',
            'questions.*.options.*.text_ar' => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
        ]);

        // Custom validation for single choice questions
        foreach ($request->questions as $index => $questionData) {
            if ($questionData['type'] === 'single_choice') {
                $correctCount = collect($questionData['options'])->filter(fn($opt) => $opt['is_correct'] ?? false)->count();
                if ($correctCount !== 1) {
                    return back()->withErrors(["questions.{$index}.options" => 'Single choice questions must have exactly one correct answer.'])->withInput();
                }
            }
        }
        
        DB::transaction(function () use ($request) {
            // Create exam with proper field mapping
            $exam = Exam::create([
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'number_of_questions' => count($request->questions),
                'time' => $request->duration,
                'is_completed' => $request->is_completed ?? false,
            ]);
            
            foreach ($request->questions as $questionData) {
                $question = ExamQuestions::create([
                    'question' => $questionData['text_en'],
                    'question-ar' => $questionData['text_ar'],
                    'text-ar' => '',
                    'type' => $questionData['type'],
                    'marks' => $questionData['points'],
                    'exam_id' => $exam->id,
                ]);
        
                foreach ($questionData['options'] as $optionData) {
                    $question->answers()->create([
                        'answer' => $optionData['text_en'],
                        'answer-ar' => $optionData['text_ar'],
                        'is_correct' => $optionData['is_correct'] ?? false,
                    ]);
                }
            }
        });

        return redirect()->route('admin.exams')->with('success', 'Exam created successfully.');
    }

    public function editExam(Exam $exam)
    {
        // Load the exam with its questions and answers
        $exam->load(['questions' => function($query) {
            $query->with('answers');
        }]);
        
        return view('admin.exams.edit', compact('exam'));
    }
    public function updateExam(Request $request, Exam $exam)
    {
        // dd($request->all());
        // $validated = $request->validated();
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.question-ar' => 'required|string',
            'questions.*.type' => 'required|string|in:single_choice,multiple_choice,true_false',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.answers.*.answer-ar' => 'required|string',
            'questions.*.answers' => [
                   'required',
                   'array',
                   'min:1',
                   function ($attribute, $value, $fail) {
                       $hasCorrectAnswer = collect($value)->contains('is_correct', true);
                       if (!$hasCorrectAnswer) {
                           $fail("At least one answer must be marked as correct for question: " . str_replace('questions.', '', $attribute));
                       }
                   }
            ],
            'questions.*.answers.*.is_correct' => 'boolean'
        ]);
    
        DB::transaction(function () use ($request, $exam) {
            // Update exam basic information
            $exam->update([
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
                'number_of_questions' => count($request->questions),
            ]);
    
            // Track existing questions for deletion
            $existingQuestionIds = $exam->questions->pluck('id')->toArray();
            $updatedQuestionIds = [];
    
            foreach ($request->questions as $questionData) {
                // Create or update question
                $question = $exam->questions()->updateOrCreate(
                    ['id' => $questionData['id'] ?? null],
                    [
                        'question' => $questionData['question'],
                        'question-ar' => $questionData['question-ar'],
                        'type' => $questionData['type'],
                        'marks' => $questionData['marks'],
                    ]
                );
    
                $updatedQuestionIds[] = $question->id;
    
                // Handle answers using your table structure
                $this->updateQuestionAnswers($question, $questionData['answers']);
            }
    
            // Delete removed questions (cascade will handle answers)
            $exam->questions()->whereNotIn('id', $updatedQuestionIds)->delete();
        });
    
        return redirect()
            ->route('admin.exams')
            ->with('success', 'Exam updated successfully.');
    }
    
    private function updateQuestionAnswers($question, $answersData)
    {
        // Get existing answer IDs for this question
        $existingAnswerIds = $question->answers->pluck('id')->toArray();
        $updatedAnswerIds = [];
    
        foreach ($answersData as $answerData) {
            // Create or update answer in question_exam_answer table
            $answer = ExamQuestionAnswer::updateOrCreate(
                ['id' => $answerData['id'] ?? null],
                [
                    'exam_question_id' => $question->id,
                    'answer' => $answerData['answer'],
                    'answer-ar' => $answerData['answer-ar'],
                    'is_correct' => $answerData['is_correct'] ?? false,
                ]
            );
    
            $updatedAnswerIds[] = $answer->id;
        }
    
        // Delete removed answers
        ExamQuestionAnswer::where('exam_question_id', $question->id)
            ->whereNotIn('id', $updatedAnswerIds)
            ->delete();
    }
    
    // public function updateExam(Request $request, Exam $exam)
    // {
    //     $validated = $request->validate([
    //         'title_en' => 'required|string|max:255',
    //         'title_ar' => 'required|string|max:255',
    //         'description_en' => 'nullable|string',
    //         'description_ar' => 'nullable|string',
    //         'duration' => 'required|integer|min:1',
    //         'questions' => 'required|array|min:1',
    //         'questions.*.question' => 'required|string',
    //         'questions.*.question-ar' => 'required|string',
    //         'questions.*.type' => 'required|string|in:single_choice,multiple_choice',
    //         'questions.*.marks' => 'required|integer|min:1',
    //         'questions.*.answers' => 'required|array|min:2',
    //         'questions.*.answers.*.is_correct' => 'required|boolean',
    //         'questions.*.answers.*.answer' => 'required|string',
    //         'questions.*.answers.*.answer-ar' => 'required|string',
    //     ]);

    //     // Custom validation for single choice questions
    //     foreach ($request->questions as $index => $questionData) {
    //         if ($questionData['type'] === 'single_choice') {
    //             $correctCount = collect($questionData['answers'])->filter(fn($opt) => $opt['is_correct'] ?? false)->count();
    //             if ($correctCount !== 1) {
    //                 return back()->withErrors(["questions.{$index}.answers" => 'Single choice questions must have exactly one correct answer.'])->withInput();
    //             }
    //         }
    //     }

    //     DB::transaction(function () use ($request, $exam) {
    //         $exam->update([
    //             'text' => $request->title_en,
    //             'text-ar' => $request->title_ar,
    //             'description' => $request->description_en,
    //             'description-ar' => $request->description_ar,
    //             'time' => $request->duration,
    //             'number_of_questions' => count($request->questions),
    //         ]);
    
    //         $existingQuestionIds = $exam->questions->pluck('id')->toArray();
    //         $updatedQuestionIds = [];
    
    //         foreach ($request->questions as $questionData) {
    //             $question = $exam->questions()->updateOrCreate(
    //                 ['id' => $questionData['id'] ?? null],
    //                 [
    //                     'question' => $questionData['question'],
    //                     'question-ar' => $questionData['question-ar'],
    //                     'type' => $questionData['type'],
    //                     'marks' => $questionData['marks'],
    //                 ]
    //             );
    
    //             $updatedQuestionIds[] = $question->id;
    
    //             // Get existing answer IDs for this question
    //             $existingAnswerIds = $question->answers->pluck('id')->toArray();
    //             $updatedAnswerIds = [];
    
    //             foreach ($questionData['answers'] as $optionData) {
    //                 $answer = $question->answers()->updateOrCreate(
    //                     ['id' => $optionData['id'] ?? null],
    //                     [
    //                         'answer' => $optionData['answer'],
    //                         'answer-ar' => $optionData['answer-ar'],
    //                         'is_correct' => $optionData['is_correct'] ?? false,
    //                     ]
    //                 );
    
    //                 $updatedAnswerIds[] = $answer->id;
    //             }
    
    //             $question->answers()->whereNotIn('id', $updatedAnswerIds)->delete();
    //         }
    
    //         $exam->questions()->whereNotIn('id', $updatedQuestionIds)->delete();
    //     });
    
    //     return redirect()->route('admin.exams')->with('success', 'Exam updated successfully.');
    // }

    public function import(Request $request)
    {
        print_r($request->all());
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ExamsImport, $request->file('excel_file'));

            return redirect()->back()->with('success', 'Exam imported successfully!');
        } catch (\Exception $e) {
            print_r($e->getMessage());
            // return redirect()->back()->with('error', 'Error importing exam: '.$e->getMessage());
        }
    }
    public function destroyExam(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams')->with('success', 'Exam deleted successfully.');
    }

    /**
     * Quiz Attempts
     */
    public function quizAttempts()
    {
        $quizAttempts = QuizAttempt::with(['user', 'quiz'])->latest()->paginate(20);
        return view('admin.quiz-attempts.index', compact('quizAttempts'));
    }

    public function showQuizAttempt(QuizAttempt $quizAttempt)
    {
        $quizAttempt->load(['user', 'quiz.answers']);
        return view('admin.quiz-attempts.show', compact('quizAttempt'));
    }

    /**
     * Test Attempts
     */
    public function testAttempts()
    {
        $testAttempts = TestAttempt::with(['user', 'test'])->latest()->paginate(20);
        return view('admin.test-attempts.index', compact('testAttempts'));
    }

    public function showTestAttempt(TestAttempt $testAttempt)
    {
        $testAttempt->load(['user', 'test.testAnswers']);
        return view('admin.test-attempts.show', compact('testAttempt'));
    }

    // /**
    //  * Applications Management
    //  */
    // public function applications()
    // {
    //     $applications = Application::with(['candidate', 'job'])->latest()->paginate(20);
    //     return view('admin.applications.index', compact('applications'));
    // }

    // public function showApplication(Application $application)
    // {
    //     $application->load(['candidate', 'job']);
    //     return view('admin.applications.show', compact('application'));
    // }

    // public function updateApplicationStatus(Request $request, Application $application)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,reviewed,accepted,rejected'
    //     ]);

    //     $application->update(['status' => $request->status]);

    //     return redirect()->back()->with('success', 'Application status updated successfully.');
    // }

    /**
     * Notifications Management
     */
    public function notifications()
    {
        $notifications = Notification::with('user')->latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function createNotification()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    public function storeNotification(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'subtext' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'send_to_all' => 'boolean',
        ]);
  
        if ($request->send_to_all) {
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'text' => $request->text,
                    'subtext' => $request->subtext,
                    'user_id' => $user->id,
                    'is_seen' => false,
                ]);
            }
        } else {
            Notification::create($request->only(['text', 'subtext', 'user_id']));
        }

        return redirect()->route('admin.notifications')->with('success', 'Notification(s) sent successfully.');
    }

    public function destroyNotification(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications')->with('success', 'Notification deleted successfully.');
    }

    /**
     * Analytics & Reports
     */
    public function analytics()
    {
        $data = [
            'user_growth' => $this->getUserGrowthData(),
            'quiz_performance' => $this->getQuizPerformanceData(),
            'domain_popularity' => $this->getDomainPopularityData(),
            'application_stats' => $this->getApplicationStatsData(),
        ];

        return view('admin.analytics', compact('data'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function exportReports(Request $request)
    {
        // Implement export functionality (CSV, PDF, etc.)
        return response()->download(storage_path('app/reports/admin_report.csv'));
    }

    /**
     * Helper methods for analytics
     */
    private function getUserGrowthData()
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getQuizPerformanceData()
    {
        return QuizAttempt::selectRaw('AVG(score) as avg_score, DATE(created_at) as date')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getDomainPopularityData()
    {
        return Domain::withCount(['slides' => function($query) {
            $query->whereHas('slideAttempts', function($q) {
                $q->where('created_at', '>=', Carbon::now()->subDays(30));
            });
        }])->get();
    }

    private function getApplicationStatsData()
    {
        return Application::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
    }
}