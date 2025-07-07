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
}