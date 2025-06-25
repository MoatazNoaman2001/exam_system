<?php

namespace App\Models;


use App\Models\Exam;
use App\Models\Plan;
use App\Models\User;
use App\Models\Slide;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanSchedule extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $fillable = [
        'plan_id', 'slide_id', 'exam_id', 'scheduled_date', 'status', 'completed_at'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    public function slide()
    {
        return $this->belongsTo(Slide::class, 'slide_id', 'id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }
}
