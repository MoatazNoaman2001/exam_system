<?php

namespace App\Models;

use App\Models\TestAttempt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_ar',
        'question_en',
        'slide_id',
    ];

    protected $casts = [
        'slide_id' => 'string',
    ];

    /**
     * Get the slide that owns the test.
     */
    public function slide(): BelongsTo
    {
        return $this->belongsTo(Slide::class);
    }

    /**
     * Get the answers for the test.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(TestAnswer::class);
    }

    /**
     * Get the attempts for the test.
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(TestAttempt::class);
    }

    /**
     * Get the correct answers for the test.
     */
    public function correctAnswers(): HasMany
    {
        return $this->hasMany(TestAnswer::class)->where('is_correct', true);
    }

    /**
     * Get question based on current locale.
     */
    public function getQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"question_{$locale}"} ?? $this->question_en ?? '';
    }

    /**
     * Check if test has answers.
     */
    public function hasAnswers(): bool
    {
        return $this->answers()->exists();
    }

    /**
     * Check if test has correct answers.
     */
    public function hasCorrectAnswers(): bool
    {
        return $this->correctAnswers()->exists();
    }

    /**
     * Scope to get tests with answers.
     */
    public function scopeWithAnswers($query)
    {
        return $query->whereHas('answers');
    }

    /**
     * Scope to get tests by slide.
     */
    public function scopeBySlide($query, $slideId)
    {
        return $query->where('slide_id', $slideId);
    }

    public function testAttempts(){
        return $this->hasMany(TestAttempt::class);
    }
}