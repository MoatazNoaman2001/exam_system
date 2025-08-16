<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Certificate extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'code',
        'icon',
        'color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the domains for this certificate
     */
    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get the chapters for this certificate
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Get the exams for this certificate
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the slides for this certificate (through domains and chapters)
     */
    public function slides()
    {
        return Slide::where(function($query) {
            $query->whereHas('domain', function($q) {
                $q->where('certificate_id', $this->id);
            })->orWhereHas('chapter', function($q) {
                $q->where('certificate_id', $this->id);
            });
        });
    }

    /**
     * Get the plans for this certificate
     */
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    /**
     * Get the user progress records for this certificate
     */
    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get the achievements for this certificate
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Get localized name
     */
    public function getLocalizedNameAttribute()
    {
        return app()->getLocale() === 'ar' ? ($this->name_ar ?: $this->name) : $this->name;
    }

    /**
     * Get localized description
     */
    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? ($this->description_ar ?: $this->description) : $this->description;
    }

    /**
     * Scope to get only active certificates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
} 