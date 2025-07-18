<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Chapter extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['text'];

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }
}