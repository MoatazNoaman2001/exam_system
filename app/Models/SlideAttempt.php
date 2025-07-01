<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlideAttempt extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['slide_id', 'user_id', 'start_date', 'end_date'];

    public function slide()
    {
        return $this->belongsTo(Slide::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}