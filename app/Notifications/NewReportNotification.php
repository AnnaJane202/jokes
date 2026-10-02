<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification
{
    use Queueable;

    protected Report $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
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
        return (new MailMessage)
            ->subject('📢 Новая жалоба на модерацию')
            ->greeting('Здравствуйте, ' . $notifiable->name . '!')
            ->line('Пользователь ' . $this->report->reporter->name . ' отправил жалобу.')
            ->line('Тип: ' . $this->report->getTypeLabel())
            ->line('Причина: ' . $this->report->reason)
            ->when($this->report->hasEvidence(), function ($mail) {
                $mail->line('📎 К жалобе приложены доказательства (' . $this->report->getEvidenceCount() . ' файлов)');
            })
            ->action('Рассмотреть жалобу', route('admin.posts.index', $this->report->id))
            ->line('Пожалуйста, рассмотрите жалобу в ближайшее время.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'type' => 'new_report',
            'message' => 'Новая жалоба от ' . $this->report->reporter->name,
            'action_url' => route('admin.posts.index', $this->report->id),
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
