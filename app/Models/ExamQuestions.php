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

    public function userAnswers()
    {
        return $this->hasMany(UserExamAnswer::class);
    }

    public function getQuestionTextAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['question-ar'] : $this->question;
    }
}