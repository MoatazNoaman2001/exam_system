<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_en' => 'required|string|max:500',
            'question_ar' => 'nullable|string|max:500',
            'answer_en' => 'required|string|max:2000',
            'answer_ar' => 'nullable|string|max:2000',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'question_en.required' => 'The English question is required.',
            'answer_en.required' => 'The English answer is required.',
            'question_en.max' => 'The English question must not exceed 500 characters.',
            'answer_en.max' => 'The English answer must not exceed 2000 characters.',
        ];
    }
}
