<?php

namespace App\Http\Resources\Violation;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\User\UserResource;
use App\Models\AppealEvidence;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ViolationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => User::where('id', $this->user_id)->first(),
//            'admin_id' => $this->admin_id,
            'admin' => $this->whenLoaded('admin', fn() => $this->admin ? [
                'id' => $this->admin->id,
                'name' => $this->admin->name,
            ] : null),
            'details' => $this->details,
            'type_label' => $this->getTypeLabel(),
            'reason' => $this->reason,
            'penalty_label' => $this->getPenaltyLabel(),
            'created_at' => $this->created_at?->toIso8601String(),
            'deadline' => $this->created_at?->copy()->addDays(7)->format('d.m.Y'),
            'days_left' => $this->created_at ? now()->diffInDays($this->created_at->copy()->addDays(7), false) : null,
            'deadline_timestamp' => $this->created_at->addDays(7)->timestamp, // ← добавляем timestamp
            'is_deadline_near' => now()->diffInDays($this->created_at->addDays(7), false) <= 3, // ← флаг
            'appeal_reason' => $this->appeal_reason,
            'appeal_evidence' => AppealEvidence::where('violation_id', $this->id)->get(),
        ];
    }
}
