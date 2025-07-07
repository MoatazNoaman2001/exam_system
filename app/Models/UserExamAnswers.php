<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserExamAnswers extends Model
{
    use HasUuids;
    protected $fillable = [
        'exam_session_id', 'exam_question_id', 'selected_answers', 'is_correct', 'time_spent'
    ];

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }
    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }
}
