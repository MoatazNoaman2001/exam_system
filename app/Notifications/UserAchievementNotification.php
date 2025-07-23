<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserAchievementNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $type;
    public $text;
    public $subtext;

    public $broadcastType = null;

    public function __construct($type, $text, $subtext = null)
    {
        if (empty($text)) {
            throw new \InvalidArgumentException('Notification text cannot be empty.');
        }

        $this->type = $type;
        $this->text = $text;
        $this->subtext = $subtext;
    }

   public function via($notifiable)
{
    return ['custom_database', 'broadcast'];
}


  public function toDatabase($notifiable)
{
    if (empty($this->text)) {
        \Log::error('Notification text is empty in toDatabase');
        throw new \Exception('Notification text is empty.');
    }
    return [
        'id' => (string) \Str::uuid(),
        'user_id' => $notifiable->id,
        'text' => $this->text,
        'subtext' => $this->subtext,
        'is_seen' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => $this->type,
            'text' => $this->text,
            'subtext' => $this->subtext,
            'created_at' => now()->toDateTimeString(),
        ]);
    }
}
