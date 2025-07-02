<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
        $rules = [
            'text' => 'required|string|max:255',
            'domain_id' => 'nullable|exists:domains,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'questions' => 'nullable|array',
            'questions.*.question_ar' => 'required_with:questions|string|max:1000',
            'questions.*.question_en' => 'required_with:questions|string|max:1000',
            'questions.*.answers' => 'required_with:questions|array|min:2|max:6',
            'questions.*.answers.*.text_ar' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.answers.*.text_en' => 'required_with:questions.*.answers|string|max:255',
            'questions.*.correct_answer' => 'required_with:questions|integer|min:0',
        ];

        // Add content validation based on request method
        if ($this->isMethod('post')) {
            $rules['content'] = 'required|file|mimes:pdf|max:5120';
        } else {
            $rules['content'] = 'nullable|file|mimes:pdf|max:5120';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'text.required' => 'Slide title is required.',
            'text.max' => 'Slide title cannot exceed 255 characters.',
            'content.required' => 'PDF file is required.',
            'content.file' => 'Content must be a valid file.',
            'content.mimes' => 'File must be a PDF.',
            'content.max' => 'File size must not exceed 5MB.',
            'domain_id.exists' => 'Selected domain does not exist.',
            'chapter_id.exists' => 'Selected chapter does not exist.',
            
            // Question validation messages
            'questions.array' => 'Questions must be provided as an array.',
            'questions.*.question_ar.required_with' => 'Question in Arabic is required when adding questions.',
            'questions.*.question_ar.max' => 'Question in Arabic cannot exceed 1000 characters.',
            'questions.*.question_en.required_with' => 'Question in English is required when adding questions.',
            'questions.*.question_en.max' => 'Question in English cannot exceed 1000 characters.',
            
            // Answer validation messages
            'questions.*.answers.required_with' => 'Each question must have answers.',
            'questions.*.answers.array' => 'Answers must be provided as an array.',
            'questions.*.answers.min' => 'Each question must have at least 2 answers.',
            'questions.*.answers.max' => 'Each question cannot have more than 6 answers.',
            'questions.*.answers.*.text_ar.required_with' => 'Answer text in Arabic is required.',
            'questions.*.answers.*.text_ar.max' => 'Answer text in Arabic cannot exceed 255 characters.',
            'questions.*.answers.*.text_en.required_with' => 'Answer text in English is required.',
            'questions.*.answers.*.text_en.max' => 'Answer text in English cannot exceed 255 characters.',
            'questions.*.correct_answer.required_with' => 'Please select the correct answer for each question.',
            'questions.*.correct_answer.integer' => 'Correct answer must be a valid number.',
            'questions.*.correct_answer.min' => 'Correct answer index must be valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'text' => 'slide title',
            'content' => 'PDF file',
            'domain_id' => 'domain',
            'chapter_id' => 'chapter',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean up questions data if needed
        if ($this->has('questions') && is_array($this->questions)) {
            $questions = [];
            foreach ($this->questions as $index => $question) {
                // Only include questions that have content
                if (!empty($question['question_ar']) || !empty($question['question_en'])) {
                    $questions[$index] = $question;
                }
            }
            $this->merge(['questions' => $questions]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Custom validation for questions
            if ($this->has('questions') && is_array($this->questions)) {
                foreach ($this->questions as $index => $question) {
                    // Validate correct answer index
                    if (isset($question['correct_answer']) && isset($question['answers'])) {
                        $correctIndex = (int) $question['correct_answer'];
                        if (!isset($question['answers'][$correctIndex])) {
                            $validator->errors()->add(
                                "questions.{$index}.correct_answer", 
                                'The selected correct answer is invalid.'
                            );
                        }
                    }

                    // Ensure at least one correct answer is selected
                    if (isset($question['answers']) && count($question['answers']) > 0) {
                        if (!isset($question['correct_answer']) || $question['correct_answer'] === '') {
                            $validator->errors()->add(
                                "questions.{$index}.correct_answer", 
                                'Please select a correct answer for this question.'
                            );
                        }
                    }
                }
            }

            // If domain is selected, ensure at least one question is provided
            if ($this->domain_id && (!$this->has('questions') || empty($this->questions))) {
                $validator->errors()->add(
                    'questions', 
                    'Please add at least one question when a domain is selected.'
                );
            }
        });
    }
}