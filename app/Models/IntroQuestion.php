<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class IntroQuestion extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['text', 'exam_id'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

       public function introSelections()
{
    return $this->hasMany(IntroSelection::class, 'question_id');
}

    public function introAnswers()
    {
        return $this->hasMany(IntroAnswer::class);
    }
}