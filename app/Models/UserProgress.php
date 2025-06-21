<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id', 'points', 'current_level', 'points_to_next_level', 'days_left',
        'plan_duration', 'plan_end_date', 'progress', 'domains_completed', 'domains_total',
        'lessons_completed', 'lessons_total', 'exams_completed', 'exams_total',
        'questions_completed', 'questions_total', 'lessons_milestone', 'questions_milestone',
        'streak_days'
    ];

    // إضافة العلاقة العكسية مع User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}