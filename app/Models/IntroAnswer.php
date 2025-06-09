<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question_id', 'selection_id', 'extra_text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function introQuestion()
    {
        return $this->belongsTo(IntroQuestion::class, 'question_id');
    }

    public function introSelection()
    {
        return $this->belongsTo(IntroSelection::class, 'selection_id');
    }
}