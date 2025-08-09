<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use Illuminate\Support\Str;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use App\Models\QuestionExamAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;


class AdminExamQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }
     /**
     * Display questions for a specific exam
     */
    public function index($examId)
    {
        try {
            $exam = Exam::with(['examQuestions.answers'])
                ->findOrFail($examId);

            return view('admin.exams.questions.index', compact('exam'));
        } catch (\Exception $e) {
            Log::error('Error loading exam questions', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return redirect()->route('admin.exams.index')->with('error', 'Exam not found.');
        }
    }

    /**
     * Show form to create a new question for the exam
     */
    public function create($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            return view('admin.exams.questions.create', compact('exam'));
        } catch (\Exception $e) {
            Log::error('Error loading question create form', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return redirect()->route('admin.exams.questions.index', $examId)->with('error', 'Exam not found.');
        }
    }

    /**
     * Store a new question for the exam
     */
    public function store(Request $request, $examId)
    {
        $request->validate([
            'text_en' => 'required|string|max:1000',
            'text_ar' => 'required|string|max:1000',
            'type' => 'required|in:single_choice,multiple_choice',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required|array|min:2|max:6',
            'options.*.text_en' => 'required|string|max:500',
            'options.*.text_ar' => 'required|string|max:500',
            'options.*.reason' => 'nullable|string|max:2000',
            'options.*.reason_ar' => 'nullable|string|max:2000',
            'correct_answer' => 'required_if:type,single_choice|integer',
            'options.*.is_correct' => 'sometimes|boolean',
        ]);

        try {
            $exam = Exam::findOrFail($examId);

            DB::transaction(function () use ($request, $exam) {
                // Create question
                $question = ExamQuestions::create([
                    'id' => Str::uuid(),
                    'exam_id' => $exam->id,
                    'question' => $request->text_en,
                    'question-ar' => $request->text_ar,
                    'text-ar' => $request->text_ar,
                    'type' => $request->type,
                    'marks' => $request->points,
                ]);

                // Process answers
                $this->processAnswers($question, $request->options, $request->type, $request->correct_answer);

                // Update exam question count
                $exam->update([
                    'number_of_questions' => $exam->examQuestions()->count()
                ]);
            });

            return redirect()->route('admin.exams.questions.index', $examId)
                ->with('success', 'Question added successfully!');

        } catch (\Exception $e) {
            Log::error('Question creation failed', [
                'exam_id' => $examId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the question: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit a specific question
     */
    public function edit($examId, $questionId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            $question = ExamQuestions::with('answers')->where('exam_id', $examId)->findOrFail($questionId);
            
            return view('admin.exams.questions.edit', compact('exam', 'question'));
        } catch (\Exception $e) {
            Log::error('Error loading question edit form', [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('admin.exams.questions.index', $examId)->with('error', 'Question not found.');
        }
    }

    /**
     * Update a specific question
     */
    public function update(Request $request, $examId, $questionId)
    {
        $request->validate([
            'text_en' => 'required|string|max:1000',
            'text_ar' => 'required|string|max:1000',
            'type' => 'required|in:single_choice,multiple_choice',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required|array|min:2|max:6',
            'options.*.text_en' => 'required|string|max:500',
            'options.*.text_ar' => 'required|string|max:500',
            'options.*.reason' => 'nullable|string|max:2000',
            'options.*.reason_ar' => 'nullable|string|max:2000',
            'correct_answer' => 'required_if:type,single_choice|integer',
            'options.*.is_correct' => 'sometimes|boolean',
        ]);

        try {
            $exam = Exam::findOrFail($examId);
            $question = ExamQuestions::where('exam_id', $examId)->findOrFail($questionId);

            DB::transaction(function () use ($request, $question) {
                // Update question
                $question->update([
                    'question' => $request->text_en,
                    'question-ar' => $request->text_ar,
                    'text-ar' => $request->text_ar,
                    'type' => $request->type,
                    'marks' => $request->points,
                ]);

                // Delete existing answers
                $question->answers()->delete();

                // Process new answers
                $this->processAnswers($question, $request->options, $request->type, $request->correct_answer);
            });

            return redirect()->route('admin.exams.questions.index', $examId)
                ->with('success', 'Question updated successfully!');

        } catch (\Exception $e) {
            Log::error('Question update failed', [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the question: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific question
     */
    public function destroy($examId, $questionId)
    {
        try {
            $exam = Exam::findOrFail($examId);
            $question = ExamQuestions::where('exam_id', $examId)->findOrFail($questionId);

            DB::transaction(function () use ($question, $exam) {
                $question->delete();
                
                // Update exam question count
                $exam->update([
                    'number_of_questions' => $exam->examQuestions()->count()
                ]);
            });

            return redirect()->route('admin.exams.questions.index', $examId)
                ->with('success', 'Question deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Question deletion failed', [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the question.');
        }
    }

    /**
     * Process answers data
     */
    protected function processAnswers(ExamQuestions $question, array $answersData, string $questionType, $correctAnswer = null)
    {
        if (count($answersData) < 2) {
            throw new \Exception("Question must have at least 2 answer options.");
        }

        $hasCorrectAnswer = false;

        foreach ($answersData as $index => $answerData) {
            // Validate answer data
            if (empty($answerData['text_en']) || empty($answerData['text_ar'])) {
                throw new \Exception("Answer option " . ($index + 1) . " is missing required text.");
            }

            $isCorrect = false;

            // Determine if this answer is correct based on question type
            if ($questionType === 'single_choice') {
                $isCorrect = $correctAnswer == $index;
            } elseif ($questionType === 'multiple_choice') {
                $isCorrect = isset($answerData['is_correct']) && $answerData['is_correct'];
            }

            if ($isCorrect) {
                $hasCorrectAnswer = true;
            }

            ExamQuestionAnswer::create([
                'id' => Str::uuid(),
                'exam_question_id' => $question->id,
                'answer' => $answerData['text_en'],
                'answer-ar' => $answerData['text_ar'],
                'is_correct' => $isCorrect,
                'reason' => $answerData['reason'] ?? null,
                'reason-ar' => $answerData['reason_ar'] ?? null,
            ]);
        }

        // Validate that at least one correct answer exists
        if (!$hasCorrectAnswer) {
            throw new \Exception("Question must have at least one correct answer.");
        }

        // For single choice, validate only one correct answer
        if ($questionType === 'single_choice') {
            $correctAnswers = collect($answersData)->filter(function ($answer, $index) use ($correctAnswer) {
                return $correctAnswer == $index;
            });

            if ($correctAnswers->count() !== 1) {
                throw new \Exception("Single choice questions must have exactly one correct answer.");
            }
        }
    }
}
