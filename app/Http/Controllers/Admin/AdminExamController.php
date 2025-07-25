<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminExamController extends Controller
{
    /**
     * Display a listing of exams
     */
    public function exams()
    {
        $exams = Exam::withCount('questions')->latest()->paginate(20);
        return view('admin.exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function createExam()
    {
        return view('admin.exams.create');
    }

    /**
     * Store a newly created exam
     */
    public function storeExam(Request $request)
    {
        $validated = $this->validateExamRequest($request);

        DB::transaction(function () use ($request, $validated) {
            // Create the exam
            $exam = Exam::create([
                'text' => $validated['title_en'],
                'text-ar' => $validated['title_ar'],
                'description' => $validated['description_en'],
                'description-ar' => $validated['description_ar'],
                'number_of_questions' => count($validated['questions']),
                'time' => $validated['duration'],
                'is_completed' => false,
            ]);

            // Create questions and their options
            foreach ($validated['questions'] as $questionData) {
                $question = ExamQuestions::create([
                    'question' => $questionData['text_en'],
                    'question-ar' => $questionData['text_ar'],
                    'text-ar' => '', // Legacy field
                    'type' => $questionData['type'],
                    'marks' => $questionData['points'],
                    'exam_id' => $exam->id,
                ]);

                // Create options/answers for the question
                foreach ($questionData['options'] as $optionData) {
                    ExamQuestionAnswer::create([
                        'exam_question_id' => $question->id,
                        'answer' => $optionData['text_en'],
                        'answer-ar' => $optionData['text_ar'],
                        'reason' => $optionData['reason'] ?? null,
                        'reason-ar' => $optionData['reason_ar'] ?? null,
                        'is_correct' => $this->determineCorrectAnswer($questionData, $optionData),
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.exams')
            ->with('success', 'Exam created successfully.');
        // try {
           

        // } catch (\Exception $e) {
        //     \Log::error('Exam creation failed: ' . $e->getMessage());
            
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->withErrors(['error' => 'Failed to create exam. Please try again.']);
        // }
    }

    /**
     * Show the form for editing an exam
     */
    public function editExam(Exam $exam)
    {
        $exam->load(['questions' => function($query) {
            $query->with('answers');
        }]);
        
        return view('admin.exams.edit', compact('exam'));
    }

    /**
     * Update the specified exam
     */
    public function updateExam(Request $request, Exam $exam)
    {
        $validated = $this->validateExamUpdateRequest($request);

        try {
            DB::transaction(function () use ($request, $exam, $validated) {
                // Update exam basic information
                $exam->update([
                    'text' => $validated['title_en'],
                    'text-ar' => $validated['title_ar'],
                    'description' => $validated['description_en'],
                    'description-ar' => $validated['description_ar'],
                    'time' => $validated['duration'],
                    'number_of_questions' => count($validated['questions']),
                ]);

                // Track existing questions for deletion
                $existingQuestionIds = $exam->questions->pluck('id')->toArray();
                $updatedQuestionIds = [];

                foreach ($validated['questions'] as $questionData) {
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

                    // Handle answers
                    $this->updateQuestionAnswers($question, $questionData['answers']);
                }

                // Delete removed questions (cascade will handle answers)
                $exam->questions()->whereNotIn('id', $updatedQuestionIds)->delete();
            });

            return redirect()
                ->route('admin.exams')
                ->with('success', 'Exam updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Exam update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update exam. Please try again.']);
        }
    }

    /**
     * Validate exam creation request
     */
    private function validateExamRequest(Request $request)
    {
        $rules = [
            'title_en' => 'required|string|max:500',  // Increased from 255
            'title_ar' => 'required|string|max:500',  // Increased from 255
            'description_en' => 'nullable|string|max:5000',  // Increased to handle long descriptions
            'description_ar' => 'nullable|string|max:5000',  // Increased to handle long descriptions
            'duration' => 'required|integer|min:1|max:300',
            'questions' => 'required|array|min:1',
            'questions.*.text_en' => 'required|string|max:2000',  // Increased for long questions
            'questions.*.text_ar' => 'required|string|max:2000',  // Increased for long questions
            'questions.*.type' => 'required|string|in:single_choice,multiple_choice',
            'questions.*.points' => 'required|integer|min:1|max:100',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text_en' => 'required|string|max:1000',  // Increased for long options
            'questions.*.options.*.text_ar' => 'required|string|max:1000',  // Increased for long options
            'questions.*.options.*.reason' => 'nullable|string|max:2000',   // New field for explanations
            'questions.*.options.*.reason_ar' => 'nullable|string|max:2000', // New field for explanations
        ];

        $messages = [
            'title_en.required' => 'English title is required.',
            'title_en.max' => 'English title cannot exceed 500 characters.',
            'title_ar.required' => 'Arabic title is required.',
            'title_ar.max' => 'Arabic title cannot exceed 500 characters.',
            'description_en.max' => 'English description cannot exceed 5000 characters.',
            'description_ar.max' => 'Arabic description cannot exceed 5000 characters.',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'duration.max' => 'Duration cannot exceed 300 minutes.',
            'questions.required' => 'At least one question is required.',
            'questions.min' => 'At least one question is required.',
            'questions.*.text_en.required' => 'Question text in English is required.',
            'questions.*.text_en.max' => 'Question text in English cannot exceed 2000 characters.',
            'questions.*.text_ar.required' => 'Question text in Arabic is required.',
            'questions.*.text_ar.max' => 'Question text in Arabic cannot exceed 2000 characters.',
            'questions.*.type.required' => 'Question type is required.',
            'questions.*.type.in' => 'Invalid question type.',
            'questions.*.points.required' => 'Question points are required.',
            'questions.*.points.min' => 'Question points must be at least 1.',
            'questions.*.options.required' => 'Question options are required.',
            'questions.*.options.min' => 'Each question must have at least 2 options.',
            'questions.*.options.*.text_en.required' => 'Option text in English is required.',
            'questions.*.options.*.text_en.max' => 'Option text in English cannot exceed 1000 characters.',
            'questions.*.options.*.text_ar.required' => 'Option text in Arabic is required.',
            'questions.*.options.*.text_ar.max' => 'Option text in Arabic cannot exceed 1000 characters.',
            'questions.*.options.*.reason.max' => 'Reason/explanation cannot exceed 2000 characters.',
            'questions.*.options.*.reason_ar.max' => 'Reason/explanation in Arabic cannot exceed 2000 characters.',
        ];

        $validated = $request->validate($rules, $messages);

        // Custom validation for correct answers
        $this->validateCorrectAnswers($request->questions);

        return $validated;
    }

    /**
     * Validate exam update request
     */
    private function validateExamUpdateRequest(Request $request)
    {
        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:exam_questions,id',
            'questions.*.question' => 'required|string|max:1000',
            'questions.*.question-ar' => 'required|string|max:1000',
            'questions.*.type' => 'required|string|in:single_choice,multiple_choice',
            'questions.*.marks' => 'required|integer|min:1|max:100',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.id' => 'nullable|exists:question_exam_answer,id',
            'questions.*.answers.*.answer' => 'required|string|max:500',
            'questions.*.answers.*.answer-ar' => 'required|string|max:500',
            'questions.*.answers.*.reason' => 'nullable|string|max:1000',
            'questions.*.answers.*.reason-ar' => 'nullable|string|max:1000',
            'questions.*.answers.*.is_correct' => 'boolean'
        ];

        return $request->validate($rules);
    }

    /**
     * Validate that each question has at least one correct answer
     */
    private function validateCorrectAnswers($questions)
    {
        foreach ($questions as $index => $questionData) {
            $hasCorrectAnswer = false;
            $correctCount = 0;

            foreach ($questionData['options'] as $option) {
                if (isset($option['is_correct']) && $option['is_correct']) {
                    $hasCorrectAnswer = true;
                    $correctCount++;
                }
            }

            if (!$hasCorrectAnswer) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []), 
                    ["questions.{$index}.options" => 'Each question must have at least one correct answer.']
                );
            }

            // For single choice, ensure only one correct answer
            if ($questionData['type'] === 'single_choice' && $correctCount > 1) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []), 
                    ["questions.{$index}.options" => 'Single choice questions must have exactly one correct answer.']
                );
            }
        }
    }

    /**
     * Determine if an option is correct based on question type
     */
    private function determineCorrectAnswer($questionData, $optionData)
    {
        if ($questionData['type'] === 'single_choice') {
            // For single choice, check if this option is the selected correct answer
            $correctAnswerIndex = $questionData['correct_answer'] ?? null;
            $currentOptionIndex = array_search($optionData, $questionData['options']);
            return $correctAnswerIndex !== null && $correctAnswerIndex == $currentOptionIndex;
        } else {
            // For multiple choice, check the is_correct flag directly
            return isset($optionData['is_correct']) && $optionData['is_correct'];
        }
    }

    /**
     * Update question answers
     */
    private function updateQuestionAnswers($question, $answersData)
    {
        // Get existing answer IDs for this question
        $existingAnswerIds = $question->answers->pluck('id')->toArray();
        $updatedAnswerIds = [];

        foreach ($answersData as $answerData) {
            // Create or update answer
            $answer = ExamQuestionAnswer::updateOrCreate(
                ['id' => $answerData['id'] ?? null],
                [
                    'exam_question_id' => $question->id,
                    'answer' => $answerData['answer'],
                    'answer-ar' => $answerData['answer-ar'],
                    'reason' => $answerData['reason'] ?? null,
                    'reason-ar' => $answerData['reason-ar'] ?? null,
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

    /**
     * Delete an exam
     */
    public function destroyExam(Exam $exam)
    {
        try {
            $exam->delete();
            
            return redirect()
                ->route('admin.exams')
                ->with('success', 'Exam deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to delete exam.']);
        }
    }
}