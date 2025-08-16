<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestions;
use App\Models\ExamQuestionAnswer;
use App\Imports\ExamsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of exams
     */
    public function index()
    {
        $exams = Exam::withCount('questions')->latest()->paginate(20);
        return view('admin.exams.index', compact('exams'));
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
    public function store(Request $request)
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

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    /**
     * Display the specified exam
     */
    public function show(Exam $exam)
    {
        $exam->load(['questions.answers']);
        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified exam
     */
    public function edit(Exam $exam)
    {
        // Load the exam with its questions and answers
        $exam->load(['questions' => function($query) {
            $query->with('answers');
        }]);
        
        return view('admin.exams.edit', compact('exam'));
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, Exam $exam)
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
            ->route('admin.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    /**
     * Remove the specified exam
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }

    /**
     * Import exams from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ExamsImport, $request->file('excel_file'));

            return redirect()->back()->with('success', 'Exam imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing exam: '.$e->getMessage());
        }
    }

    /**
     * Private helper method to update question answers
     */
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
}