<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ReportEvidence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'report_id',
        'user_id',
        'filename',
        'original_name',
        'path',
        'url',
        'size',
        'mime_type',
        'description',
        'is_verified',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'size' => 'integer',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // СВЯЗИ
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ХЕЛПЕРЫ

    public function getFileSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / (1024 * 1024), 1) . ' MB';
    }

    public function getIconAttribute(): string
    {
        return match(true) {
            str_starts_with($this->mime_type, 'image/') => '🖼️',
            $this->mime_type === 'application/pdf' => '📄',
            str_contains($this->mime_type, 'word') => '📝',
            default => '📎',
        };
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getPreviewUrlAttribute(): ?string
    {
        return $this->isImage() ? $this->url : null;
    }

    // EVENTS

    protected static function booted()
    {
        static::deleting(function ($evidence) {
            if ($evidence->isForceDeleting()) {
                Storage::disk('public')->delete($evidence->path);
            }
        });
    }
}
