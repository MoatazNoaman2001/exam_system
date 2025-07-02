<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TestAnswer extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'text_ar',
        'text_en',
        'is_correct',
        'test_id',
    ];

    protected $casts = [
        'id' => 'string',
        'is_correct' => 'boolean',
        'test_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the test that owns the answer.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get text based on current locale.
     */
    public function getTextAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"text_{$locale}"} ?? $this->text_en ?? '';
    }

    /**
     * Scope to get correct answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope to get incorrect answers.
     */
    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    /**
     * Check if this is the correct answer.
     */
    public function isCorrect(): bool
    {
        return $this->is_correct;
    }
}