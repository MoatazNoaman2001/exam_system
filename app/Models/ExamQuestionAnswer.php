<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamQuestionAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'question_exam_answer';
    
    protected $fillable = [
        'answer',
        'answer-ar',
        'reason',
        'reason-ar',
        'is_correct',
        'exam_question_id',
    ];
    
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(ExamQuestions::class, 'exam_question_id');
    }

    public function getAnswerTextAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['answer-ar'] : $this->answer;
    }

    public function getReasonTextAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['reason-ar'] : $this->reason;
    }
}
?>