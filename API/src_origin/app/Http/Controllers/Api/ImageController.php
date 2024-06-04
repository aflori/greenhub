<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        return Image::all();
    }

    public function store(Request $request)
    {
        $newImage = new Image();
        foreach ($request->request as $columnName => $columnValue) {
            $newImage->$columnName = $columnValue;
        }
        $newImage->save();

        return $newImage;
    }

    public function show(Image $image)
    {
        return $image;
    }

    public function update(Request $request, Image $image)
    {
        if (isset($request->id)) {
            return response()->json(['error' => 'cannot change id'], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $image->$attributeName = $attributeValue;
        }
        $image->save();

        return $image;
    }

    public function destroy(Image $image)
    {
        $image->delete();

        return $image;
    }
}
