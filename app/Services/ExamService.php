<?php 
namespace App\Services;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\ExamQuestion;
use App\Models\ExamQuestions;
use App\Models\UserExamAnswer;
use Illuminate\Support\Facades\DB;

class ExamService
{
    public function startExam($examId, $userId)
    {
        return DB::transaction(function () use ($examId, $userId) {
            $exam = Exam::findOrFail($examId);
            
            // Check if user has an active session
            $activeSession = $exam->getActiveSession($userId);
            if ($activeSession) {
                return $activeSession;
            }

            // Get randomized question order (all 180 questions)
            $questionIds = $exam->questions()->pluck('id')->shuffle()->toArray();

            return ExamSession::create([
                'user_id' => $userId,
                'exam_id' => $examId,
                'started_at' => now(),
                'last_activity_at' => now(),
                'question_order' => $questionIds,
                'current_question_index' => 0,
                'answered_questions' => [],
                'status' => 'in_progress'
            ]);
        });
    }

    public function submitAnswer($sessionId, $questionId, $selectedAnswers, $timeSpent)
    {
        return DB::transaction(function () use ($sessionId, $questionId, $selectedAnswers, $timeSpent) {
            $session = ExamSession::findOrFail($sessionId);
            $question = ExamQuestion::findOrFail($questionId);

            // Update session activity
            $session->updateActivity();
            $session->increment('total_time_spent', $timeSpent);

            // Check if time expired (3 hours = 10800 seconds)
            if ($session->isExpired()) {
                $this->completeExam($session);
                return ['status' => 'expired', 'session' => $session];
            }

            // Get correct answers
            $correctAnswerIds = $question->correctAnswers()->pluck('id')->toArray();
            
            // Determine if answer is correct
            $isCorrect = $this->isAnswerCorrect($question->type, $selectedAnswers, $correctAnswerIds);

            // Save or update user answer
            UserExamAnswer::updateOrCreate(
                [
                    'exam_session_id' => $sessionId,
                    'exam_question_id' => $questionId
                ],
                [
                    'selected_answers' => $selectedAnswers,
                    'is_correct' => $isCorrect,
                    'time_spent' => $timeSpent
                ]
            );

            // Update answered questions list
            $answeredQuestions = $session->answered_questions ?? [];
            if (!in_array($questionId, $answeredQuestions)) {
                $answeredQuestions[] = $questionId;
                $session->update(['answered_questions' => $answeredQuestions]);
            }

            return ['status' => 'success', 'session' => $session->fresh()];
        });
    }

    private function isAnswerCorrect($questionType, $selectedAnswers, $correctAnswers)
    {
        switch ($questionType) {
            case 'single_choice':
            case 'true_false':
                return count($selectedAnswers) === 1 && 
                       count($correctAnswers) === 1 && 
                       $selectedAnswers[0] === $correctAnswers[0];
            
            case 'multiple_choice':
                sort($selectedAnswers);
                sort($correctAnswers);
                return $selectedAnswers === $correctAnswers;
            
            case 'matching':
                // For matching questions, check if all pairs are correct
                return $this->isMatchingCorrect($selectedAnswers, $correctAnswers);
            
            default:
                return false;
        }
    }

    private function isMatchingCorrect($selectedAnswers, $correctAnswers)
    {
        // Assuming selectedAnswers is array of pairs like ['item1-match1', 'item2-match2']
        // and correctAnswers has the same format
        if (count($selectedAnswers) !== count($correctAnswers)) {
            return false;
        }

        foreach ($selectedAnswers as $selected) {
            if (!in_array($selected, $correctAnswers)) {
                return false;
            }
        }

        return true;
    }

    public function navigateToQuestion($sessionId, $questionIndex)
    {
        $session = ExamSession::findOrFail($sessionId);
        
        // Validate question index
        if ($questionIndex < 0 || $questionIndex >= count($session->question_order)) {
            throw new \InvalidArgumentException('Invalid question index');
        }

        $session->update([
            'current_question_index' => $questionIndex,
            'last_activity_at' => now()
        ]);

        return $session->fresh();
    }

    public function pauseExam($sessionId)
    {
        $session = ExamSession::findOrFail($sessionId);
        $session->update([
            'status' => 'paused',
            'last_activity_at' => now()
        ]);

        return $session;
    }

    public function resumeExam($sessionId)
    {
        $session = ExamSession::findOrFail($sessionId);
        
        if ($session->isExpired()) {
            return $this->completeExam($session);
        }

        $session->update([
            'status' => 'in_progress',
            'last_activity_at' => now()
        ]);

        return $session;
    }

    public function completeExam($session)
    {
        if (is_string($session)) {
            $session = ExamSession::findOrFail($session);
        }

        $score = $this->calculateScore($session);

        $session->update([
            'status' => 'completed',
            'completed_at' => now(),
            'score' => $score
        ]);

        return $session->fresh();
    }

    private function calculateScore($session)
    {
        $totalQuestions = count($session->question_order);
        if ($totalQuestions === 0) {
            return 0;
        }

        $correctAnswers = $session->userAnswers()
            ->where('is_correct', true)
            ->count();

        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }

    public function getExamProgress($sessionId)
    {
        $session = ExamSession::with(['exam', 'userAnswers'])->findOrFail($sessionId);
        
        $totalQuestions = count($session->question_order);
        $answeredCount = count($session->answered_questions ?? []);
        $currentIndex = $session->current_question_index;

        return [
            'total_questions' => $totalQuestions,
            'answered_count' => $answeredCount,
            'current_index' => $currentIndex,
            'remaining_time' => $session->remaining_time,
            'progress_percentage' => $session->progress_percentage,
            'status' => $session->status
        ];
    }

