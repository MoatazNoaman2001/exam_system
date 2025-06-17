<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExamQuestion extends Model
{
    use HasUuids;
    protected $fillable = [
        'question',
        'type',
        'marks',
        'exam_id',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

}