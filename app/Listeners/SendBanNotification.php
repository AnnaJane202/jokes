<?php

namespace App\Listeners;

use App\Events\UserBanned;
use App\Notifications\UserBannedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBanNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserBanned $event): void
    {
        $event->user->notify(new UserBannedNotification(
            $event->reason,
            $event->duration,
            $event->bannedBy
        ));
    }
}
