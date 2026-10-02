<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\User\UserResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'likes' => $this->likes,
            'description' => $this->description,
            'content' => $this->content,
            'published' => $this->published,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'updated_at' => Carbon::parse($this->updated_at)->format('d.m.Y'),
//            'category' => CategoryResource::make(Category::where('id', $this->category_id)->get())->resolve(),
            'category' => CategoryResource::collection(Category::where('id', $this->category_id)->get())->resolve(),
//            'category' => CategoryResource::make($this->category)->resolve(),
//            'category_title' => Category::where('id', $this->category_id)->title,
//            'user' => UserResource::make(User::where('id', $this->user_id)->get())->resolve(),
            'user' => UserResource::make(User::find($this->user_id))->resolve(),

            // Данные о лайках
            'likes_count' => $this->likes_count,
            'is_liked_by_user' => $this->isLikedByUser(auth()->id()),
            'comments_count' => $this->comments_count ?? 0, // из withCount

            // Если нужны пользователи, которые лайкнули
//            'liked_users' => UserResource::collection($this->whenLoaded('likedUsers')),
        ];
    }
}
