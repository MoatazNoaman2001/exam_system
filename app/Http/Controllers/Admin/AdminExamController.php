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

class AdminExamController extends Controller
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
     * Display a listing of exams
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');

            $exams = Exam::query()
                ->withCount(['examQuestions as questions_count'])
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('text', 'like', "%{$search}%")
                          ->orWhere('text-ar', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(15);

            return view('admin.exams.index', compact('exams', 'search'));
        } catch (\Exception $e) {
            Log::error('Error loading exams index', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error loading exams.');
        }
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        return view('admin.exams.create');
    }

    /**
     * Store a newly created exam
     */
    public function store(StoreExamRequest $request)
    {
        $exam = DB::transaction(function () use ($request) {
            // Create exam
            $exam = Exam::create([
                'id' => Str::uuid(),
                'text' => $request->title_en,
                'text-ar' => $request->title_ar,
                'description' => $request->description_en,
                'description-ar' => $request->description_ar,
                'time' => $request->duration,
                'number_of_questions' => count($request->questions ?? []),
                'is_completed' => false,
            ]);

            // Process questions
            if ($request->has('questions')) {
                $this->processQuestions($exam, $request->questions);
            }

            return $exam;
        });

        return redirect()->route('admin.exams', $exam->id)
            ->with('success', 'Exam created successfully!');
    }

    /**
     * Display the specified exam
     */
    public function show($examId)
    {
        try {
            $exam = Exam::with(['examQuestions.questionExamAnswers'])
                ->findOrFail($examId);

            return view('admin.exams.show', compact('exam'));
        } catch (\Exception $e) {
            Log::error('Error showing exam', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return redirect()->route('admin.exams.index')->with('error', 'Exam not found.');
        }
    }

    /**
     * Show the form for editing the specified exam
     */
    public function edit($examId)
    {
        try {
            $exam = Exam::with(['examQuestions.answers'])
                ->findOrFail($examId);

            // dd(strval($exam));
            return view('admin.exams.edit', compact('exam'));
        } catch (\Exception $e) {
            Log::error('Error loading exam for edit', ['exam_id' => $examId, 'error' => $e->getMessage()]);
            return redirect()->route('admin.exams')->with('error', 'Exam not found.');
        }
    }

    /**
     * Update the specified exam
     */
    public function update(UpdateExamRequest $request, $examId)
    {
        try {
            $exam = DB::transaction(function () use ($request, $examId) {
                $exam = Exam::findOrFail($examId);

                // Update exam basic info
                $exam->update([
                    'text' => $request->title_en,
                    'text-ar' => $request->title_ar,
                    'description' => $request->description_en,
                    'description-ar' => $request->description_ar,
                    'time' => $request->duration,
                    'number_of_questions' => count($request->questions ?? []),
                ]);

                // Update questions if provided
                if ($request->has('questions')) {
                    // Delete existing questions and answers
                    $exam->examQuestions()->delete();
                    
                    // Process new questions
                    $this->processQuestions($exam, $request->questions);
                }

                return $exam;
            });

            return redirect()->route('admin.exams.show', $exam->id)
                ->with('success', 'Exam updated successfully!');

        } catch (\Exception $e) {
            Log::error('Exam update failed', [
                'exam_id' => $examId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the exam: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified exam
     */
    public function destroy($examId)
    {
        try {
            $exam = Exam::findOrFail($examId);

            // Check if exam has sessions
            if (DB::table('exam_sessions')->where('exam_id', $exam->id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete exam that has student sessions.');
            }

            DB::transaction(function () use ($exam) {
                // Delete questions and answers (cascade)
                $exam->examQuestions()->delete();
                
                // Delete exam
                $exam->delete();
            });

            return redirect()->route('admin.exams.index')
                ->with('success', 'Exam deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Exam deletion failed', [
                'exam_id' => $examId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while deleting the exam.');
        }
    }

    /**
     * Process questions data
     */
    protected function processQuestions(Exam $exam, array $questionsData)
    {
        foreach ($questionsData as $index => $questionData) {

            // Validate basic question data
            if (empty($questionData['text_en']) || empty($questionData['text_ar'])) {
                throw new \Exception("Question " . ($index + 1) . " is missing required text.");
            }

            if (empty($questionData['type']) || !in_array($questionData['type'], ['single_choice', 'multiple_choice'])) {
                throw new \Exception("Question " . ($index + 1) . " has invalid type.");
            }

            // Create question
            $question = ExamQuestions::create([
                'id' => Str::uuid(),
                'exam_id' => $exam->id,
                'question' => $questionData['text_en'],
                'question-ar' => $questionData['text_ar'],
                'text-ar' => $questionData['text_ar'],
                'type' => $questionData['type'],
                'marks' => $questionData['points'] ?? 1,
            ]);

            // Process answers
            if (isset($questionData['options']) && is_array($questionData['options'])) {
                $this->processAnswers($question, $questionData['options'], $questionData['type'], $questionData['correct_answer'] ?? null);
            }
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

            // dd($answerData);

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