<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class UserBannedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ?string $reason = null,
        public ?int $duration = null,
        public ?User $bannedBy = null
    )
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        Log::info('Определение каналов для уведомления', [
            'user_id' => $notifiable->id,
        ]);
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('Создание уведомления mail', [
            'user_id' => $notifiable->id,
        ]);
        return (new MailMessage)
                    ->line('Причина: ' . ($this->reason ?: 'не указана'))
//                    ->action('Notification Action', url('/'))
                    ->subject('Ваш аккаунт заблокирован');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        Log::info('Создание уведомления для БД', [
            'user_id' => $notifiable->id,
        ]);
        return [
            'message' => 'Вы заблокированы',
            'reason' => $this->reason,
        ];
    }
}
