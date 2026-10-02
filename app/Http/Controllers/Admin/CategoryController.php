<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        $categories->getCollection()->transform(fn($category) => CategoryResource::make($category)->resolve());
        return inertia('Admin/Category/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
//        dd($request);
        $data = $request->validated();

        $category = CategoryService::store($data);


        return CategoryResource::make($category)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = CategoryResource::make($category)->resolve();
        return inertia('Admin/Category/Show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $category = CategoryResource::make($category)->resolve();
        return inertia('Admin/Category/Edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Category $category)
    {

        $data = $request->validated();
        $category = CategoryService::update($category, $data);
        return CategoryResource::make($category)->resolve();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}
