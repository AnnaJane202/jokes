<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppealDecidedNotification extends Notification
{
    use Queueable;

    protected Violation $violation;
    protected bool $approved;
    protected ?string $decisionReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Violation $violation, bool $approved, ?string $decisionReason = null)
    {
        $this->violation = $violation;
        $this->approved = $approved;
        $this->decisionReason = $decisionReason;
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
        if ($this->approved) {
            return $this->approvedMail($notifiable);
        }

        return $this->rejectedMail($notifiable);
    }

    /**
     * Почта при одобрении апелляции
     */
    protected function approvedMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Ваша апелляция одобрена')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы рассмотрели вашу апелляцию по нарушению от ' .
                $this->violation->created_at->format('d.m.Y') . '.')
            ->line('**Решение:** Апелляция ОДОБРЕНА')
            ->line('Наказание снято, ваш аккаунт восстановлен.')
            ->when($this->decisionReason, function ($message) {
                $message->line('**Комментарий модератора:** ' . $this->decisionReason);
            })
            ->action('Посмотреть детали', route('profile.violations.show', $this->violation->id))
            ->line('Приносим извинения за доставленные неудобства.');
    }

    /**
     * Почта при отклонении апелляции
     */
    protected function rejectedMail(object $notifiable): MailMessage
    {
        $penaltyText = match($this->violation->penalty_type) {
            Violation::PENALTY_WARNING => 'Предупреждение',
            Violation::PENALTY_TEMP_BAN => 'Временная блокировка',
            Violation::PENALTY_PERMANENT_BAN => 'Постоянная блокировка',
            default => 'Наказание',
        };

        return (new MailMessage)
            ->subject('ℹ️ Решение по вашей апелляции')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы рассмотрели вашу апелляцию по нарушению от ' .
                $this->violation->created_at->format('d.m.Y') . '.')
            ->line('**Решение:** Апелляция ОТКЛОНЕНА')
            ->line('Нарушение признано обоснованным, ' . $penaltyText . ' оставлено в силе.')
            ->when($this->decisionReason, function ($message) {
                $message->line('**Комментарий модератора:** ' . $this->decisionReason);
            })
            ->when($this->violation->active_until, function ($message) {
                $message->line('**Окончание блокировки:** ' .
                    $this->violation->active_until->format('d.m.Y'));
            })
            ->action('Посмотреть детали', route('profile.violations.show', $this->violation->id))
            ->line('Это окончательное решение модерации.');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $status = $this->approved ? 'approved' : 'rejected';
        $message = $this->approved
            ? 'Ваша апелляция одобрена, наказание снято'
            : 'Ваша апелляция отклонена, наказание оставлено в силе';

        return [
            'violation_id' => $this->violation->id,
            'type' => 'appeal_' . $status,
            'approved' => $this->approved,
            'message' => $message,
            'decision_reason' => $this->decisionReason,
            'action_url' => route('profile.violations.show', $this->violation->id),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
//    public function toBroadcast(object $notifiable): BroadcastMessage
//    {
//        return new BroadcastMessage([
//            'type' => 'appeal_' . ($this->approved ? 'approved' : 'rejected'),
//            'message' => $this->approved
//                ? '✅ Ваша апелляция одобрена'
//                : '❌ Ваша апелляция отклонена',
//            'violation_id' => $this->violation->id,
//        ]);
//    }

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
