<?php
// app/Models/Contact.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'admin_reply',
        'replied_at',
        'replied_by'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'replied_by' => 'string' // Cast UUID as string
    ];

    // Relationship with User (admin who replied)
    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    // Scopes
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->where('status', 'read');
    }

    public function scopeReplied(Builder $query): Builder
    {
        return $query->where('status', 'replied');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Mark as read
    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }

    // Mark as replied
    public function markAsReplied(string $reply, string $adminId): void
    {
        $this->update([
            'status' => 'replied',
            'admin_reply' => $reply,
            'replied_at' => now(),
            'replied_by' => $adminId
        ]);
    }

    // Check if contact is new (unread)
    public function isNew(): bool
    {
        return $this->status === 'unread';
    }

    // Get status badge class for UI
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'unread' => 'badge-danger',
            'read' => 'badge-warning',
            'replied' => 'badge-success',
            default => 'badge-secondary'
        };
    }
}