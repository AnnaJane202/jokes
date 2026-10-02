<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $posts = $category->posts()
            ->where('published', 1)
            ->with(['user', 'category'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        $posts->getCollection()->transform(fn($post) => PostResource::make($post)->resolve());

        return inertia('Client/Category/Show', [
            'category' => CategoryResource::make($category)->resolve(),
            'posts' => $posts,
        ]);
    }
}
