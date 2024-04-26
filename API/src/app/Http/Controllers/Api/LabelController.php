<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;

class LabelController extends Controller
{
    public function index()
    {
        return Label::all();
    }

    public function store(Request $request)
    {
        $newLabel = new Label();
        foreach ($request->request as $columnName => $columnValue) {
            $newLabel->$columnName = $columnValue;
        }
        $newLabel->save();
        return $newLabel;
    }

    public function show(Label $label ) {
        return $label;
    }

    public function update(Request $request, Label $label)
    {
        if ( isset($request->id) ) {
            return response()->json(["error" => "cannot change id"], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $label->$attributeName = $attributeValue;
        }
        $label->save();
        return $label;
    }

    public function destroy(Label $label) {
        $label->delete();
        return $label;
    }
}
