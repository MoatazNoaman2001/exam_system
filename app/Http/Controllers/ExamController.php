<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use App\Services\ExamService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index()
    {
        $exams = Exam::with('questions')
            ->withCount('questions')
            ->get()
            ->map(function ($exam) {
                $activeSession = $exam->getActiveSession(Auth::id());
                $exam->has_active_session = $activeSession !== null;
                $exam->active_session_id = $activeSession?->id;
                $exam->session_status = $activeSession?->status;
                return $exam;
            });
        return view('student.exams.index', compact('exams'));
    }

    public function show($id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        $activeSession = $exam->getActiveSession(Auth::id());
        
        $previousSessions = $exam->userSessions(Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        return view('student.exams.show', compact('exam', 'activeSession', 'previousSessions'));
    }

    public function startExam($id)
    {
        $canStart = $this->examService->canUserStartExam(Auth::id(), $id);
            
        if (!$canStart['can_start']) {
            if ($canStart['reason'] === 'active_session_exists') {
                return redirect()->route('student.exams.take', $canStart['active_session_id'])
                    ->with('info', __('lang.active_session_resumed'));
            }
            
            return back()->with('error', __('lang.cannot_start_exam'));
        }

        $session = $this->examService->startExam($id, Auth::id());
        
        return redirect()->route('student.exams.take', $session->id)
            ->with('success', __('lang.exam_started_successfully'));
    }

    public function take($sessionId)
    {
        $session = ExamSession::with(['exam', 'exam.questions.answers'])
            ->where('user_id', Auth::id())
            ->findOrFail($sessionId);

        if ($session->status === 'completed') {
            return redirect()->route('student.exams.result', $sessionId);
        }
        
        if ($session->isExpired()) {
            $this->examService->completeExam($session);
            return redirect()->route('student.exams.result', $sessionId)
                ->with('info', __('lang.exam_time_expired'));
        }

        $currentQuestion = $session->getCurrentQuestion();
        if (!$currentQuestion) {
            return redirect()->route('student.exams.index')
                ->with('error', __('lang.no_questions_available'));
        }

        // Load the question with its answers
        $currentQuestion->load('answers');

        // Get user's previous answer for this question
        $userAnswer = $session->userAnswers()
            ->where('exam_question_id', $currentQuestion->id)
            ->first();

        $progress = $this->examService->getExamProgress($sessionId);

        return view('student.exams.take', compact(
            'session', 'currentQuestion', 'userAnswer', 'progress'
        ));
    }

    public function submitAnswer(Request $request, $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:exam_questions,id',
            'selected_answers' => 'required|array',
            'selected_answers.*' => 'integer',
            'time_spent' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->examService->submitAnswer(
            $sessionId,
            $request->question_id,
            $request->selected_answers,
            $request->time_spent
        );

        if ($result['status'] === 'expired') {
            return response()->json([
                'success' => false,
                'message' => __('lang.exam_time_expired'),
                'redirect' => route('student.exams.result', $sessionId)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('lang.answer_submitted_successfully'),
            'session' => $result['session']
        ]);

    }

    public function navigate(Request $request, $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'question_index' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = $this->examService->navigateToQuestion(
                $sessionId,
                $request->question_index
            );

            return response()->json([
                'success' => true,
                'message' => __('lang.navigation_successful'),
                'session' => $session
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.invalid_question_index')
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.navigation_error')
            ], 500);
        }
    }

    public function pause($sessionId)
    {
        try {
            $session = $this->examService->pauseExam($sessionId);

            return response()->json([
                'success' => true,
                'message' => __('lang.exam_paused_successfully'),
                'session' => $session
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_pausing_exam')
            ], 500);
        }
    }

    public function resume($sessionId)
    {
        try {
            $session = $this->examService->resumeExam($sessionId);

            if ($session->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => __('lang.exam_time_expired'),
                    'redirect' => route('student.exams.result', $sessionId)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => __('lang.exam_resumed_successfully'),
                'session' => $session
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_resuming_exam')
            ], 500);
        }
    }

    public function complete($sessionId)
    {
        try {
            $session = $this->examService->completeExam($sessionId);

            return response()->json([
                'success' => true,
                'message' => __('lang.exam_completed_successfully'),
                'redirect' => route('student.exams.result', $sessionId)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_completing_exam')
            ], 500);
        }
    }

    public function result($sessionId)
    {
        $session = ExamSession::with(['exam', 'userAnswers'])
            ->where('user_id', Auth::id())
            ->findOrFail($sessionId);

        if ($session->status !== 'completed') {
            return redirect()->route('student.exams.take', $sessionId)
                ->with('error', __('lang.exam_not_completed'));
        }

        $serviceResults = $this->examService->getDetailedResults($sessionId);

        // Restructure the data to match the Blade template expectations
        $results = [
            'results' => $serviceResults['results'], // The question-by-question results
            'statistics' => $serviceResults['statistics'] // The overall statistics
        ];

        // Optional: Generate recommendations based on performance
        $recommendations = $this->generateRecommendations($serviceResults['statistics']);

        return view('student.exams.result', compact('session', 'results', 'recommendations'));
    }

    private function generateRecommendations($statistics)
    {
        $recommendations = [];
        $score = floatval($statistics['final_score']);
        $accuracy = $statistics['accuracy_percentage'];
        $avgTime = $statistics['average_time_per_question'];
    
        // Performance-based recommendations
        if ($score >= 85) {
            $recommendations[] = [
                'type' => 'success',
                'title' => __('lang.excellent_work'),
                'message' => __('lang.excellent_performance_message')
            ];
        } elseif ($score >= 70) {
            $recommendations[] = [
                'type' => 'success',
                'title' => __('lang.good_job'),
                'message' => __('lang.good_performance_message')
            ];
        } elseif ($score >= 60) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => __('lang.room_for_improvement'),
                'message' => __('lang.improvement_needed_message')
            ];
        } else {
            $recommendations[] = [
                'type' => 'danger',
                'title' => __('lang.needs_more_study'),
                'message' => __('lang.failed_performance_message')
            ];
        }
    
        // Time-based recommendations
        if ($avgTime > 120) { // More than 2 minutes per question
            $recommendations[] = [
                'type' => 'info',
                'title' => __('lang.time_management'),
                'message' => __('lang.time_management_message')
            ];
        }
    
        // Accuracy-based recommendations
        if ($statistics['unanswered_questions'] > 0) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => __('lang.complete_all_questions'),
                'message' => __('lang.unanswered_questions_message')
            ];
        }
    
        return $recommendations;
    }

    public function detailedReport($sessionId)
    {
        $session = ExamSession::where('user_id', Auth::id())
            ->findOrFail($sessionId);

        if ($session->status !== 'completed') {
            return redirect()->route('student.exams.take', $sessionId)
                ->with('error', __('lang.exam_not_completed'));
        }

        $report = $this->examService->generateExamReport($sessionId);

        return view('student.exams.detailed-report', compact('report'));
    }

    public function reviewSession($sessionId)
    {
        $session = ExamSession::with(['exam', 'userAnswers.question.answers'])
            ->where('user_id', Auth::id())
            ->findOrFail($sessionId);

        if ($session->status !== 'completed') {
            return redirect()->route('student.exams.take', $sessionId)
                ->with('error', __('lang.exam_not_completed'));
        }

        $results = $this->examService->getDetailedResults($sessionId);

        return view('student.exams.review', compact('session', 'results'));
    }

    public function getProgress($sessionId)
    {
        try {
            $progress = $this->examService->getExamProgress($sessionId);

            return response()->json([
                'success' => true,
                'progress' => $progress
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_getting_progress')
            ], 500);
        }
    }

    public function updateActivityTime(Request $request, $sessionId)
    {
        try {
            $session = ExamSession::where('user_id', Auth::id())
                ->findOrFail($sessionId);

            $session->updateActivity();

            return response()->json([
                'success' => true,
                'message' => __('lang.activity_updated'),
                'remaining_time' => $session->remaining_time
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_updating_activity')
            ], 500);
        }
    }

    public function apiGetQuestion(Request $request, $sessionId)
    {
        try {
            $session = ExamSession::where('user_id', Auth::id())
                ->findOrFail($sessionId);

            $questionIndex = $request->get('index', $session->current_question_index);

            if ($questionIndex < 0 || $questionIndex >= count($session->question_order)) {
                return response()->json([
                    'success' => false,
                    'message' => __('lang.invalid_question_index')
                ], 400);
            }

            $questionId = $session->question_order[$questionIndex];
            $question = ExamQuestion::with('answers')->find($questionId);

            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => __('lang.question_not_found')
                ], 404);
            }

            // Get user's previous answer for this question
            $userAnswer = $session->userAnswers()
                ->where('exam_question_id', $question->id)
                ->first();

            return response()->json([
                'success' => true,
                'question' => $question,
                'user_answer' => $userAnswer,
                'question_index' => $questionIndex
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_getting_question')
            ], 500);
        }
    }

    public function sessionHistory(Request $request)
    {
        $examId = $request->get('exam_id');
        $sessions = $this->examService->getSessionHistory(Auth::id(), $examId);

        return view('student.exams.history', compact('sessions'));
    }

    public function deleteSession($sessionId)
    {
        try {
            $session = ExamSession::where('user_id', Auth::id())
                ->findOrFail($sessionId);

            // Only allow deletion of completed sessions
            if ($session->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => __('lang.cannot_delete_active_session')
                ], 400);
            }

            $session->delete();

            return response()->json([
                'success' => true,
                'message' => __('lang.session_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_deleting_session')
            ], 500);
        }
    }

    // Additional helper methods that might be useful

    public function getSessionStatus($sessionId)
    {
        try {
            $session = ExamSession::where('user_id', Auth::id())
                ->findOrFail($sessionId);

            return response()->json([
                'success' => true,
                'status' => $session->status,
                'remaining_time' => $session->remaining_time,
                'progress' => $session->progress_percentage,
                'is_expired' => $session->isExpired()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_getting_session_status')
            ], 500);
        }
    }

    public function getQuestionsByRange(Request $request, $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'start_index' => 'required|integer|min:0',
            'end_index' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $questions = $this->examService->getQuestionsByRange(
                $sessionId,
                $request->start_index,
                $request->end_index
            );

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.invalid_question_range')
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('lang.error_getting_questions')
            ], 500);
        }
    }

    public function exportResults($sessionId)
    {
        try {
            $session = ExamSession::where('user_id', Auth::id())
                ->findOrFail($sessionId);

            if ($session->status !== 'completed') {
                return redirect()->back()
                    ->with('error', __('lang.exam_not_completed'));
            }

            $report = $this->examService->generateExamReport($sessionId);

            // Generate PDF or Excel export
            // This would typically use a package like dompdf or maatwebsite/excel
            // For now, we'll return the data as JSON for download
            $filename = "exam_results_{$session->exam->title}_{$session->id}.json";

            return response()->json($report)
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
                ->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('lang.error_exporting_results'));
        }
    }
}