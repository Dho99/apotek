<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public User $user;
    private $links;
    private $level;
    private $linkPlaceholder;
    /**
     * Create a new event instance.
     */
    public function __construct(string $message, User $user, $links = null, $level = null, $linkPlaceholder = null)
    {
        $this->message = $message;
        $this->user = $user;
        $this->links = $links;
        $this->level = $level;
        $this->linkPlaceholder = $linkPlaceholder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('private.notif.'.$this->level),
            new Channel('public-notif'),
            // new PrivateChannel('private.notif.2'),
        ];
    }

    public function broadcastAs(){
        return 'notif-msg';
    }

    public function broadcastWith(){
        return [
            'message' => $this->message,
            'user' => $this->user->only(['nama','level']),
            'links' => $this->links,
            'level' => $this->level,
            'linkPlaceholder' => $this->linkPlaceholder,
        ];
    }
}
