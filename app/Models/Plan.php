<?php

namespace App\Models;

use App\Models\User;
use App\Models\PlanSchedule;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;


use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'certificate_id',
        'plan_type',
        'start_date',
        'end_date',
        'custom_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'custom_date' => 'date',
    ];

    // Define the composite unique rule
    public static function rules()
    {
        return [
            'user_id' => 'required',
            'certificate_id' => 'required',
            'plan_type' => 'required|in:6_weeks,10_weeks,custom',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
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
