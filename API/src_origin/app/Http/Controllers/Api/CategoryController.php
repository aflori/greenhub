<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $newCategory = new Category();
        foreach ($request->request as $columnName => $columnValue) {
            $newCategory->$columnName = $columnValue;
        }
        $newCategory->save();

        return $newCategory;
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(Request $request, Category $category)
    {
        if (isset($request->id)) {
            return response()->json(['error' => 'cannot change id'], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $category->$attributeName = $attributeValue;
        }
        $category->save();

        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $category;
    }
}
