<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Post\StoreRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\PublicUserResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categories = CategoryResource::collection($categories)->resolve();
        $posts = Post::where('published', 1)->withCount('comments')->paginate(10);
        $posts->getCollection()->transform(fn($post) => PostResource::make($post)->resolve());

//        dd($posts);

        $topAuthors = User::withCount('posts')->orderBy('posts_count', 'desc')->limit(5)->get();
        $topAuthors = PublicUserResource::collection($topAuthors)->resolve();
//        dd($topAuthors);

        $recommendedUsers = User::where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $recommendedUsers = PublicUserResource::collection($recommendedUsers)->resolve();
        $type = 'post';


        return inertia('Client/Post/Index', compact('categories', 'posts', 'type', 'topAuthors', 'recommendedUsers'));
    }

    public function myPosts(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $posts = $user->posts()
            ->with(['category'])
            ->withCount(['comments', 'likes'])
            ->when($request->status === 'published', fn($q) => $q->where('published', true))

            ->when($request->status === 'draft', fn($q) => $q->where('published', false))
            ->latest()
            ->paginate(10);

        $posts->getCollection()->transform(fn($post) => PostResource::make($post)->resolve());

        return inertia('Client/Profile/Post/Index', [
            'posts' => $posts,
            'filters' => $request->only(['status']),
            'stats' => [
                'total' => auth()->user()->posts()->count(),
                'published' => auth()->user()->posts()->where('published', true)->count(),
                'draft' => auth()->user()->posts()->where('published', false)->count(),
            ],
        ]);


    }

    public function show(Post $post)
    {
        // Загружаем все необходимые отношения
        $post->load([
            'user',
            'category',
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with(['children.user', 'user']);
            }
        ]);

//        dd($post->comments);


        return inertia('Client/Post/Show', [
            'post' => PostResource::make($post)->resolve(),
            'comments' => CommentResource::collection($post->comments)->resolve(),
        ]);
    }

    public function create()
    {
        $categories = CategoryResource::collection(Category::all())->resolve();
        return inertia('Client/Post/Create', compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $post = PostService::store($data);
//        return redirect()
//            ->route('/')
//            ->with('success', 'Пост успешно обновлён');

        return PostResource::make($post)->resolve();
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete(); // soft delete, если есть trait

        return redirect()
            ->route('post.index')
            ->with('success', 'Пост удалён');
    }
}
