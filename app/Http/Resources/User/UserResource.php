<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'email' => $this->email,
//            'role' => User::where('id', $this->id)->get()->roles->pluck('name'),
            'avatar' => $this->avatar
                ? Storage::url($this->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF',
            'role' => $this->roles->pluck('title'),
            'is_admin' => $this->is_admin,

            'created_at'=> Carbon::parse($this->created_at)->format('d.m.Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d.m.Y'),

            'is_banned' => $this->is_banned,
            'ban_reason' => $this->ban_reason,
            'banned_until' => $this->banned_until,
            'banned_by' => $this->banned_by,

        ];

//        return parent::toArray($request);
    }
}
