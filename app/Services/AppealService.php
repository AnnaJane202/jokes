<?php

namespace App\Services;

use App\Models\Violation;
use App\Models\User;
use App\Notifications\AppealApprovedNotification;
use App\Notifications\AppealRejectedNotification;
use App\Notifications\AppealPartiallyApprovedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppealService
{
    /**
     * Обработать апелляцию
     */
    public function process(
        Violation $violation,
        string $decision,
        ?string $newPenaltyType = null,
        ?int $newDurationDays = null,
        ?string $comment = null,
//        bool $notifyUser = true
    ): array {
        // Проверяем, что апелляция еще не рассмотрена
        if ($violation->status !== 'appealed') {
            throw new \RuntimeException('Апелляция уже рассмотрена');
        }

        DB::beginTransaction();

        try {
            $user = $violation->user;
            $result = $this->applyDecision(
                $violation,
                $user,
                $decision,
                $newPenaltyType,
                $newDurationDays,
                $comment
            );

            // Отправляем уведомление, если нужно

                $this->sendNotification($violation, $user, $decision, $comment);


            DB::commit();

            Log::info('Апелляция обработана', [
                'violation_id' => $violation->id,
                'user_id' => $user->id,
                'decision_reason' => $decision,
                'moderator_id' => auth()->id(),
            ]);

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при обработке апелляции', [
                'violation_id' => $violation->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Применить решение
     */
    private function applyDecision(
        Violation $violation,
        User $user,
        string $decision,
        ?string $newPenaltyType,
        ?int $newDurationDays,
        ?string $comment
    ): array {
        return match ($decision) {
            'approve' => $this->approveAppeal($violation, $user, $comment),
            'reject' => $this->rejectAppeal($violation, $comment),
            'modify' => $this->modifyPenalty($violation, $user, $newPenaltyType, $newDurationDays, $comment),
            default => throw new \InvalidArgumentException("Некорректное решение: {$decision}"),
        };
    }

    /**
     * Одобрить апелляцию
     */
    private function approveAppeal(Violation $violation, User $user, ?string $comment): array
    {
        $violation->update([
            'status' => 'pardoned',
            'appeal_decided_at' => now(),
            'appeal_decided_by' => auth()->id(),
            'appeal_decision_reason' => 'approved',
            'moderator_comment' => $comment,
        ]);

        $user->update([
            'is_banned' => false,
            'banned_at' => null,
            'banned_until' => null,
            'ban_reason' => null,
        ]);

        return [
            'status' => 'approved',
            'message' => 'Апелляция одобрена, наказание снято',
        ];
    }

    /**
     * Отклонить апелляцию
     */
    private function rejectAppeal(Violation $violation, ?string $comment): array
    {
        $violation->update([
            'status' => 'active',
            'appeal_decided_at' => now(),
            'appeal_decided_by' => auth()->id(),
            'appeal_decision_reason' => 'rejected',
            'moderator_comment' => $comment,
        ]);

        return [
            'status' => 'rejected',
            'message' => 'Апелляция отклонена, наказание оставлено в силе',
        ];
    }

    /**
     * Смягчить наказание
     */
    private function modifyPenalty(
        Violation $violation,
        User $user,
        ?string $newPenaltyType,
        ?int $newDurationDays,
        ?string $comment
    ): array {
//        dd($violation);
        $activeUntil = null;
        if ($newPenaltyType === 'temp_ban' && $newDurationDays) {
            $activeUntil = now()->addDays($newDurationDays);
        }

        $violation->update([
            'status' => 'modified',
            'penalty_type' => $newPenaltyType,
            'duration_days' => $newDurationDays,
            'active_until' => $activeUntil,
            'appeal_decided_at' => now(),
            'appeal_decided_by' => auth()->id(),
            'appeal_decision_reason' => 'modified',
            'moderator_comment' => $comment,
        ]);



        // Обновляем статус бана пользователя
        $this->updateUserBanStatus($user, $newPenaltyType, $activeUntil, $violation);

        return [
            'status' => 'modified',
            'message' => 'Наказание изменено',
            'new_penalty_type' => $newPenaltyType,
            'new_duration_days' => $newDurationDays,
        ];
    }

    /**
     * Обновить статус бана пользователя
     */
    private function updateUserBanStatus(
        User $user,
        string $penaltyType,
        ?\DateTime $activeUntil,
        Violation $violation
    ): void {
        $banReason = $violation->reason . ' (наказание смягчено по апелляции)';

        match ($penaltyType) {
            'permanent_ban' => $user->update([
                'is_banned' => true,
                'banned_at' => now(),
                'banned_until' => null,
                'ban_reason' => $banReason,
            ]),
            'temp_ban' => $user->update([
                'is_banned' => true,
                'banned_at' => now(),
                'banned_until' => $activeUntil,
                'ban_reason' => $banReason,
            ]),
            default => $user->update([
                'is_banned' => false,
                'banned_at' => null,
                'banned_until' => null,
                'ban_reason' => null,
            ]),
        };
    }

    /**
     * Отправить уведомление пользователю
     */
    private function sendNotification(
        Violation $violation,
        User $user,
        string $decision,
        ?string $comment
    ): void {
        $notification = match ($decision) {
            'approve' => new AppealApprovedNotification($violation, $comment),
            'reject' => new AppealRejectedNotification($violation, $comment),
            'modify' => new AppealPartiallyApprovedNotification($violation, $comment),
            default => null,
        };

        if ($notification) {
            $user->notify($notification);
        }
    }
}
