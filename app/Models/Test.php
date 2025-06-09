<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Test extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['question', 'slide_id'];

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }

    public function testAnswers()
    {
        return $this->hasMany(TestAnswer::class);
    }

    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }
}