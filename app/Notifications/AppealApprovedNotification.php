<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppealApprovedNotification extends Notification
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
    public function toMail(object $notifiable)
    {
        $mail = (new MailMessage)
            ->subject('✅ Ваша апелляция одобрена')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы рассмотрели вашу апелляцию на нарушение от ' .
                $this->violation->created_at->format('d.m.Y'))
            ->line('**Тип нарушения:** ' . $this->violation->getTypeLabel())
            ->line('**Решение:** Апелляция ОДОБРЕНА')
            ->line('Наказание снято, ваш аккаунт полностью восстановлен.');

        if ($this->comment) {
            $mail->line('**Комментарий модератора:**')
                ->line($this->comment);
        }

        return $mail->action('Посмотреть детали', route('client.violations.index', $this->violation->id))
            ->line('Спасибо за понимание!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'violation_id' => $this->violation->id,
            'type' => 'appeal_approved',
            'message' => 'Ваша апелляция одобрена. Наказание снято.',
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
