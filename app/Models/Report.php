<?php

namespace App\Models;

use App\Models\Traits\HasViolationTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes, HasViolationTypes;

    protected $appends = ['status_label', 'type_label']; // ← ЭТО ОБЯЗАТЕЛЬНО!

    // ДОПОЛНИТЕЛЬНЫЕ КОНСТАНТЫ (только для Report)

    // Специфические типы для жалоб
    public const TYPE_NSFW = 'nsfw';
    public const TYPE_DOXING = 'doxing';
    public const TYPE_MISINFORMATION = 'misinformation';
    public const TYPE_ADMIN_ABUSE = 'admin_abuse'; // ← та же константа, что в Violation

    // Статусы жалоб
    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWING = 'reviewing';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reportable_type',
        'reportable_id',
        'type',
        'reason',
        'details',
        'status',
        'moderator_id',
        'resolution_type',
        'violation_id',
        'moderator_comment',
        'reviewed_at',
    ];

    protected $casts = [
        'details' => 'array',
        'reviewed_at' => 'datetime',
    ];

    // ПЕРЕОПРЕДЕЛЯЕМ МЕТОД ИЗ ТРЕЙТА

    /**
     * Получить все типы нарушений с метками (с учетом специфических для Report)
     */
    public static function getViolationTypes(): array
    {
//        dd(parent::getViolationTypes());
        return array_merge(self::getBaseViolationTypes(), [
            self::TYPE_NSFW => 'NSFW контент',
            self::TYPE_DOXING => 'Доксинг (разглашение личных данных)',
            self::TYPE_MISINFORMATION => 'Дезинформация',
            self::TYPE_ADMIN_ABUSE => 'Злоупотребление админ-правами',
        ]);
    }

    /**
     * Получить все статусы жалоб
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Ожидает',
            self::STATUS_REVIEWING => 'В работе',
            self::STATUS_RESOLVED => 'Решена',
            self::STATUS_REJECTED => 'Отклонена',
        ];
    }

    public static function getStatusOptions(): array
    {
        return collect(static::getStatuses())
            ->map(fn($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->toArray();
    }

    public function getStatusLabel(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }



    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_REVIEWING => 'blue',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->getTypeLabel(); // используем существующий метод
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->getStatusLabel(); // используем существующий метод
    }

    // СВЯЗИ

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    public function reportable()
    {
        return $this->morphTo();
    }
    public function evidenceFiles()
    {
        return $this->hasMany(ReportEvidence::class);
    }

    // МЕТОДЫ ДЛЯ РАБОТЫ С КОНТЕНТОМ

    public function getReportableTypeLabel(): string
    {
        return match(class_basename($this->reportable_type)) {
            'Post' => 'Пост',
            'Comment' => 'Комментарий',
            'User' => 'Пользователь',
            default => 'Неизвестный контент',
        };
    }

    public function getReportableTitle(): string
    {
        if (!$this->reportable) {
            return 'Удаленный контент';
        }

        return match(class_basename($this->reportable_type)) {
            'Post' => $this->reportable->title ?? 'Пост без заголовка',
            'Comment' => substr($this->reportable->body ?? '', 0, 50) . '...',
            'User' => $this->reportable->name ?? 'Пользователь',
            default => 'Контент',
        };
    }

    public function getReportableUrl(): string
    {
        if (!$this->reportable) {
            return '#';
        }

        return match(class_basename($this->reportable_type)) {
            'Post' => route('admin.posts.show', $this->reportable_id),
            'Comment' => route('admin.comments.show', $this->reportable_id),
            'User' => route('admin.users.show', $this->reportable_id),
            default => '#',
        };
    }

    // ХЕЛПЕРЫ

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function hasEvidence(): bool
    {
        return $this->evidenceFiles()->exists();
    }

    public function getEvidenceCount(): int
    {
        return $this->evidenceFiles()->count();
    }

}
