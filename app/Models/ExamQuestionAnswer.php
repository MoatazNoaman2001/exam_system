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

    public function examQuestion()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }

    /**
     * Scope for correct answers
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope for incorrect answers
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Check if this answer has explanation
     */
    public function hasReason()
    {
        return !empty($this->reason) || !empty($this->{'reason-ar'});
    }

    /**
     * Get reason in preferred language
     */
    public function getReasonInLanguage($language = 'en')
    {
        if ($language === 'ar') {
            return $this->{'reason-ar'} ?: $this->reason;
        }
        return $this->reason ?: $this->{'reason-ar'};
    }

    /**
     * Get answer text in preferred language
     */
    public function getAnswerInLanguage($language = 'en')
    {
        if ($language === 'ar') {
            return $this->{'answer-ar'} ?: $this->answer;
        }
        return $this->answer ?: $this->{'answer-ar'};
    }
}
?>