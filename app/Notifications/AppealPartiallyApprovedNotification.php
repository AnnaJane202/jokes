<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppealPartiallyApprovedNotification extends Notification
{
    use Queueable;

    protected Violation $violation;
    protected ?string $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Violation $violation, ?string $comment = null)
    {
        $this->violation = $violation;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('🔄 Наказание изменено по результатам апелляции')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы рассмотрели вашу апелляцию и приняли решение смягчить наказание.')
            ->line('**Новое наказание:** ' . $this->violation->getPenaltyLabel());

        if ($this->violation->duration_days) {
            $mail->line('**Срок блокировки:** ' . $this->violation->duration_days . ' дней');
            $mail->line('**Окончание блокировки:** ' . $this->violation->active_until?->format('d.m.Y'));
        }

        if ($this->comment) {
            $mail->line('**Комментарий модератора:**')
                ->line($this->comment);
        }

        return $mail->action('Посмотреть детали', route('client.violations.index', $this->violation->id));
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

    public function toDatabase($notifiable)
    {
        return [
            'violation_id' => $this->violation->id,
            'type' => 'appeal_modified',
            'message' => 'Наказание изменено: ' . $this->violation->getPenaltyLabel(),
//            'comment' => $this->comment,
            'action_url' => route('client.violations.index', $this->violation->id),
        ];
    }
}
