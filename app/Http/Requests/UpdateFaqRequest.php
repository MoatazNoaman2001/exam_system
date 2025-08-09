<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
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
}
