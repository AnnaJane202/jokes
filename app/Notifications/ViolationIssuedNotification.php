<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ViolationIssuedNotification extends Notification implements ShouldQueue
{
    use Queueable;



    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Violation $violation  // Вот так правильно в PHP 8+
    ) {}

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
        $penaltyText = $this->getPenaltyText();
        $durationText = $this->getDurationText();


        return (new MailMessage)
            ->subject('⚠️ Нарушение правил платформы')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы зафиксировали нарушение правил нашей платформы.')
            ->line('**Тип нарушения:** ' . $this->violation->getTypeLabel())
            ->line('**Причина:** ' . $this->violation->reason)
            ->line('**Наказание:** ' . $penaltyText . $durationText)
            ->line('Если вы считаете, что это ошибка, вы можете подать апелляцию в течение 7 дней.')
            ->action('Подать апелляцию', route('client.violations.appeal', $this->violation->id))
//            ->action('👁️ Посмотреть детали', route('profile.violations.show', $this->violation->id))
            ->line('Спасибо за понимание!')
            ->salutation('С уважением, Администрация');

    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'violation_id' => $this->violation->id,
            'type' => $this->violation->type,
            'type_label' => $this->violation->getTypeLabel(),
            'penalty_type' => $this->violation->penalty_type,
            'penalty_label' => $this->violation->getPenaltyLabel(),
            'reason' => $this->violation->reason,
            'duration_days' => $this->violation->duration_days,
            'active_until' => $this->violation->active_until?->format('d.m.Y'),
            'message' => $this->getNotificationMessage(),
            'severity' => $this->getSeverityLevel(),
            'action_url' => route('client.violations.appeal', $this->violation->id),
            'created_at' => now()->toISOString(),
        ];
    }

    /**
     * Получить сообщение для уведомления
     */
    protected function getNotificationMessage(): string
    {
        $penalty = $this->getPenaltyText();
        $duration = $this->getDurationText();

        return "Вам вынесено нарушение: {$this->violation->getTypeLabel()}. {$penalty}{$duration}.";
    }

    /**
     * Получить уровень серьезности (для иконок в UI)
     */
    protected function getSeverityLevel(): string
    {
        return match($this->violation->penalty_type) {
            Violation::PENALTY_WARNING => 'warning',
            Violation::PENALTY_TEMP_BAN => 'danger',
            Violation::PENALTY_PERMANENT_BAN => 'critical',
            Violation::PENALTY_CONTENT_REMOVAL => 'info',
            default => 'info',
        };
    }

    /**
     * Получить текст наказания
     */
    protected function getPenaltyText(): string
    {
        return match($this->violation->penalty_type) {
            Violation::PENALTY_WARNING => 'Предупреждение',
            Violation::PENALTY_TEMP_BAN => 'Временная блокировка',
            Violation::PENALTY_PERMANENT_BAN => 'Постоянная блокировка',
            Violation::PENALTY_CONTENT_REMOVAL => 'Удаление контента',
            default => 'Наказание',
        };
    }

    /**
     * Получить текст длительности
     */
    protected function getDurationText(): string
    {
        if (!$this->violation->duration_days) {
            return '';
        }

        $days = $this->violation->duration_days;
        $word = $this->pluralize($days, ['день', 'дня', 'дней']);

        return " на {$days} {$word}";
    }

    /**
     * Склонение слова после числа
     */
    protected function pluralize(int $number, array $titles): string
    {
        $cases = [2, 0, 1, 1, 1, 2];
        return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
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
            'message' => $this->getNotificationMessage(),
        ];
    }


    /**
     * Get the broadcast representation of the notification.
     */
//    public function toBroadcast(object $notifiable): BroadcastMessage
//    {
//        return new BroadcastMessage([
//            'violation_id' => $this->violation->id,
//            'message' => $this->getNotificationMessage(),
//            'type' => 'violation_issued',
//            'time' => now()->diffForHumans(),
//        ]);
//    }
}
