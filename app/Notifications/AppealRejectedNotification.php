<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppealRejectedNotification extends Notification
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
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('ℹ️ Решение по вашей апелляции')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы рассмотрели вашу апелляцию на нарушение от ' .
                $this->violation->created_at->format('d.m.Y'))
            ->line('**Тип нарушения:** ' . $this->violation->getTypeLabel())
            ->line('**Решение:** Апелляция ОТКЛОНЕНА')
            ->line('Наказание оставлено в силе.');

        if ($this->violation->active_until) {
            $mail->line('**Блокировка действует до:** ' . $this->violation->active_until->format('d.m.Y'));
        }

        if ($this->comment) {
            $mail->line('**Комментарий модератора:**')
                ->line($this->comment);
        }

        return $mail->action('Посмотреть детали', route('client.violations.index', $this->violation->id))
            ->line('Это окончательное решение модерации.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'violation_id' => $this->violation->id,
            'type' => 'appeal_rejected',
            'message' => 'Ваша апелляция отклонена. Наказание оставлено в силе.',
            'comment' => $this->comment,
            'action_url' => route('client.violations.index', $this->violation->id),
        ];
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
}
