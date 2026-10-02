<?php

namespace App\Http\Resources\Violation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViolationWithAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Базовые поля - всегда
        $data = [
            'id' => $this->id,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'reason' => $this->reason,
            'penalty_type' => $this->penalty_type,
            'penalty_label' => $this->getPenaltyLabel(),
            'duration_days' => $this->duration_days,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d.m.Y H:i'),
            'created_at_raw' => $this->created_at,

            // Пользователь (загружаем через отношение)
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'registered_at' => $this->user->created_at->format('d.m.Y H:i'),
                    'total_violations' => $this->user->total_violations,
//                    'avatar' => $this->user->avatar_url ?? null
                ];
            }),

            // Модератор
            'admin' => $this->whenLoaded('admin', function() {
                return [
                    'id' => $this->admin->id,
                    'name' => $this->admin->name,
                ];
            }),





        ];
        // Дедлайн - только если нужно
            if ($this->shouldIncludeDeadline()) {
                $data['deadline'] = $this->created_at->addDays(7)->format('d.m.Y');
                $data['deadline_timestamp'] = $this->created_at->addDays(7)->timestamp;
                $data['days_left'] = now()->diffInDays($this->created_at->addDays(7), false);
                $data['is_deadline_near'] = now()->diffInDays($this->created_at->addDays(7), false) <= 3;
            }

        if ($this->appeal_reason) {
            $data['appeal'] = [
                'reason' => $this->appeal_reason,
//                'contact' => $this->appeal_contact,
//                'submitted_at' => $this->appealed_at?->format('d.m.Y H:i'),
                'evidence' => $this->whenLoaded('appealEvidence', function () {
                        return $this->appealEvidence->map(function ($evidence) {
                            return [
                                'id' => $evidence->id,
                                'url' => $evidence->url,
                                'original_name' => $evidence->original_name,
                            ];
                        });
                    }) ?? [],
            ];
        }

        // Дополнительные данные - только для админов
        if (auth()->user()?->isAdmin) {
            $data['admin_id'] = $this->moderator_id;
            $data['details'] = $this->details;
            $data['appeal_decision'] = $this->appeal_decision;
            $data['appeal_decision_comment'] = $this->appeal_decision_comment;
            $data['appeal_decided_at'] = $this->appeal_decided_at?->format('d.m.Y H:i');
            $data['appeal_decided_by'] = $this->appeal_decided_by;
        }


        return $data;

    }

    /**
     * Определяем, нужно ли показывать дедлайн
     */
    protected function shouldIncludeDeadline(): bool
    {
        // Показываем только для активных нарушений или апелляций
        return in_array($this->status, ['active', 'appealed']);
    }
}
