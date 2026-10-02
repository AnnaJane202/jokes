<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\StoreRequest;
use App\Http\Requests\Admin\Post\UpdateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);
        $posts->getCollection()->transform(fn($post) => PostResource::make($post)->resolve());
//        $posts = PostResource::collection($posts)->resolve();
        $categories = CategoryResource::collection(Category::all())->resolve();
        return inertia('Admin/Post/Index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/Post/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['comments.user']);
        $comments =  CommentResource::collection($post->comments)->resolve();
        $post = PostResource::make($post)->resolve();

        return inertia('Admin/Post/Show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $post = PostResource::make($post)->resolve();
        $categories = CategoryResource::collection(Category::all())->resolve();
        return inertia('Admin/Post/Edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
//        dd($post);
        $data = $request->validated();
        PostService::update($post, $data);

//        return redirect()
//            ->route('admin.posts.index')
//            ->with('success', 'Пост успешно обновлён');
        return PostResource::make($post)->resolve();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            PostService::deletePostWithLikes($post);

            return response()->json([
                'message' => 'success'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Не удалось удалить пользователя: ' . $e->getMessage());
        }
    }
}
