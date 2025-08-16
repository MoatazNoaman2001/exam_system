<?php

namespace App\Models;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'text', 
        'description', 
        'description_ar',
        'certificate_id'
    ];

    public function slides()
    {
        return $this->hasMany(Slide::class);
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
    
    public function getLocalizedDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? ($this->description_ar ?: $this->description) : $this->description;
    }
}