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
    protected $dates = ['plan_end_date'];

 
public function updateLevel()
{
    if ($this->points >= 1000) {
        $this->current_level = 'خبير';
        $this->points_to_next_level = 0;  
    } elseif ($this->points >= 500) {
        $this->current_level = 'متقدم';
        $this->points_to_next_level = 1000 - $this->points;
    } elseif ($this->points >= 150) {
        $this->current_level = 'متوسط';
        $this->points_to_next_level = 500 - $this->points;
    } else {
        $this->current_level = 'مبتدئ';
        $this->points_to_next_level = 150 - $this->points;
    }

    $this->save();
}


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}