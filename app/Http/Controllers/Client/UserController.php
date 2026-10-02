<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\PublicUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('title', ['admin']);
        })
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        $users->getCollection()->transform(fn($user) => PublicUserResource::make($user)->resolve());
//        dd($users);

        return inertia('Client/User/Index', [
            'users' => $users,
            'filters' => $request->only('search'),
        ]);
    }

    public function show(User $user)
    {
        // Загружаем посты пользователя (опционально)
        $posts = $user->posts()
            ->where('published', 1)
            ->latest()
            ->paginate(10);

        // Применяем ресурс для постов
        $posts->getCollection()->transform(fn($post) => PostResource::make($post)->resolve());

        return inertia('Client/User/Show', [
            'user' => PublicUserResource::make($user)->resolve(),
            'posts' => $posts,
        ]);
    }
}
