<?php

namespace App\Models;

use App\Models\ExamQuestions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'text',
        'description',
        'text-ar',
        'description-ar',
        'number_of_questions',
        'time',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'time' => 'integer',
        'number_of_questions' => 'integer'
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestions::class);
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestions::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function userSessions($userId)
    {
        return $this->sessions()->where('user_id', $userId);
    }

    public function getActiveSession($userId)
    {
        return $this->sessions()
            ->where('user_id', $userId)
            ->whereIn('status', ['in_progress', 'paused'])
            ->first();
    }


    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['text-ar'] : $this->text;
    }

    public function getDescriptionLocalizedAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['description-ar'] : $this->description;
    }

     /**
     * Get plan schedules
     */
    public function planSchedules()
    {
        return $this->hasMany(PlanSchedule::class, 'exam_id');
    }

    /**
     * Get user progress records
     */
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class, 'exam_id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
    /**
     * Calculate total marks for the exam
     */
    public function getTotalMarksAttribute()
    {
        return $this->examQuestions()->sum('marks');
    }

    /**
     * Get exam duration in hours
     */
    public function getDurationInHoursAttribute()
    {
        return round($this->time / 60, 2);
    }

    /**
     * Check if exam is active
     */
    public function getIsActiveAttribute()
    {
        return !$this->is_completed && !$this->deleted_at;
    }

    /**
     * Scope for active exams
     */
    public function scopeActive($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope for completed exams
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }
}