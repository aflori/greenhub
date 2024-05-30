<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        return Discount::all();
    }

    public function store(Request $request)
    {
        $newDiscount = new Discount();
        foreach ($request->request as $columnName => $columnValue) {
            $newDiscount->$columnName = $columnValue;
        }
        $newDiscount->save();

        return $newDiscount;
    }

    public function show(Discount $discount)
    {
        return $discount;
    }

    public function update(Request $request, Discount $discount)
    {
        if (isset($request->id)) {
            return response()->json(['error' => 'cannot change id'], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $discount->$attributeName = $attributeValue;
        }
        $discount->save();

        return $discount;
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return $discount;
    }
}
