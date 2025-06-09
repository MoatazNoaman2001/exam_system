<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Quiz extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['question', 'slide_id'];

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}