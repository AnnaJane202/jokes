<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Violation;
use App\Notifications\AppealApprovedNotification;
use App\Notifications\AppealRejectedNotification;
use App\Notifications\NewAppealNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViolationService
{
    /**
     * Вынести нарушение пользователю
     */
    public function issueViolation(array $data): Violation
    {
//        dd($data);
        $violation = Violation::create([
            'user_id' => $data['user_id'],
            'admin_id' => $data['admin_id'],
            'type' => $data['type'],
            'reason' => $data['reason'],
            'details' => $data['details'] ?? null,
            'penalty_type' => $data['penalty_type'],
            'duration_days' => $data['duration_days'] ?? null,
            'status' => Violation::STATUS_ACTIVE,
            'active_until' => $this->calculateActiveUntil($data),
        ]);

//        dd($violation);

        // Применить наказание
        $this->applyPenalty($violation);

        // Отправить уведомление
        $this->notifyUser($violation);

        // Логирование
        Log::info('Вынесено нарушение', [
            'violation_id' => $violation->id,
            'user_id' => $violation->user_id,
            'admin_id' => $violation->admin_id,
            'penalty_type' => $violation->penalty_type,
        ]);

        return $violation;


    }

    /**
     * Рассчитать дату окончания наказания
     */
    public function calculateActiveUntil(array $data)
    {
        if ($data['penalty_type'] === Violation::PENALTY_TEMP_BAN && isset($data['duration_days'])) {
            return now()->addDays($data['duration_days']);
        }

        return null;
    }

    /**
     * Применить наказание
     */
    public function applyPenalty(Violation $violation): void
    {
        $user = $violation->user;

        switch($violation->penalty_type) {
            case Violation::PENALTY_TEMP_BAN:
            case Violation::PENALTY_PERMANENT_BAN:
                $user->update([
                    'is_banned' => true,
                    'ban_reason' => $violation->reason,
                    'banned_at' => now(),
                    'banned_until' => $violation->active_until,
                ]);
                break;
            case Violation::PENALTY_CONTENT_REMOVAL:
                $user->posts()->where('user_id', $user->id)->delete();
                break;
            case Violation::PENALTY_WARNING:
                // Только предупреждение
                break;
        }
    }

    /**
     * Уведомить пользователя
     */
    protected function notifyUser(Violation $violation): void
    {
//        dd($violation->user);
        $violation->user->notify(new \App\Notifications\ViolationIssuedNotification($violation));

//        try {
//            Log::info('=== NOTIFY USER START ===', [
//                'violation_id' => $violation->id,
//                'user_id' => $violation->user_id
//            ]);
//
//            $user = $violation->user;
//
//            Log::info('User loaded', [
//                'user_id' => $user->id,
//                'user_class' => get_class($user),
//                'notifiable_trait_used' => in_array('Illuminate\Notifications\Notifiable', class_uses($user))
//            ]);
//
//            // Проверяем, что у пользователя есть метод notify
//            if (!method_exists($user, 'notify')) {
//                Log::error('User does not have notify method');
//                return;
//            }
//
//            // Создаем уведомление
//            $notification = new \App\Notifications\ViolationIssuedNotification($violation);
//
//            Log::info('Notification created', [
//                'notification_class' => get_class($notification)
//            ]);
//
//            // Отправляем
//            $user->notify($notification);
//
//            Log::info('=== NOTIFY USER END ===');
//
//        } catch (\Exception $e) {
//            Log::error('=== NOTIFY USER ERROR ===', [
//                'error_message' => $e->getMessage(),
//                'error_file' => $e->getFile(),
//                'error_line' => $e->getLine(),
//                'error_trace' => $e->getTraceAsString()
//            ]);
//
//            // Временно выведем ошибку
//            throw $e;
//        }
    }

    /**
     * Подать апелляцию
     */
    public function appealViolation(Violation $violation, string $reason, User $appealedBy): bool
    {
        if ($violation->status !== Violation::STATUS_ACTIVE) {
            return false;
        }

        $violation->update([
            'appeal_reason' => $reason,
            'appealed_at' => now(),
            'status' => Violation::STATUS_APPEALED,
        ]);

        // Уведомить администраторов
//        $this->notifyAdminsAboutAppeal($violation);

        return true;
    }

    /**
     * Рассмотреть апелляцию
     */
    public function decideAppeal(Violation $violation, bool $approved, ?string $reason, User $decidedBy): bool
    {
        if ($violation->status !== Violation::STATUS_APPEALED) {
            return false;
        }

        $newStatus = $approved ? Violation::STATUS_PARDONED : Violation::STATUS_ACTIVE;

        $violation->update([
            'appeal_decided_by' => $decidedBy->id,
            'appeal_decided_at' => now(),
            'appeal_decision_reason' => $reason,
            'status' => $newStatus,
        ]);

        if ($approved) {
            // Снять наказание
            $this->revokePenalty($violation);
        }

        // Уведомить пользователя
        $violation->user->notify(new \App\Notifications\AppealDecidedNotification($violation));

        return true;
    }


    /**
     * Снять наказание
     */
    protected function revokePenalty(Violation $violation): void
    {
        $user = $violation->user;

        switch ($violation->penalty_type) {
            case Violation::PENALTY_TEMP_BAN:
            case Violation::PENALTY_PERMANENT_BAN:
                $user->update([
                    'is_banned' => false,
                    'ban_reason' => null,
                    'banned_at' => null,
                    'banned_until' => null,
                ]);
                break;
        }
    }

    /**
     * Сохранить аппеляцию
     */
    public static function storeAppeal(Violation $violation, array $data)
    {
        // Сохраняем апелляцию
        $violation->update([
            'appeal_reason' => $data['appeal_reason'],
            'details' => $data['details'],
            'appealed_at' => now(),
            'status' => Violation::STATUS_APPEALED,
        ]);

        // Уведомить администраторов
        self::notifyAdmins($violation);

        return true;
    }

    /**
     * Быстрое одобрение апелляции
     */
    public function quickAppealApprove(Violation $violation, int $adminId): Violation
    {
        try {
            DB::beginTransaction();

            // Обновляем статус
            $violation->update([
                'status' => 'pardoned',
                'appeal_decided_at' => now(),
                'appeal_decided_by' => $adminId,
                'appeal_decision_reason' => 'approved',

            ]);

            // Снимаем наказание
            $user = $violation->user;
            $user->update([
                'is_banned' => false,
                'banned_at' => null,
                'banned_until' => null,
                'ban_reason' => null,
            ]);

            DB::commit();

             //Отправляем уведомление
            $user->notify(new AppealApprovedNotification($violation, null));
            Log::info('Апелляция быстро одобрена', [
                'violation_id' => $violation->id,
                'user_id' => $user->id,
                'moderator_id' => $adminId,
            ]);

            return $violation->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при быстром одобрении апелляции', [
                'violation_id' => $violation->id,
                'error' => $e->getMessage(),
            ]);
            throw $e; // Пробрасываем исключение дальше
        }


    }

    /**
     * Быстрое одобрение апелляции
     */
    public function quickAppealReject(Violation $violation, int $adminId): Violation
    {
        try {
            DB::beginTransaction();

            $violation->update([
                'status' => 'active', // Возвращаем в активное состояние
                'appeal_decided_at' => now(),
                'appeal_decided_by' => $adminId,
                'appeal_decision_reason' => 'rejected',

            ]);

            DB::commit();

            // Отправляем уведомление
            $violation->user->notify(new AppealRejectedNotification($violation, null));

            Log::info('Апелляция быстро отклонена', [
                'violation_id' => $violation->id,
                'user_id' => $violation->user->id,
                'moderator_id' => $adminId,
            ]);

            return $violation->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при быстром отклонении апелляции', [
                'violation_id' => $violation->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private static  function notifyAdmins(Violation $violation)
    {
        // Находим всех админов и модераторов
        $adminIds = Role::where('title', 'admin')->pluck('id');
        $admins = User::where('id', $adminIds)->get();
//        dd($admins);

        foreach ($admins as $admin) {
            $admin->notify(new NewAppealNotification($violation));
        }
    }
}
