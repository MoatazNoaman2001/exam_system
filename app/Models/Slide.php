<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Slide extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['text', 'content', 'chapter_id', 'domain_id'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function slideAttempts()
    {
        return $this->hasMany(SlideAttempt::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}