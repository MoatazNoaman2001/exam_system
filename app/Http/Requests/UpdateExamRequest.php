<?php

namespace App\Http\Requests\Admin;

class UpdateExamRequest extends StoreExamRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        $rules = parent::rules();
        
        return $rules;
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        $messages = parent::messages();
        
        $messages['title_en.required'] = 'English title is required for exam update.';
        $messages['title_ar.required'] = 'Arabic title is required for exam update.';
        
        return $messages;
    }
}