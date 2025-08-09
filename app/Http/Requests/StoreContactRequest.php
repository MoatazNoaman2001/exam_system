<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|in:General Inquiry,Technical Support,Billing Question,Feedback,Other',
            'message' => 'required|string|max:2000|min:10'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'name']),
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.email' => __('validation.email'),
            'subject.required' => __('validation.required', ['attribute' => 'subject']),
            'subject.in' => __('validation.in', ['attribute' => 'subject']),
            'message.required' => __('validation.required', ['attribute' => 'message']),
            'message.min' => __('validation.min.string', ['attribute' => 'message', 'min' => 10]),
            'message.max' => __('validation.max.string', ['attribute' => 'message', 'max' => 2000]),
        ];
    }
}
