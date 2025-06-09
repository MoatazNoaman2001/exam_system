<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroSelection extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'has_extra_text', 'question_id'];

    public function introQuestion()
    {
        return $this->belongsTo(IntroQuestion::class, 'question_id');
    }

    public function introAnswers()
    {
        return $this->hasMany(IntroAnswer::class);
    }
}