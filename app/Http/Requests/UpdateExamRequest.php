<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
            
            'questions' => 'required|array|min:1|max:50',
            'questions.*.id' => 'nullable|integer',
            'questions.*.question' => 'required|string|max:1000',
            'questions.*.question-ar' => 'required|string|max:1000',
            'questions.*.type' => 'required|string|in:single_choice,multiple_choice,true_false',
            'questions.*.marks' => 'required|integer|min:1|max:100',
            
            // More flexible answer validation
            'questions.*.answers' => 'required|array|min:1|max:10',
            'questions.*.answers.*.id' => 'nullable|integer',
            'questions.*.answers.*.answer' => 'required|string|max:500',
            'questions.*.answers.*.answer-ar' => 'required|string|max:500',
            'questions.*.answers.*.is_correct' => 'nullable|boolean',
            
            // For true/false questions
            'questions.*.correct_answer' => 'nullable|integer|min:0|max:9',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $questions = $this->input('questions', []);
            
            foreach ($questions as $index => $question) {
                $this->validateQuestionType($validator, $question, $index);
            }
        });
    }

    private function validateQuestionType($validator, $question, $index)
    {
        $type = $question['type'] ?? '';
        $answers = $question['answers'] ?? [];
        
        // Handle true/false special case
        if ($type === 'true_false') {
            $this->validateTrueFalse($validator, $question, $index);
            return;
        }
        
        // Validate regular choice questions
        $this->validateChoiceQuestions($validator, $question, $index, $answers);
    }

    private function validateTrueFalse($validator, $question, $index)
    {
        $answers = $question['answers'] ?? [];
        $correctAnswer = $question['correct_answer'] ?? null;
        
        // Check if we have exactly 2 answers
        if (count($answers) !== 2) {
            $validator->errors()->add(
                "questions.{$index}.answers",
                "True/False questions must have exactly 2 options."
            );
            return;
        }
        
        // Check if correct_answer is set
        if ($correctAnswer === null || !in_array($correctAnswer, [0, 1])) {
            $validator->errors()->add(
                "questions.{$index}.correct_answer",
                "True/False questions must have exactly one correct answer selected."
            );
            return;
        }
        
        // Validate True/False content
        $answerTexts = collect($answers)->pluck('answer')->map('strtolower');
        if (!$answerTexts->contains('true') || !$answerTexts->contains('false')) {
            $validator->errors()->add(
                "questions.{$index}.answers",
                "True/False questions must have 'True' and 'False' options."
            );
        }
    }

    private function validateChoiceQuestions($validator, $question, $index, $answers)
    {
        $type = $question['type'];
        
        // Count correct answers - handle both checkbox (1) and boolean (true) values
        $correctCount = collect($answers)->filter(function($answer) {
            $isCorrect = $answer['is_correct'] ?? false;
            return $isCorrect === true || $isCorrect === 1 || $isCorrect === '1';
        })->count();

        // Minimum answers check
        if (count($answers) < 2) {
            $validator->errors()->add(
                "questions.{$index}.answers",
                ucfirst($type) . " questions must have at least 2 options."
            );
            return;
        }

        // Type-specific validation
        switch ($type) {
            case 'single_choice':
                if ($correctCount !== 1) {
                    $validator->errors()->add(
                        "questions.{$index}.answers",
                        "Single choice questions must have exactly one correct answer."
                    );
                }
                break;

            case 'multiple_choice':
                if ($correctCount < 1) {
                    $validator->errors()->add(
                        "questions.{$index}.answers",
                        "Multiple choice questions must have at least one correct answer."
                    );
                }
                if ($correctCount >= count($answers)) {
                    $validator->errors()->add(
                        "questions.{$index}.answers",
                        "Multiple choice questions cannot have all options as correct."
                    );
                }
                break;
        }

        // Check for duplicate answers
        $answerTexts = collect($answers)->pluck('answer')->filter();
        if ($answerTexts->count() !== $answerTexts->unique()->count()) {
            $validator->errors()->add(
                "questions.{$index}.answers",
                "Question options cannot have duplicate text."
            );
        }
    }

    public function messages()
    {
        return [
            'title_en.required' => 'English title is required.',
            'title_ar.required' => 'Arabic title is required.',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be at least 1 minute.',
            'duration.max' => 'Duration cannot exceed 300 minutes.',
            
            'questions.required' => 'At least one question is required.',
            'questions.min' => 'At least one question is required.',
            'questions.max' => 'Maximum 50 questions allowed.',
            
            'questions.*.question.required' => 'Question text in English is required.',
            'questions.*.question-ar.required' => 'Question text in Arabic is required.',
            'questions.*.type.required' => 'Question type is required.',
            'questions.*.type.in' => 'Invalid question type selected.',
            'questions.*.marks.required' => 'Question marks are required.',
            'questions.*.marks.min' => 'Question marks must be at least 1.',
            'questions.*.marks.max' => 'Question marks cannot exceed 100.',
            
            'questions.*.answers.required' => 'Answer options are required.',
            'questions.*.answers.min' => 'At least 1 answer option is required.',
            'questions.*.answers.max' => 'Maximum 10 answer options allowed.',
            
            'questions.*.answers.*.answer.required' => 'Answer text in English is required.',
            'questions.*.answers.*.answer-ar.required' => 'Answer text in Arabic is required.',
            
            'questions.*.correct_answer.required' => 'Please select the correct answer for True/False question.',
        ];
    }

    public function attributes()
    {
        return [
            'title_en' => 'English Title',
            'title_ar' => 'Arabic Title',
            'duration' => 'Duration',
            'questions.*.question' => 'Question Text (English)',
            'questions.*.question-ar' => 'Question Text (Arabic)',
            'questions.*.type' => 'Question Type',
            'questions.*.marks' => 'Question Marks',
            'questions.*.answers.*.answer' => 'Answer Text (English)',
            'questions.*.answers.*.answer-ar' => 'Answer Text (Arabic)',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $questions = $this->input('questions', []);
        
        foreach ($questions as $index => $question) {
            // Handle true/false questions - convert correct_answer to is_correct for answers
            if (($question['type'] ?? '') === 'true_false' && isset($question['correct_answer'])) {
                $correctAnswerIndex = (int) $question['correct_answer'];
                
                if (isset($question['answers']) && is_array($question['answers'])) {
                    foreach ($question['answers'] as $answerIndex => $answer) {
                        $questions[$index]['answers'][$answerIndex]['is_correct'] = ($answerIndex == $correctAnswerIndex);
                    }
                }
            }
            
            // Convert string boolean values to actual booleans for is_correct
            if (isset($question['answers']) && is_array($question['answers'])) {
                foreach ($question['answers'] as $answerIndex => $answer) {
                    if (isset($answer['is_correct'])) {
                        $isCorrect = $answer['is_correct'];
                        if ($isCorrect === '1' || $isCorrect === 1 || $isCorrect === 'true') {
                            $questions[$index]['answers'][$answerIndex]['is_correct'] = true;
                        } else {
                            $questions[$index]['answers'][$answerIndex]['is_correct'] = false;
                        }
                    } else {
                        $questions[$index]['answers'][$answerIndex]['is_correct'] = false;
                    }
                }
            }
        }
        
        $this->merge(['questions' => $questions]);
    }

    /**
     * Get the validation rules that apply to the request based on question type.
     */
    public function getRulesForQuestionType($questionType)
    {
        switch ($questionType) {
            case 'true_false':
                return [
                    'answers' => 'required|array|size:2',
                    'correct_answer' => 'required|integer|in:0,1',
                ];
            case 'single_choice':
                return [
                    'answers' => 'required|array|min:2|max:10',
                    'answers.*.is_correct' => 'required|boolean',
                ];
            case 'multiple_choice':
                return [
                    'answers' => 'required|array|min:2|max:10',
                    'answers.*.is_correct' => 'required|boolean',
                ];
            default:
                return [];
        }
    }
}