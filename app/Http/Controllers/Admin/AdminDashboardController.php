<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Plan;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\User;
use App\Models\Slide;
use App\Models\Domain;
use App\Models\Chapter;
use App\Models\Application;
use App\Models\ExamSession;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display the admin dashboard
     */
    public function index()
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

        return view('admin.analytics.index', compact('data'));
    }

    public function reports()
    {
        return view('admin.reports.index');
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