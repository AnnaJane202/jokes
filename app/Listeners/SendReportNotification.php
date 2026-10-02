<?php

namespace App\Listeners;

use App\Events\NewReportEvent;
use App\Models\User;
use App\Notifications\NewReportNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendReportNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewReportEvent $event): void
    {
        Log::info('SendReportNotification вызван', ['report_id' => $event->report->id]);
        $report = $event->report;

        // Находим всех админов и модераторов
//        $admins = User::whereIn('role', ['admin'])->get();
        $admins = User::whereHas('roles', function ($query) {
            $query->where('title', 'admin');
        })->get();
        Log::info('Найдено админов/модераторов: ' . $admins->count());
//        $admins = User::whereIn('role', ['admin', 'moderator'])->get();

        // Отправляем уведомление каждому админу
        foreach ($admins as $admin) {
            try {

                $admin->notify(new NewReportNotification($report));


                Log::info('Уведомление о новой жалобе отправлено админу', [
                    'admin_id' => $admin->id,
                    'report_id' => $report->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Ошибка отправки уведомления админу', [
                    'admin_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
