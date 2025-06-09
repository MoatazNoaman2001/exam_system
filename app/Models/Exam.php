<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Exam extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'text',
        'description',
        'number_of_questions',
        'time',
        'is_completed',
    ];

    public function introQuestions()
    {
        return $this->hasMany(IntroQuestion::class);
    }
}