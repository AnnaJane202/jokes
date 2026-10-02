<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportResolvedNotification extends Notification
{
    use Queueable;

    protected Report $report;
    protected bool $approved;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, bool $approved)
    {
        $this->report = $report;
        $this->approved = $approved;
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
    public function toMail($notifiable): MailMessage
    {
        $status = $this->approved ? '✅ Одобрена' : '❌ Отклонена';
        $message = $this->approved
            ? 'Ваша жалоба признана обоснованной. Нарушителю вынесено наказание.'
            : 'Ваша жалоба отклонена. Нарушение не подтвердилось.';

        return (new MailMessage)
            ->subject('📋 Решение по вашей жалобе')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Мы рассмотрели вашу жалобу.')
            ->line('Статус: ' . $status)
            ->line($message)
            ->when($this->report->moderator_comment, function ($mail) {
                $mail->line('Комментарий модератора: ' . $this->report->moderator_comment);
            })
            ->action('Посмотреть жалобу', route('client.reports.show', $this->report->id));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'type' => 'report_resolved',
            'approved' => $this->approved,
            'message' => $this->approved ? 'Ваша жалоба одобрена' : 'Ваша жалоба отклонена',
            'action_url' => route('client.reports.show', $this->report->id),
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
