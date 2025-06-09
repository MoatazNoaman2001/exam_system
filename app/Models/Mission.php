<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Mission extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['text', 'is_completed', 'is_manually_added', 'user_id', 'due_date'];

    protected $dates = ['due_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}