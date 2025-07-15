<?php

namespace App\Models;

use App\Events\NotificationSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['text',"subtext", 'is_seen', 'user_id'];
    protected $casts = [
        'is_seen' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = \Illuminate\Support\Str::uuid()->toString();
            }
        });

    static::created(function ($model) {
            event(new NotificationSent($model));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}