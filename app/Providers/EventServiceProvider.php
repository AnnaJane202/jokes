<?php

namespace App\Providers;

use App\Events\NewReportEvent;
use App\Events\UserBanned;
use App\Listeners\SendBanNotification;
use App\Listeners\SendReportNotification;
use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
//use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // События аутентификации Laravel
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Ваши кастомные события
        UserBanned::class => [
            SendBanNotification::class,
            // Можно добавить дополнительные слушатели:
            // \App\Listeners\LogBanAction::class,
            // \App\Listeners\NotifyAdmins::class,
            // \App\Listeners\ClearUserCache::class,
        ],

        NewReportEvent::class => [
            SendReportNotification::class,
        ],

        // Пример других событий которые могут понадобиться:
//        \App\Events\UserUnbanned::class => [
//            \App\Listeners\SendUnbanNotification::class,
//        ],

//        \App\Events\PostCreated::class => [
//            \App\Listeners\SendNewPostNotifications::class,
//        ],

//        \App\Events\CommentCreated::class => [
//            \App\Listeners\NotifyPostAuthor::class,
//        ],
    ];



    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
