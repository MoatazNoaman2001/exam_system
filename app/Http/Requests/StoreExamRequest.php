<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic exam information
            'title_en' => [
                'required',
                'string',
                'max:500',
                'min:3'
            ],
            'title_ar' => [
                'required',
                'string',
                'max:500',
                'min:3'
            ],
            'description_en' => [
                'nullable',
                'string',
                'max:65535'
            ],
            'description_ar' => [
                'nullable',
                'string',
                'max:65535'
            ],
            
            // Exam settings
            'duration' => [
                'required',
                'integer',
                'min:1',
                'max:480' // Maximum 8 hours in minutes
            ],
            
            // Questions validation
            'questions' => [
                'required',
                'array',
                'min:1',
                'max:100'
            ],
            'questions.*.text_en' => [
                'required',
                'string',
                'max:65535',
                'min:10'
            ],
            'questions.*.text_ar' => [
                'required',
                'string',
                'max:65535',
                'min:10'
            ],
            'questions.*.type' => [
                'required',
                Rule::in(['single_choice', 'multiple_choice'])
            ],
            'questions.*.points' => [
                'required',
                'integer',
                'min:1',
                'max:100'
            ],
            
            // Options validation
            'questions.*.options' => [
                'required',
                'array',
                'min:2',
                'max:10'
            ],
            'questions.*.options.*.text_en' => [
                'required',
                'string',
                'max:65535',
                'min:1'
            ],
            'questions.*.options.*.text_ar' => [
                'required',
                'string',
                'max:65535',
                'min:1'
            ],
            'questions.*.options.*.reason' => [
                'nullable',
                'string',
                'max:65535'
            ],
            'questions.*.options.*.reason_ar' => [
                'nullable',
                'string',
                'max:65535'
            ],
            
            // Correct answer validation for single choice
            'questions.*.correct_answer' => [
                'required_if:questions.*.type,single_choice',
                'integer',
                'min:0'
            ],
            
            // Correct answer validation for multiple choice
            'questions.*.options.*.is_correct' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            // Basic exam messages
            'title_en.required' => 'English title is required.',
            'title_en.min' => 'English title must be at least 3 characters.',
            'title_en.max' => 'English title cannot exceed 500 characters.',
            'title_ar.required' => 'Arabic title is required.',
            'title_ar.min' => 'Arabic title must be at least 3 characters.',
            'title_ar.max' => 'Arabic title cannot exceed 500 characters.',
            
            'duration.required' => 'Exam duration is required.',
            'duration.min' => 'Exam duration must be at least 1 minute.',
            'duration.max' => 'Exam duration cannot exceed 8 hours (480 minutes).',
            
            // Questions messages
            'questions.required' => 'At least one question is required.',
            'questions.min' => 'Exam must have at least 1 question.',
            'questions.max' => 'Exam cannot have more than 100 questions.',
            
            'questions.*.text_en.required' => 'Question text in English is required.',
            'questions.*.text_en.min' => 'Question text must be at least 10 characters.',
            'questions.*.text_ar.required' => 'Question text in Arabic is required.',
            'questions.*.text_ar.min' => 'Question text must be at least 10 characters.',
            
            'questions.*.type.required' => 'Question type is required.',
            'questions.*.type.in' => 'Invalid question type selected.',
            
            'questions.*.points.required' => 'Question points are required.',
            'questions.*.points.min' => 'Question must have at least 1 point.',
            'questions.*.points.max' => 'Question cannot have more than 100 points.',
            
            // Options messages
            'questions.*.options.required' => 'Question options are required.',
            'questions.*.options.min' => 'Each question must have at least 2 options.',
            'questions.*.options.max' => 'Each question cannot have more than 10 options.',
            
            'questions.*.options.*.text_en.required' => 'Option text in English is required.',
            'questions.*.options.*.text_ar.required' => 'Option text in Arabic is required.',
            
            'questions.*.correct_answer.required_if' => 'Please select the correct answer for single choice questions.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateCorrectAnswers($validator);
            $this->validateUniqueOptions($validator);
            $this->validateQuestionNames($validator);
        });
    }

    /**
     * Validate correct answers for each question
     */
    protected function validateCorrectAnswers($validator)
    {
        $questions = $this->input('questions', []);

        foreach ($questions as $questionIndex => $question) {
            $questionNumber = $questionIndex + 1;
            $questionType = $question['type'] ?? null;
            $options = $question['options'] ?? [];

            if ($questionType === 'single_choice') {
                // Validate single choice correct answer
                $correctAnswer = $question['correct_answer'] ?? null;
                
                if ($correctAnswer === null) {
                    $validator->errors()->add(
                        "questions.{$questionIndex}.correct_answer",
                        "Question {$questionNumber}: Please select exactly one correct answer."
                    );
                } elseif ($correctAnswer >= count($options)) {
                    $validator->errors()->add(
                        "questions.{$questionIndex}.correct_answer",
                        "Question {$questionNumber}: Selected correct answer index is invalid."
                    );
                }
                
            } elseif ($questionType === 'multiple_choice') {
                // Validate multiple choice correct answers
                $hasCorrectAnswer = false;
                $correctCount = 0;
                
                foreach ($options as $optionIndex => $option) {
                    if (!empty($option['is_correct'])) {
                        $hasCorrectAnswer = true;
                        $correctCount++;
                    }
                }
                
                if (!$hasCorrectAnswer) {
                    $validator->errors()->add(
                        "questions.{$questionIndex}.options",
                        "Question {$questionNumber}: Please mark at least one option as correct."
                    );
                }
                
                // Warning if all options are correct (but allow it)
                if ($correctCount === count($options) && count($options) > 1) {
                    // This could be added as a warning system if implemented
                }
            }
        }
    }

    /**
     * Validate that options within each question are unique
     */
    protected function validateUniqueOptions($validator)
    {
        $questions = $this->input('questions', []);

        foreach ($questions as $questionIndex => $question) {
            $questionNumber = $questionIndex + 1;
            $options = $question['options'] ?? [];
            
            // Check for duplicate English options
            $englishTexts = array_column($options, 'text_en');
            $englishTexts = array_map('trim', $englishTexts);
            $englishTexts = array_map('strtolower', $englishTexts);
            
            if (count($englishTexts) !== count(array_unique($englishTexts))) {
                $validator->errors()->add(
                    "questions.{$questionIndex}.options",
                    "Question {$questionNumber}: Option texts in English must be unique."
                );
            }
            
            // Check for duplicate Arabic options
            $arabicTexts = array_column($options, 'text_ar');
            $arabicTexts = array_map('trim', $arabicTexts);
            
            if (count($arabicTexts) !== count(array_unique($arabicTexts))) {
                $validator->errors()->add(
                    "questions.{$questionIndex}.options",
                    "Question {$questionNumber}: Option texts in Arabic must be unique."
                );
            }
        }
    }

    /**
     * Validate form field names match expected structure
     */
    protected function validateQuestionNames($validator)
    {
        $questions = $this->input('questions', []);

        foreach ($questions as $questionIndex => $question) {
            $questionNumber = $questionIndex + 1;
            $questionType = $question['type'] ?? null;
            $options = $question['options'] ?? [];

            // Validate that correct answer naming is consistent with question type
            if ($questionType === 'single_choice') {
                // For single choice, we should have correct_answer field
                if (!isset($question['correct_answer']) && count($options) > 0) {
                    $validator->errors()->add(
                        "questions.{$questionIndex}.correct_answer",
                        "Question {$questionNumber}: Single choice questions must have a correct_answer field."
                    );
                }
            } elseif ($questionType === 'multiple_choice') {
                // For multiple choice, we should have is_correct in options
                $hasIsCorrectFields = false;
                foreach ($options as $option) {
                    if (array_key_exists('is_correct', $option)) {
                        $hasIsCorrectFields = true;
                        break;
                    }
                }
                
                if (!$hasIsCorrectFields && count($options) > 0) {
                    $validator->errors()->add(
                        "questions.{$questionIndex}.options",
                        "Question {$questionNumber}: Multiple choice questions must have is_correct fields in options."
                    );
                }
            }
        }
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Clean up the questions data
        $questions = $this->input('questions', []);
        
        foreach ($questions as $questionIndex => $question) {
            // Ensure correct_answer is integer for single choice
            if (isset($question['correct_answer']) && $question['type'] === 'single_choice') {
                $questions[$questionIndex]['correct_answer'] = (int) $question['correct_answer'];
            }
            
            // Ensure is_correct is boolean for multiple choice options
            if (isset($question['options']) && $question['type'] === 'multiple_choice') {
                foreach ($question['options'] as $optionIndex => $option) {
                    if (isset($option['is_correct'])) {
                        $questions[$questionIndex]['options'][$optionIndex]['is_correct'] = 
                            filter_var($option['is_correct'], FILTER_VALIDATE_BOOLEAN);
                    }
                }
            }
        }
        
        $this->merge(['questions' => $questions]);
    }
}