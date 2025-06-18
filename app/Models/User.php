<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;    


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, SoftDeletes,HasUuids;
    use Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'phone',
        'image',
        'is_agree',
        'preferred_language',
        'verified',
        'reset_password_token',
        'reset_password_expires',
    ];

    protected $hidden = ['password', 'reset_password_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function getIsAdminAttribute(): bool
    {
       return $this->role === 'admin';
    }

    public function comparePassword(string $value): bool
    {
        return Hash::check($value, $this->password);
    }

    public function omitPassword(): array
    {
        return $this->only([
            'id',
            'username',
            'email',
            'role',
            'phone',
            'image',
            'is_agree',
            'preferred_language',
            'verified',
            'created_at',
            'updated_at',
        ]);
    }

    public function introAnswers()
    {
        return $this->hasMany(IntroAnswer::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function slideAttempts()
    {
        return $this->hasMany(SlideAttempt::class);
    }

    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->verified;
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'verified' => true,
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }
}