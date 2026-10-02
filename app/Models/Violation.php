<?php

namespace App\Models;

use App\Models\Traits\HasViolationTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Violation extends Model
{
    use SoftDeletes, HasViolationTypes;

    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'details',
        'penalty_type',
        'duration_days',
        'active_until',
        'appeal_reason',
        'appealed_at',
        'appeal_decided_at',
        'appeal_decision_reason',
//        'auto_revoked',
    ];

    // ДОПОЛНИТЕЛЬНЫЕ КОНСТАНТЫ (только для Violation)

    // Специфические типы для нарушений
    public const TYPE_ADMIN_ABUSE = 'admin_abuse';
    public const TYPE_SYSTEM_VIOLATION = 'system_violation';


    protected $casts = [
        'details' => 'array',
        'active_until' => 'datetime',
        'appealed_at' => 'datetime',
        'appeal_decided_at' => 'datetime',
        'auto_revoked' => 'boolean',
    ];

    // Константы для типов нарушений
//    const TYPE_SPAM = 'spam';
//    const TYPE_ABUSE = 'abuse';
//    const TYPE_HARASSMENT = 'harassment';
//    const TYPE_COPYRIGHT = 'copyright';
//    const TYPE_ILLEGAL = 'illegal';
//    const TYPE_FRAUD = 'fraud';
//    const TYPE_OTHER = 'other';

    // Константы для типов наказаний
    const PENALTY_WARNING = 'warning';
    const PENALTY_TEMP_BAN = 'temp_ban';
    const PENALTY_PERMANENT_BAN = 'permanent_ban';
    const PENALTY_CONTENT_REMOVAL = 'content_removal';
//    public const PENALTY_SHADOW_BAN = 'shadow_ban';

    // Константы статусов
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_APPEALED = 'appealed';
    const STATUS_REVOKED = 'revoked';
    const STATUS_PARDONED = 'pardoned';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_MODIFIED = 'modified';
//    public const STATUS_EXPIRED = 'expired';


    // ПЕРЕОПРЕДЕЛЯЕМ МЕТОД ИЗ ТРЕЙТА
    /**
     * Получить все типы нарушений с метками (с учетом специфических для Violation)
     */
    public static function getViolationTypes(): array
    {
        return array_merge(self::getBaseViolationTypes(), [
            self::TYPE_ADMIN_ABUSE => 'Злоупотребление админ-правами',
            self::TYPE_SYSTEM_VIOLATION => 'Системное нарушение',
        ]);
    }

    /**
     * Пользователь, который нарушил
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Модератор или Админ, который вынес наказание
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Кто рассмотрел апелляцию
     */
    public function appealDecidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'appeal_decided_by');
    }

    /**
     * Проверка, активно ли нарушение
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && (!$this->active_until || $this->active_until->isFuture());
    }

    /**
     * Проверка, истекло ли нарушение
     */
    public function isExpired(): bool
    {
        return $this->active_until && $this->active_until->isPast();
    }

    /**
     * Автоматическое снятие при истечении срока
     */
    public function revokeIfExpired(): bool
    {
        if ($this->isExpired() && $this->status === self::STATUS_ACTIVE) {
            $this->update([
                'status' => self::STATUS_EXPIRED,
                'auto_revoked' => true,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Получить человеко-читаемый тип нарушения
     */
//    public function getTypeLabel(): string
//    {
//        return match($this->type) {
//            self::TYPE_SPAM => 'Спам',
//            self::TYPE_ABUSE => 'Оскорбления',
//            self::TYPE_HARASSMENT => 'Травля',
//            self::TYPE_COPYRIGHT => 'Нарушение авторских прав',
//            self::TYPE_ILLEGAL => 'Незаконный контент',
//            self::TYPE_FRAUD => 'Мошенничество',
//            default => 'Другое',
//        };
//    }

    public function getTypeLabel(): string
    {
        return static::getViolationTypeLabel($this->type);
    }

    /**
     * Получить человеко-читаемый тип наказания
     */
    public function getPenaltyLabel(): string
    {
        return match($this->penalty_type) {
            self::PENALTY_WARNING => 'Предупреждение',
            self::PENALTY_TEMP_BAN => 'Временная блокировка',
            self::PENALTY_PERMANENT_BAN => 'Постоянная блокировка',
            self::PENALTY_CONTENT_REMOVAL => 'Удаление контента',
            default => 'Неизвестно',
        };
    }

    /**
     * Получить все типы наказаний с метками
     */
    public static function getPenaltyTypes(): array
    {
        return [
            self::PENALTY_WARNING => 'Предупреждение',
            self::PENALTY_TEMP_BAN => 'Временная блокировка',
            self::PENALTY_PERMANENT_BAN => 'Постоянная блокировка',
            self::PENALTY_CONTENT_REMOVAL => 'Удаление контента',
        ];
    }

    /**
     * Получить опции для селекта
     */
    public static function getPenaltyOptions(): array
    {
        return collect(self::getPenaltyTypes())
            ->map(fn($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->toArray();
    }

    /**
     * Получить метку типа наказания
     */
//    public function getPenaltyLabel(): string
//    {
//        $penalties = self::getPenaltyTypes();
//        return $penalties[$this->penalty_type] ?? $this->penalty_type;
//    }

    /**
     * Получить все статусы
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активно',
            self::STATUS_APPEALED => 'Апелляция подана',
            self::STATUS_PARDONED => 'Прощено',
            self::STATUS_REJECTED => 'Отклонено',
            self::STATUS_MODIFIED => 'Изменено',
            self::STATUS_EXPIRED => 'Истекло',
        ];
    }

    /**
     * Связь с доказательствами апелляции
     */
    public function appealEvidence()
    {
        return $this->hasMany(AppealEvidence::class);
    }

    /**
     * Проверка, есть ли апелляция
     */
    public function hasAppeal(): bool
    {
        return !is_null($this->appeal_reason);
    }



    public function isAppealed(): bool
    {
        return $this->status === self::STATUS_APPEALED;
    }



    public function canAppeal(): bool
    {
        return $this->isActive() && !$this->hasAppeal() && !$this->created_at->addDays(7)->isPast();
    }
}
