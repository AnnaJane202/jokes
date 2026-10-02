<?php

namespace App\Services;

use App\Events\NewReportEvent;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportService
{
    public function createReport(array $data, array $evidenceIds = []): Report
    {
        // Получаем модель контента
        $reportable = $this->getReportable($data['reportable_type'], $data['reportable_id']);

        if (!$reportable) {
            throw new \Exception('Контент не найден');
        }

        // Проверяем, не жаловался ли уже пользователь
        $existing = Report::where('reporter_id', auth()->id())
            ->where('reportable_type', $data['reportable_type'])
            ->where('reportable_id', $data['reportable_id'])
            ->whereIn('status', [Report::STATUS_PENDING, Report::STATUS_REVIEWING])
            ->first();

        if ($existing) {
            throw new \Exception('Вы уже отправили жалобу на этот контент');
        }

        DB::beginTransaction();

        try {
            // 1. Создаём жалобу
            $report = Report::create([
                'reporter_id'      => auth()->id(),
                'reported_user_id' => $reportable->user_id ?? $reportable->id,
                'reportable_type'  => get_class($reportable),
                'reportable_id'    => $data['reportable_id'],
                'type'             => $data['type'],
                'reason'           => $data['reason'],
                'status'           => Report::STATUS_PENDING,
            ]);

            // 2. Привязываем загруженные файлы
            if (!empty($evidenceIds)) {
                ReportEvidence::whereIn('id', $evidenceIds)
                    ->where('user_id', auth()->id())
                    ->update(['report_id' => $report->id]);
            }

            DB::commit();

            // 3. Отправляем событие
            event(new NewReportEvent($report));

            Log::info('Новая жалоба создана', [
                'report_id' => $report->id,
                'user_id'   => auth()->id(),
            ]);

            return $report;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getReportable(string $type, int $id)
    {
        return match($type) {
            'post'    => Post::find($id),
            'comment' => Comment::find($id),
            'user'    => User::find($id),
            default   => null,
        };
    }


}
