<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['text', 'subtext', 'is_seen', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}