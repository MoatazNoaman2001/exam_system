<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\ExamQuestions;
use App\Models\UserExamAnswers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExamSession extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id', 'exam_id', 'started_at', 'last_activity_at', 'completed_at',
        'total_time_spent', 'current_question_index', 'answered_questions',
        'question_order', 'score', 'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'completed_at' => 'datetime',
        'answered_questions' => 'array',
        'question_order' => 'array',
        'score' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserExamAnswers::class, 'exam_session_id');
    }

    public function getRemainingTimeAttribute()
    {
        if ($this->status === 'completed') {
            return 0;
        }

        $examDurationSeconds = $this->exam->time * 60;
        $elapsed = $this->total_time_spent;
        
        return max(0, $examDurationSeconds - $elapsed);
    }

    public function getProgressPercentageAttribute()
    {
        $totalQuestions = $this->exam->number_of_questions;
        $answeredCount = count($this->answered_questions ?? []);
        
        return $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100, 2) : 0;
    }

    public function isExpired()
    {
        return $this->remaining_time <= 0;
    }

    public function getCurrentQuestion()
    {
        if (!$this->question_order || $this->current_question_index >= count($this->question_order)) {
            return null;
        }

        $questionId = $this->question_order[$this->current_question_index];
        return ExamQuestions::find($questionId);
    }

    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }  
}
