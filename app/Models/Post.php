<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'user_id',
        'category_id',
        'published',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Генерируем slug только если он не задан
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug();
            }
        });
    }

    public static function generateUniqueSlug($length = 10)
    {
        do {
            $slug = Str::random($length);
        } while (static::where('slug', $slug)->exists());

        return $slug;
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function isLikedByUser($userId = null)
    {
        $userId = $userId ?: auth()->id();
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Количество лайков
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
