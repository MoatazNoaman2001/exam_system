<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Domain;
use App\Models\Slide;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use App\Models\Application;
use App\Models\Notification;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'phone' => 'nullable|string|max:20',
            'preferred_language' => 'nullable|string|max:10',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password, // Will be hashed by mutator
            'role' => $request->role,
            'phone' => $request->phone,
            'preferred_language' => $request->preferred_language ?? 'en',
            'verified' => true,
            'is_agree' => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
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
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'phone' => 'nullable|string|max:20',
            'preferred_language' => 'nullable|string|max:10',
            'verified' => 'boolean',
        ]);

        $user->update($request->only([
            'username', 'email', 'role', 'phone', 'preferred_language', 'verified'
        ]));

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
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
            'icon' => 'nullable|string|max:100',
            'count' => 'nullable|integer|min:0',
        ]);

        Domain::create($request->only(['text', 'icon', 'count']));

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
            'icon' => 'nullable|string|max:100',
            'count' => 'nullable|integer|min:0',
            'is_completed' => 'boolean',
        ]);

        $domain->update($request->only(['text', 'icon', 'count', 'is_completed']));

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

    public function editChapter(Chapter $domain)
    {
        return view('admin.chapter.edit', compact('chapter'));
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'is_completed' => 'boolean',
        ]);

        $chapter->update($request->only(['text', 'count', 'is_completed']));

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
        $request->validate([
            'text' => 'required|string|max:255',
            'content' => 'required|string',
            'domain_id' => 'required|exists:domains,id',
            'chapter_id' => 'required|exists:chapters,id',
            'count' => 'nullable|integer|min:0',
        ]);

        Slide::create($request->only(['text', 'content', 'domain_id', 'chapter_id', 'count']));

        return redirect()->route('admin.slides')->with('success', 'Slide created successfully.');
    }

    public function editSlide(Slide $slide)
    {
        $domains = Domain::all();
        $chapters = Chapter::all();
        return view('admin.slides.edit', compact('slide', 'domains', 'chapters'));
    }

    public function updateSlide(Request $request, Slide $slide)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'content' => 'required|string',
            'domain_id' => 'required|exists:domains,id',
            'chapter_id' => 'required|exists:chapters,id',
            'count' => 'nullable|integer|min:0',
            'is_completed' => 'boolean',
        ]);

        $slide->update($request->only(['text', 'content', 'domain_id', 'chapter_id', 'count', 'is_completed']));

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
        $exams = Exam::withCount('introQuestions')->latest()->paginate(20);
        return view('admin.exams.index', compact('exams'));
    }

    public function createExam()
    {
        return view('admin.exams.create');
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'nullable|string',
            'number_of_questions' => 'required|integer|min:1',
            'time' => 'required|integer|min:1',
        ]);

        Exam::create($request->only(['text', 'description', 'number_of_questions', 'time']));

        return redirect()->route('admin.exams')->with('success', 'Exam created successfully.');
    }

    public function editExam(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'description' => 'nullable|string',
            'number_of_questions' => 'required|integer|min:1',
            'time' => 'required|integer|min:1',
            'is_completed' => 'boolean',
        ]);

        $exam->update($request->only(['text', 'description', 'number_of_questions', 'time', 'is_completed']));

        return redirect()->route('admin.exams')->with('success', 'Exam updated successfully.');
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