<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'email_verified_at',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
        'banned_at' => 'datetime',
        'banned_until' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

//    public function roles(): BelongsToMany
//    {
//        return $this->belongsToMany(Role::class);
//    }

// Отношение многие-ко-многим с ролями
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    // Получить названия ролей пользователя
    public function getRoleNames()
    {
        return $this->roles->pluck('title');
    }


    public function getIsAdminAttribute(): bool
    {
        return $this->roles->contains('title', 'admin');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? Storage::url($this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Кто забанил пользователя
     */
    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }


    /**
     * Проверка, временно заблокирован ли пользователь
     */
    public function isTemporarilyBanned(): bool
    {
        return $this->banned_until && $this->banned_until->isFuture();
    }

    /**
     * Заблокировать пользователя
     */
    public function ban(string $reason = null, User $bannedBy = null, $days = null )
    {
        $this->update([
            'is_banned' => true,
            'ban_reason' => $reason,
            'banned_at' => now(),
            'banned_until' => $days ? now()->addDays($days) : null,
            'banned_by' => $bannedBy?->id,

        ]);

        // Можно добавить событие
//        event(new UserBanned($this, $bannedBy, $reason));

        return $this;
    }

    /**
     * Разблокировать пользователя
     */
    public function unban()
    {
        $this->update([
            'is_banned' => false,
            'ban_reason' => null,
            'banned_at' => null,
            'banned_until' => null,
            'banned_by' => null,
        ]);

//        event(new UserUnbanned($this));

        return $this;
    }

    /**
     * Получить оставшееся время блокировки
     */
    public function getBanTimeLeftAttribute()
    {
        if (!$this->banned_until || $this->banned_until->isPast()) {
            return null;
        }

        return $this->banned_until->diffForHumans(['part' => 2]);
    }

    /**
     * Глобальный scope для исключения заблокированных пользователей
     */
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope('notBanned', function ($builder) {
//            $builder->where(function ($query) {
//                $query->where('is_banned', false)
//                    ->orWhere(function ($q) {
//                        $q->whereNotNull('banned_until')
//                            ->where('banned_until', '<', now());
//                    });
//            });
//        });
//    }

    /**
     * История нарушений пользователя
     */
    public function violations(): hasMany
    {
        return $this->hasMany(Violation::class);
    }

    // Жалобы, которые пользователь отправил
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

// Жалобы, которые пользователь получил (на него пожаловались)
    public function receivedReports()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    /**
     * Активные нарушения
     */
    public function activeViolations()
    {
        return $this->violations()->where('status', Violation::STATUS_ACTIVE);
    }

    /**
     * Количество активных нарушений
     */
    public function getActiveViolationsCountAttribute(): int
    {
        return $this->activeViolations()->count();
    }

    /**
     * Получить текущее самое строгое наказание
     */
    public function getCurrentPenaltyAttribute(): ?Violation
    {
        $penaltyPriority = [
            Violation::PENALTY_PERMANENT_BAN,
            Violation::PENALTY_TEMP_BAN,
            Violation::PENALTY_CONTENT_REMOVAL,
            Violation::PENALTY_WARNING,
        ];

        foreach ($penaltyPriority as $penaltyType) {
            $violation = $this->activeViolations()
                ->where('penalty_type', $penaltyType)
                ->first();

            if ($violation) {
                return $violation;
            }
        }

        return null;
    }

    /**
     * метод проверки блокировки
     */
    public function isBanned(): bool
    {
        $currentPenalty = $this->currentPenalty;

//        dd($currentPenalty);

        if (!$currentPenalty) {
            return false;
        }

        // Если permanent_ban - всегда заблокирован
        if ($currentPenalty->penalty_type === Violation::PENALTY_PERMANENT_BAN) {
            return true;
        }

        // Если temp_ban - проверяем срок
        if ($currentPenalty->penalty_type === Violation::PENALTY_TEMP_BAN) {
            // ✅ СНАЧАЛА проверяем, что active_until не null
            if ($currentPenalty->active_until && $currentPenalty->active_until->isFuture()) {
                return true;
            }

            // Если active_until = null или срок истек - снимаем
            if ($currentPenalty->active_until && $currentPenalty->active_until->isPast()) {
                $currentPenalty->revokeIfExpired();
            }

            return false;
        }

        return false;
    }

    /**
     * Получить причину текущей блокировки
     */
    public function getBanReasonAttribute(): ?string{
        $currentPenalty = $this->currentPenalty;

        if ($currentPenalty && $this->isBanned()) {
            return $currentPenalty->reason;
        }

        return $this->attributes['ban_reason'] ?? null;
    }

    public function getTotalViolationsAttribute(): int
    {
        return $this->violations()->count();
    }




}
