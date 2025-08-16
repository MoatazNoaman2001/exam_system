<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\TestAttempt;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Quiz Attempts
     */
    public function quizAttempts(Request $request)
    {
        $query = QuizAttempt::with(['user', 'quiz']);

        // Add filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $quizAttempts = $query->latest()->paginate(20);
        
        return view('admin.attempts.quiz-attempts.index', compact('quizAttempts'));
    }

    public function showQuizAttempt(QuizAttempt $quizAttempt)
    {
        $quizAttempt->load(['user', 'quiz.answers']);
        return view('admin.attempts.quiz-attempts.show', compact('quizAttempt'));
    }

    /**
     * Test Attempts
     */
    public function testAttempts(Request $request)
    {
        $query = TestAttempt::with(['user', 'test']);

        // Add filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('test_id')) {
            $query->where('test_id', $request->test_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $testAttempts = $query->latest()->paginate(20);
        
        return view('admin.attempts.test-attempts.index', compact('testAttempts'));
    }

    public function showTestAttempt(TestAttempt $testAttempt)
    {
        $testAttempt->load(['user', 'test.testAnswers']);
        return view('admin.attempts.test-attempts.show', compact('testAttempt'));
    }

    /**
     * Delete quiz attempt
     */
    public function destroyQuizAttempt(QuizAttempt $quizAttempt)
    {
        $quizAttempt->delete();
        return redirect()->route('admin.attempts.quiz-attempts.index')
            ->with('success', 'Quiz attempt deleted successfully.');
    }

    /**
     * Delete test attempt
     */
    public function destroyTestAttempt(TestAttempt $testAttempt)
    {
        $testAttempt->delete();
        return redirect()->route('admin.attempts.test-attempts.index')
            ->with('success', 'Test attempt deleted successfully.');
    }

    /**
     * Export quiz attempts
     */
    public function exportQuizAttempts(Request $request)
    {
        // Implement CSV export functionality
        $attempts = QuizAttempt::with(['user', 'quiz'])->get();
        
        $csv = "User,Quiz,Score,Date\n";
        foreach ($attempts as $attempt) {
            $csv .= "{$attempt->user->username},{$attempt->quiz->title},{$attempt->score},{$attempt->created_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="quiz-attempts.csv"');
    }

    /**
     * Export test attempts
     */
    public function exportTestAttempts(Request $request)
    {
        // Implement CSV export functionality
        $attempts = TestAttempt::with(['user', 'test'])->get();
        
        $csv = "User,Test,Score,Date\n";
        foreach ($attempts as $attempt) {
            $csv .= "{$attempt->user->username},{$attempt->test->question_en},{$attempt->score},{$attempt->created_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="test-attempts.csv"');
    }
}