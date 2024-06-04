<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }

    public function store(Request $request)
    {
        $newBrand = new Brand();
        foreach ($request->request as $columnName => $columnValue) {
            $newBrand->$columnName = $columnValue;
        }
        $newBrand->save();

        return $newBrand;
    }

    public function show(Brand $brand)
    {
        return $brand;
    }

    public function update(Request $request, Brand $brand)
    {
        if (isset($request->id)) {
            return response()->json(['error' => 'cannot change id'], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $brand->$attributeName = $attributeValue;
        }
        $brand->save();

        return $brand;
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return $brand;
    }
}
