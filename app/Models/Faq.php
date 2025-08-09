<?php
// app/Models/Faq.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'order',
        'is_active'
    ];

    protected $casts = [
        'question' => 'array',
        'answer' => 'array',
        'is_active' => 'boolean'
    ];

    // Scope for active FAQs
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered FAQs
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    // Get localized question
    public function getLocalizedQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->question[$locale] ?? $this->question['en'] ?? '';
    }

    // Get localized answer
    public function getLocalizedAnswerAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->answer[$locale] ?? $this->answer['en'] ?? '';
    }

    // Set question for specific locale
    public function setQuestionForLocale(string $locale, string $question): void
    {
        $questions = $this->question ?? [];
        $questions[$locale] = $question;
        $this->question = $questions;
    }

    // Set answer for specific locale
    public function setAnswerForLocale(string $locale, string $answer): void
    {
        $answers = $this->answer ?? [];
        $answers[$locale] = $answer;
        $this->answer = $answers;
    }
}