    public function getQuestionsByRange($sessionId, $startIndex, $endIndex)
    {
        $session = ExamSession::findOrFail($sessionId);
        
        if ($startIndex < 0 || $endIndex >= count($session->question_order) || $startIndex > $endIndex) {
            throw new \InvalidArgumentException('Invalid question range');
        }

        $questionIds = array_slice($session->question_order, $startIndex, $endIndex - $startIndex + 1);
        
        return ExamQuestion::with('answers')
            ->whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');
    }

    public function getDetailedResults($sessionId)
    {
        $session = ExamSession::with(['exam', 'userAnswers.question.answers'])
            ->findOrFail($sessionId);

        if ($session->status !== 'completed') {
            throw new \Exception('Exam session is not completed');
        }

        $results = [];
        $correctCount = 0;
        $totalQuestions = count($session->question_order);

        foreach ($session->question_order as $index => $questionId) {
            $userAnswer = $session->userAnswers->where('exam_question_id', $questionId)->first();
            $question = ExamQuestions::with('answers')->find($questionId);
            
            if ($question) {
                $correctAnswers = $question->correctAnswers()->pluck('id')->toArray();
                $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                
                if ($isCorrect) {
                    $correctCount++;
                }

                $results[] = [
                    'question_number' => $index + 1,
                    'question' => $question,
                    'user_answer' => $userAnswer,
                    'correct_answers' => $correctAnswers,
                    'is_correct' => $isCorrect,
                    'time_spent' => $userAnswer ? $userAnswer->time_spent : 0,
                    'answered' => $userAnswer !== null
                ];
            }
        }

        return [
            'session' => $session,
            'results' => $results,
            'statistics' => [
                'total_questions' => $totalQuestions,
                'answered_questions' => $session->userAnswers->count(),
                'correct_answers' => $correctCount,
                'incorrect_answers' => $session->userAnswers->where('is_correct', false)->count(),
                'unanswered_questions' => $totalQuestions - $session->userAnswers->count(),
                'accuracy_percentage' => $session->userAnswers->count() > 0 
                    ? round(($correctCount / $session->userAnswers->count()) * 100, 2) 
                    : 0,
                'total_time_spent' => $session->total_time_spent,
                'average_time_per_question' => $session->userAnswers->count() > 0 
                    ? round($session->total_time_spent / $session->userAnswers->count(), 2) 
                    : 0,
                'final_score' => $session->score
            ]
        ];
    }

    public function generateExamReport($sessionId)
    {
        $results = $this->getDetailedResults($sessionId);
        
        // Group questions by type for analysis
        $questionTypes = [];
        foreach ($results['results'] as $result) {
            $type = $result['question']->type;
            if (!isset($questionTypes[$type])) {
                $questionTypes[$type] = [
                    'total' => 0,
                    'correct' => 0,
                    'answered' => 0
                ];
            }
            
            $questionTypes[$type]['total']++;
            if ($result['answered']) {
                $questionTypes[$type]['answered']++;
                if ($result['is_correct']) {
                    $questionTypes[$type]['correct']++;
                }
            }
        }

        // Calculate performance by question type
        foreach ($questionTypes as $type => &$stats) {
            $stats['accuracy'] = $stats['answered'] > 0 
                ? round(($stats['correct'] / $stats['answered']) * 100, 2) 
                : 0;
        }

        return [
            'session_details' => $results['session'],
            'overall_statistics' => $results['statistics'],
            'question_type_analysis' => $questionTypes,
            'detailed_results' => $results['results'],
            'recommendations' => $this->generateRecommendations($results['statistics'], $questionTypes)
        ];
    }

    private function generateRecommendations($statistics, $questionTypes)
    {
        $recommendations = [];

        // Overall performance recommendations
        if ($statistics['final_score'] >= 85) {
            $recommendations[] = [
                'type' => 'success',
                'title' => 'Excellent Performance',
                'message' => 'Great job! You have demonstrated strong knowledge across the exam topics.'
            ];
        } elseif ($statistics['final_score'] >= 70) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Good Performance',
                'message' => 'Well done! Consider reviewing the topics you missed to further improve.'
            ];
        } else {
            $recommendations[] = [
                'type' => 'danger',
                'title' => 'Needs Improvement',
                'message' => 'Consider additional study and practice before retaking the exam.'
            ];
        }

        // Time management recommendations
        $avgTimePerQuestion = $statistics['average_time_per_question'];
        if ($avgTimePerQuestion > 120) { // More than 2 minutes per question
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Time Management',
                'message' => 'Consider working on time management. Try to spend less time per question to complete more questions.'
            ];
        }

        // Question type specific recommendations
        foreach ($questionTypes as $type => $stats) {
            if ($stats['accuracy'] < 60 && $stats['answered'] > 5) {
                $recommendations[] = [
                    'type' => 'warning',
                    'title' => ucfirst(str_replace('_', ' ', $type)) . ' Questions',
                    'message' => "Focus on improving your performance on {$type} questions. Accuracy: {$stats['accuracy']}%"
                ];
            }
        }

        return $recommendations;
    }

    public function getSessionHistory($userId, $examId = null)
    {
        $query = ExamSession::with('exam')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        return $query->get();
    }

    public function canUserStartExam($userId, $examId)
    {
        $exam = Exam::findOrFail($examId);
        
        // Check if user has an active session
        $activeSession = $exam->getActiveSession($userId);
        if ($activeSession) {
            return [
                'can_start' => false,
                'reason' => 'active_session_exists',
                'active_session_id' => $activeSession->id
            ];
        }

        // Add any other business rules here
        // For example: maximum attempts per day, prerequisites, etc.
        
        return [
            'can_start' => true,
            'reason' => null
        ];
    }
}
?>
