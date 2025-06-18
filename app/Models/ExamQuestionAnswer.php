<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestionAnswer extends Model
{
    protected $table = 'question_exam_answer';
    protected $fillable = [
        'answer',
        'answer-ar',
        'is_correct',
        'exam_question_id',
    ];
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }
}
