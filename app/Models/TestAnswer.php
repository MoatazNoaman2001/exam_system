<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TestAnswer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['text', 'is_correct', 'test_id'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}