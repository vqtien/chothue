<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryProperty;
use App\Models\Crumb;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $parent_id      = $request->input('parent_id') ?? null;
        $categories     = Category::where("parent_id", $parent_id)->get();

        $data = [
            'categories' => $categories
        ];
        return response(['data' => $data]);
    }

    /**
     * Show
     */
    public function show(string $id)
    {
        $category     = Category::find($id);
        $crumbs       = Crumb::crumbLocation($id);

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
     * Category for post
     */
    public function getCategories()
    {
        $category_properties = CategoryProperty::with('childrens')
            ->whereNull('parent_id')
            ->get();

        $categories = Category::selectRaw("id, name, slug, parent_id, photo_url, sorted")
            ->with('childrens')
            ->whereNull("parent_id")
            ->get();

        $data = [
            'categories'            => $categories,
            'category_properties'   => $category_properties
        ];
        return response(['data' => $data]);
    }
}
