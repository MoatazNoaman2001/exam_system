<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\Application;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\ExamQuestions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExamsImport;

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
        // Get dashboard statistics
        $totalUsers = User::count();
        $activeLearners = User::whereHas('quizAttempts', function($query) {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        })->count();
        
        $quizAttempts = QuizAttempt::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        // $jobApplications = Application::count();
        
        // Get recent data
        $recentUsers = User::latest()->take(5)->get();
        $recentQuizAttempts = QuizAttempt::with(['user', 'quiz'])
            ->latest()
            ->take(5)
            ->get();
        
        $recentNotifications = Notification::latest()->take(5)->get();
        
        // System overview data
        $totalDomains = Domain::count();
        $totalSlides = Slide::count();
        $activeExams = Exam::where('is_completed', false)->count();
        
        return view('admin.admindashboard', compact(
            'totalUsers',
            'activeLearners', 
            'quizAttempts',
            // 'jobApplications',
            'recentUsers',
            'recentQuizAttempts',
            'recentNotifications',
            'totalDomains',
            'totalSlides',
            'activeExams'
        ));
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
        ]);

        Domain::create($request->only(['text']));

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
        ]);

        $domain->update($request->only(['text']));

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
        $slides = Slide::with(['domain', 'chapter'])->latest()->paginate(20);
        return view('admin.slides.index', compact('slides'));
    }

    public function createSlide()
    {
        $domains = Domain::all();
        $chapters = Chapter::all();
        return view('admin.slides.create', compact('domains', 'chapters'));
    }

    public function storeSlide(Request $request)
    {
        $validatedData = $request->validate([
            'text' => 'required|string|max:255',
            'content' => 'required|file|mimes:pdf|max:5120',
            'domain_id' => 'nullable|exists:domains,id',
            'chapter_id' => 'nullable|exists:chapters,id'
        ]);
    
        // dd($validatedData);
        try {
            $filePath = $request->file('content')->store('slides', 'public');
            
            Slide::create([
                'text' => $validatedData['text'],
                'content' => $filePath,
                'domain_id' => null,
                'chapter_id' => $validatedData['chapter_id']
            ]);
    
            return redirect()->route('admin.slides')
                   ->with('success', 'Slide created successfully.');
    
        } catch (\Exception $e) {
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
    
            return back()->withInput()
                   ->with('error', 'Error creating slide: ' . $e->getMessage());
        }
    }

    public function editSlide(Slide $slide)
    {
        $domains = Domain::all();
        $chapters = Chapter::all();
        return view('admin.slides.edit', compact('slide', 'domains', 'chapters'));
    }

    public function updateSlide(Request $request, Slide $slide)
    {

        // dd($request->all());
        $validated= $request->validate([
            'text' => 'required|string|max:255',
            'content' => 'nullable|file|mimes:pdf|max:5120',
            'domain_id' => 'nullable|exists:domains,id',
            'chapter_id' => 'nullable|exists:chapters,id'
        ]);

        // dd($validated);

        $slide->update($request->only($validated));

        return redirect()->route('admin.slides')->with('success', 'Slide updated successfully.');
    }

    public function destroySlide(Slide $slide)
    {
        $slide->delete();
        return redirect()->route('admin.slides')->with('success', 'Slide deleted successfully.');
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
            'questions.*.type' => 'required|string|in:single_choice,multiple_choice', // example types
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required|array|min:1',
            'questions.*.options.*.text_en' => 'required|string',
            'questions.*.options.*.text_ar' => 'required|string',
            // For single_choice questions, ensure exactly one correct answer
            'questions.*.options.*.is_correct' => [
                'sometimes',
                'boolean',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input(str_replace('.is_correct', '.type', $attribute)) === 'single_choice') {
                        $correctCount = collect($request->input(str_replace('.options.*.is_correct', '.options', $attribute)))
                            ->filter(fn ($opt) => $opt['is_correct'] ?? false)
                            ->count();
                        if ($correctCount !== 1) {
                            $fail('Single choice questions must have exactly one correct answer.');
                        }
                    }
                }
            ]
        ]);

        
        DB::transaction(function () use ($request) {
            // Create exam with proper field mapping
            $exam = Exam::create([
                'text' => $request->title_en,
                'text-ar' => $request->title_ar, // Changed from text_ar to title_ar
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'number_of_questions' => count($request->questions),
                'time' => $request->duration,
                'is_completed' => $request->is_completed ?? false,
            ]);
            foreach ($request->questions as $questionData) {

                // dd($questionData);
                $question = ExamQuestions::create([
                    'question' => $questionData['text_en'],
                    'question-ar' => $questionData['text_ar'],
                    'text-ar' => '',
                    'type' => $questionData['type'],
                    'marks' => $questionData['points'],
                    'exam_id' => $exam->id,
                ]);
        
                foreach ($questionData['options'] as $optionData) { // Changed from answers to options
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
            'questions.*.answers.*.is_correct' => 'boolean'
        ]);
    

        DB::transaction(function () use ($request, $exam) {
            $exam->update([
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
                'number_of_questions' => count($request->questions),
            ]);
    
            $existingQuestionIds = $exam->questions->pluck('id')->toArray();
            $updatedQuestionIds = [];
    
            foreach ($request->questions as $questionData) {
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
    
                // Get existing answer IDs for this question
                $existingAnswerIds = $question->answers->pluck('id')->toArray();
                $updatedAnswerIds = [];
    
                foreach ($questionData['answers'] as $optionData) {
                    $answer = $question->answers()->updateOrCreate(
                        ['id' => $optionData['id'] ?? null],
                        [
                            'answer' => $optionData['answer'],
                            'answer-ar' => $optionData['answer-ar'],
                            'is_correct' => $optionData['is_correct'] ?? false,
                        ]
                    );
    
                    $updatedAnswerIds[] = $answer->id;
                }
    
                $question->answers()->whereNotIn('id', $updatedAnswerIds)->delete();
            }
    
            $exam->questions()->whereNotIn('id', $updatedQuestionIds)->delete();
        });
    
        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully.');
    }

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