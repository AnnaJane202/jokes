<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Violation;
use App\Notifications\ReportResolvedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportResolutionService
{
    /**
     * Create a new class instance.
     */


    public function resolve(Report $report, string $action, ?string $violationType = null, ?string $violationReason = null, ?string $penaltyType = null, ?int $durationDays = null, ?string $moderatorComment = null): Report
    {
//        dd($report);
        DB::beginTransaction();

        try {
            $report->update([
                'admin_id' => auth()->id(),
                'reviewed_at' => now(),
                'moderator_comment' => $moderatorComment,
            ]);

            if ($action === 'create_violation') {
                $violation = $this->createViolation($report, $violationType, $violationReason, $penaltyType, $durationDays);
                $report->update([
                    'status' => Report::STATUS_RESOLVED,
                    'resolution_type' => 'violation_created',
                    'violation_id' => $violation->id,
                ]);
                $approved = true;
            } else {
                $report->update([
                    'status' => Report::STATUS_REJECTED,
                    'resolution_type' => 'rejected',
                ]);
                $approved = false;
            }

            DB::commit();

             //Отправка уведомления автору жалобы
            $report->reporter->notify(new ReportResolvedNotification($report, $approved));

            Log::info('Жалоба разрешена', [
                'report_id' => $report->id,
                'action' => $action,
                'admin_id' => auth()->id(),
            ]);

            return $report;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function createViolation(Report $report, string $type, string $reason, string $penaltyType, ?int $durationDays): Violation
    {
        $violationService = app(ViolationService::class);

        return $violationService->issueViolation([
            'user_id' => $report->reported_user_id,
            'admin_id' => auth()->id(),
            'type' => $type,
            'reason' => $reason,
            'penalty_type' => $penaltyType,
            'duration_days' => $durationDays,
            'details' => [
                'report_id' => $report->id,
                'report_reason' => $report->reason,
                'report_type' => $report->type,
            ],
        ]);
    }
}
