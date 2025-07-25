<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExamQuestions extends Model
{
    use HasUuids;
    protected $fillable = [
        'question',
        'question-ar',
        'text-ar',
        'type',
        'marks',
        'exam_id',
    ];

    protected $casts = [
        'type' => 'string',
        'marks' => 'integer'
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamQuestionAnswer::class, 'exam_question_id');
    }
    public function correctAnswers()
    {
        return $this->answers()->where('is_correct', true);
    }

    public function incorrectAnswers()
    {
        return $this->answers()->where('is_correct', false);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserExamAnswer::class);
    }

    public function getQuestionTextAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['question-ar'] : $this->question;
    }

    public function hasCorrectAnswer()
    {
        return $this->correctAnswers()->exists();
    }

    /**
     * Get the correct answer for single choice questions
     */
    public function getSingleCorrectAnswerAttribute()
    {
        if ($this->type === self::TYPE_SINGLE_CHOICE) {
            return $this->correctAnswers()->first();
        }
        return null;
    }

    /**
     * Get all correct answers for multiple choice questions
     */
    public function getMultipleCorrectAnswersAttribute()
    {
        if ($this->type === self::TYPE_MULTIPLE_CHOICE) {
            return $this->correctAnswers()->get();
        }
        return collect([]);
    }

    /**
     * Check if this is a single choice question
     */
    public function isSingleChoice()
    {
        return $this->type === self::TYPE_SINGLE_CHOICE;
    }

    /**
     * Check if this is a multiple choice question
     */
    public function isMultipleChoice()
    {
        return $this->type === self::TYPE_MULTIPLE_CHOICE;
    }

    /**
     * Check if this is a true/false question
     */
    public function isTrueFalse()
    {
        return $this->type === self::TYPE_TRUE_FALSE;
    }

    /**
     * Scope for single choice questions
     */
    public function scopeSingleChoice($query)
    {
        return $query->where('type', self::TYPE_SINGLE_CHOICE);
    }

    /**
     * Scope for multiple choice questions
     */
    public function scopeMultipleChoice($query)
    {
        return $query->where('type', self::TYPE_MULTIPLE_CHOICE);
    }

    /**
     * Scope for true/false questions
     */
    public function scopeTrueFalse($query)
    {
        return $query->where('type', self::TYPE_TRUE_FALSE);
    }
}