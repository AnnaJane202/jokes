<?php

namespace App\Models\Traits;

trait HasViolationTypes
{


    // ОБЩИЕ КОНСТАНТЫ (доступны во всех моделях)

    // Основные типы нарушений
    public const TYPE_SPAM = 'spam';
    public const TYPE_ABUSE = 'abuse';
    public const TYPE_HARASSMENT = 'harassment';
    public const TYPE_COPYRIGHT = 'copyright';
    public const TYPE_ILLEGAL = 'illegal';
    public const TYPE_FRAUD = 'fraud';
    public const TYPE_OTHER = 'other';

    // Дополнительные типы (могут быть переопределены в модели)
    public const TYPE_NSFW = 'nsfw';
    public const TYPE_DOXING = 'doxing';
    public const TYPE_MISINFORMATION = 'misinformation';
    public const TYPE_ADMIN_ABUSE = 'admin_abuse';
    public const TYPE_SYSTEM_VIOLATION = 'system_violation';

    // МЕТОДЫ
    /**
     * Получить все типы нарушений с метками
     * Можно переопределить в модели для добавления своих типов
     */
    public static function getBaseViolationTypes(): array
    {
        return [
            self::TYPE_SPAM => 'спам',
            self::TYPE_ABUSE => 'Оскорбления',
            self::TYPE_HARASSMENT => 'Травля',
            self::TYPE_COPYRIGHT => 'Нарушение авторских прав',
            self::TYPE_ILLEGAL => 'Незаконный контент',
            self::TYPE_FRAUD => 'Мошенничество',
            self::TYPE_OTHER => 'Другое',
        ];
    }

    /**
     * Получить метку для конкретного типа
     */
    public static function getViolationTypeLabel(string $type): string
    {
        return static::getViolationTypes()[$type] ?? $type;
    }

    /**
     * Получить метку текущего типа (для экземпляра модели)
     */
    public function getTypeLabel(): string{
        $types = static::getViolationTypes(); // static:: для позднего связывания, метод вызывается из текущией модели, но не из трейта(то есть не из класса, где его создали, а из класса, который вызывает)
        return $types[$this->type] ?? $this->type;
    }

    /**
     * Проверить, является ли тип валидным
     */
    public static function isValidViolationType(string $type): bool
    {
        return array_key_exists($type, static::getViolationTypes());
    }

    /**
     * Получить все типы для select/option (массив объектов)
     */
    public static function getViolationTypeOptionsArray(): array
    {
        return collect(static::getViolationTypes())
            ->map(fn($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->toArray();
    }

    /**
     * Получить иконку для типа
     */
    public static function getViolationTypeIcon(string $type): string
    {
        return match($type) {
            self::TYPE_SPAM => '📢',
            self::TYPE_ABUSE => '😠',
            self::TYPE_HARASSMENT => '🚫',
            self::TYPE_COPYRIGHT => '©️',
            self::TYPE_ILLEGAL => '⚖️',
            self::TYPE_FRAUD => '🤥',
            self::TYPE_NSFW => '🔞',
            self::TYPE_DOXING => '🔍',
            self::TYPE_MISINFORMATION => '📰',
            self::TYPE_ADMIN_ABUSE => '👑',
            self::TYPE_SYSTEM_VIOLATION => '⚙️',
            self::TYPE_OTHER => '❓',
            default => '📌',
        };
    }

    /**
     * Получить цвет для типа (для бейджей)
     */
    public static function getViolationTypeColor(string $type): string
    {
        return match($type) {
            self::TYPE_SPAM => 'yellow',
            self::TYPE_ABUSE => 'orange',
            self::TYPE_HARASSMENT => 'red',
            self::TYPE_COPYRIGHT => 'blue',
            self::TYPE_ILLEGAL => 'purple',
            self::TYPE_FRAUD => 'pink',
            self::TYPE_NSFW => 'red',
            self::TYPE_DOXING => 'red',
            self::TYPE_MISINFORMATION => 'orange',
            self::TYPE_ADMIN_ABUSE => 'red',
            self::TYPE_SYSTEM_VIOLATION => 'gray',
            self::TYPE_OTHER => 'gray',
            default => 'gray',
        };
    }
}
