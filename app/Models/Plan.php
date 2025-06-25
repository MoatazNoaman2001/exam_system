<?php

namespace App\Models;

use App\Models\User;
use App\Models\PlanSchedule;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $fillable = [
        'user_id', 'plan_type', 'start_date', 'end_date', 'custom_date'
    ];

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
