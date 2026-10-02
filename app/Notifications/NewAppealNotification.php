<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppealNotification extends Notification
{
    use Queueable;

    protected Violation $violation;
    protected ?string $adminUrl;
    /**
     * Create a new notification instance.
     */
    public function __construct(Violation $violation)
    {
        $this->violation = $violation;
        $this->adminUrl = route('admin.violations.review-appeal', $violation->id);
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
        $user = $this->violation->user;
        $deadline = $this->violation->created_at->addDays(7)->format('d.m.Y');


        return (new MailMessage)
            ->subject('⚖️ Новая апелляция на рассмотрение')
            ->greeting("Здравствуйте, {$notifiable->name}!")
            ->line('Пользователь подал апелляцию на вынесенное нарушение.')
            ->line('**Детали нарушения:**')
            ->line("- **Пользователь:** {$user->name} ({$user->email})")
            ->line("- **Тип нарушения:** {$this->violation->getTypeLabel()}")
            ->line("- **Наказание:** {$this->violation->getPenaltyLabel()}")
            ->line("- **Дата нарушения:** {$this->violation->created_at->format('d.m.Y H:i')}")
            ->line('')
            ->line('**Причина апелляции:**')
            ->line($this->violation->appeal_reason)
            ->line('')
            ->line("**Срок рассмотрения:** до {$deadline}")
            ->action('🔍 Рассмотреть апелляцию', $this->adminUrl)
            ->line('Пожалуйста, рассмотрите апелляцию в ближайшее время.')
            ->salutation('С уважением, Система модерации');
    }


    public function toDatabase(object $notifiable): array
    {
        $user = $this->violation->user;

        return [
            'id' => $this->id,
            'type' => 'new_appeal',
            'violation_id' => $this->violation->id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'violation_type' => $this->violation->type,
            'violation_type_label' => $this->violation->getTypeLabel(),
            'penalty_type' => $this->violation->penalty_type,
            'penalty_label' => $this->violation->getPenaltyLabel(),
            'appeal_reason' => $this->violation->appeal_reason,
//            'has_evidence' => !empty($this->violation->appeal_evidence),
//            'evidence_count' => count($this->violation->appeal_evidence ?? []),
            'appealed_at' => $this->violation->appealed_at->format('d.m.Y H:i'),
            'deadline' => $this->violation->created_at->addDays(7)->format('d.m.Y'),
            'is_urgent' => $this->isUrgent(),
            'priority' => $this->getPriority(),
            'message' => "Новая апелляция от пользователя {$user->name}",
            'action_url' => $this->adminUrl,
            'created_at' => now()->toISOString(),
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
            'violation_id' => $this->violation->id,
            'message' => "Новая апелляция от {$this->violation->user->name}",
        ];
    }


    /**
     * Определяем срочность апелляции (осталось меньше 3 дней)
     */
    protected function isUrgent(): bool
    {
        $deadline = $this->violation->created_at->addDays(7);
        return now()->diffInDays($deadline, false) <= 3;
    }

    /**
     * Определяем приоритет (для отображения)
     */
    protected function getPriority(): string
    {
        // Высокий приоритет для серьезных нарушений
        $highPriorityTypes = ['harassment', 'illegal', 'fraud'];

        if (in_array($this->violation->type, $highPriorityTypes)) {
            return 'high';
        }

        if ($this->isUrgent()) {
            return 'medium';
        }

        return 'normal';
    }
}
