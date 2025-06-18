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

    public function questions()
    {
        return $this->hasMany(ExamQuestions::class);
    }
}