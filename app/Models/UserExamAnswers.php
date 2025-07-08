<?php

namespace App\Models;

use App\Models\ExamSession;
use App\Models\ExamQuestions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserExamAnswers extends Model
{
    use HasUuids;
    protected $fillable = [
        'exam_session_id', 'exam_question_id', 'selected_answers', 'is_correct', 'time_spent'
    ];
    protected $casts = [
        'selected_answers' => 'array',
        'is_correct' => 'boolean',
        'time_spent' => 'integer',
    ];

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }
    public function question()
    {
        return $this->belongsTo(ExamQuestions::class, 'exam_question_id');
    }
}
