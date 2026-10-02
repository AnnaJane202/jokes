<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserBanned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $bannedBy;
    public $reason;
    public $duration;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, ?User $bannedBy = null, ?string $reason = null)
    {
        $this->user = $user;
        $this->bannedBy = $bannedBy;
        $this->reason = $reason;
        $this->duration = $user->banned_until
            ? $user->banned_until->diffForHumans()
            : 'навсегда';

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
