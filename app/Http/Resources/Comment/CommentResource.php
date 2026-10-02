<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'post_id' => $this->post_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar_url, // используем аксессор
            ],
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_raw' => $this->created_at->toIso8601String(),
            'children' => $this->children ? CommentResource::collection($this->children)->resolve() : [],
            'parent_id' => $this->parent_id,
        ];
    }
}
