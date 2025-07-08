<?php

namespace App\Models;

use App\Models\User;
use App\Models\PlanSchedule;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'plan_type', 'start_date', 'end_date', 'custom_date'
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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'custom_date' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class, 'plan_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany(PlanSchedule::class, 'plan_id', 'id');
    }
}
