<?php

namespace App\Models;

use App\Models\SlideAttempt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slide extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    protected $fillable = [
        'text',
        'content',
        'domain_id',
        'chapter_id',
    ];

    protected $casts = [
        'domain_id' => 'string',
        'chapter_id' => 'string',
    ];

    /**
     * Get the domain that owns the slide.
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Get the chapter that owns the slide.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the tests for the slide.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function slideAttempts() : HasMany {
        return $this->hasMany(SlideAttempt::class);
    }

    /**
     * Get the PDF URL for the slide.
     */
    public function getPdfUrlAttribute(): ?string
    {
        if ($this->content && Storage::disk('public')->exists($this->content)) {
            return Storage::disk('public')->url($this->content);
        }
        
        return null;
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFileSizeAttribute(): ?string
    {
        if ($this->content && Storage::disk('public')->exists($this->content)) {
            $bytes = Storage::disk('public')->size($this->content);
            return $this->formatBytes($bytes);
        }
        
        return null;
    }

    /**
     * Check if slide has PDF content.
     */
    public function hasPdf(): bool
    {
        return $this->content && Storage::disk('public')->exists($this->content);
    }

    /**
     * Check if slide has questions.
     */
    public function hasQuestions(): bool
    {
        return $this->tests()->exists();
    }

    /**
     * Get the number of questions for this slide.
     */
    public function getQuestionsCountAttribute(): int
    {
        return $this->tests()->count();
    }

    /**
     * Get questions with answers.
     */
    public function getQuestionsWithAnswers()
    {
        return $this->tests()->with('answers')->get();
    }

    /**
     * Scope to get slides with questions.
     */
    public function scopeWithQuestions($query)
    {
        return $query->whereHas('tests');
    }

    /**
     * Scope to get slides by domain.
     */
    public function scopeByDomain($query, $domainId)
    {
        return $query->where('domain_id', $domainId);
    }

    /**
     * Scope to get slides by chapter.
     */
    public function scopeByChapter($query, $chapterId)
    {
        return $query->where('chapter_id', $chapterId);
    }

    /**
     * Delete slide and associated files.
     */
    protected static function booted()
    {
        static::deleting(function ($slide) {
            // Delete PDF file when slide is deleted
            if ($slide->content && Storage::disk('public')->exists($slide->content)) {
                Storage::disk('public')->delete($slide->content);
            }
        });
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get slide title (alias for text field).
     */
    public function getTitleAttribute(): string
    {
        return $this->text;
    }
}