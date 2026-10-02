<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PublicUserResource extends JsonResource
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
            'name' => $this->name,
            'avatar' => $this->avatar
                ? Storage::url($this->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF',

            'created_at' => $this->created_at->format('d.m.Y'),
            // опционально: количество постов
            'posts_count' => $this->whenCounted('posts'),
            // можно добавить другие публичные поля
        ];
    }
}
