<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryPropertyRequest;
use App\Models\CategoryProperty;

class CategoryPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category_id = $request->input('category_id');

        $categoryProperties = CategoryProperty::where('category_id', $category_id)->get();

        $data = [
            'category_properties' => $categoryProperties
        ];
        return response(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryPropertyRequest $request)
    {
        $params                 = $request->validated();
        $params['parent_id']    = $request->input('parent_id');

        $categoryProperty       = CategoryProperty::create($params);

        return response(['data' => ['category_property' => $categoryProperty]]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryPropertyRequest $request, string $id)
    {
        $params                 = $request->validated();
        $params['parent_id']    = $request->input('parent_id');
        $categoryProperty       = CategoryProperty::find($id);

        if ($categoryProperty) {
            $categoryProperty->update($params);
            return response(['data' => ['category_property' => $categoryProperty]]);
        } else {
            return response(['error' => 'Category property not found'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoryProperty   = CategoryProperty::find($id);

        if ($categoryProperty) {
            $categoryProperty->delete();
            return response(['message' => 'OK']);
        } else {
            return response(['error' => 'Category property not found'], 400);
        }
    }
}
