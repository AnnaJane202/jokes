<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        $users->getCollection()->transform(fn($user) => UserResource::make($user)->resolve());
//        $users = UserResource::collection($users)->resolve();
        return inertia('Admin/User/Index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $postsCount = $user->postsCount;
        $likesCount = $user->likesCount;

        $user = UserResource::make($user)->resolve();

        return inertia('Admin/User/Show', compact('user', 'postsCount', 'likesCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = UserResource::make($user)->resolve();
        $roles = Role::all();
        return inertia('Admin/User/Edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $user = UserService::update($user, $data);
        return UserResource::make($user)->resolve();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            UserService::deleteUserWithAllRelations($user);

            return response()->json([
                'message' => 'success'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return redirect()->back()
            ->with('error', 'Не удалось удалить пост: ' . $e->getMessage());
        }

//         return response()->json([
//             'message' => 'success'
//         ], Response::HTTP_OK);
    }
}
