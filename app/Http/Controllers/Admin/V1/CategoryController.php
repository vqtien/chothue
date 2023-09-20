<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Crumb;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parent_id  = $request->input('parent_id') ?? null;
        $categories = Category::where("parent_id", $parent_id)->get();

        $data = [
            'categories' => $categories
        ];
        return response(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $params                 = $request->validated();
        $params['sorted']       = $request->input('sorted');
        $params['slug']         = makeSlug($request->input('name'));
        $params['parent_id']    = $request->input('parent_id');
        $params['image_url']    = $request->input('image_url');

        $category               = Category::create($params);

        return response(['data' => ['category' => $category]]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category     = Category::find($id);
        $crumbs       = Crumb::crumbCategory($id);

        if ($category) {
            return response(['data' => [
                'category'  => $category,
                'crumbs'    => $crumbs
            ]]);
        } else {
            return response(['error' => 'Category not found'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $params                 = $request->validated();
        $params['sorted']       = $request->input('sorted');
        $params['slug']         = makeSlug($request->input('name'));
        $params['parent_id']    = $request->input('parent_id');
        $params['image_url']    = $request->input('image_url');

        $category               = Category::find($id);

        if ($category) {
            $category->update($params);
            return response(['data' => ['category' => $category]]);
        } else {
            return response(['error' => 'Category not found'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category               = Category::find($id);

        if ($category) {
            $category->delete();
            return response(['message' => 'OK']);
        } else {
            return response(['error' => 'Category not found'], 400);
        }
    }
}
