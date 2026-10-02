<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppealEvidence extends Model
{
    protected $fillable = [
        'violation_id',
        'user_id',
        'filename',
        'original_name',
        'path',
        'url',
        'size',
        'mime_type',
    ];
    public function violation() {
        return $this->belongsTo(Violation::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
