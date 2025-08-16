<?php

namespace App\Models;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = ['text'];

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}