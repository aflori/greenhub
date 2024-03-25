<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return Order::all();
    }

    public function store(Request $request)
    {
        $newOrder = new Order();
        foreach ($request->request as $columnName => $columnValue) {
            $newOrder->$columnName = $columnValue;
        }
        $newOrder->save();
        return $newOrder;
    }

    public function show(Order $order ) {
        return $order;
    }

    public function update(Request $request, Order $order)
    {
        if ( isset($request->id) ) {
            return response()->json(["error" => "cannot change id"], 404);
        }

        foreach ($request->request as $attributeName => $attributeValue) {
            $order->$attributeName = $attributeValue;
        }
        $order->save();
        return $order;
    }

    public function destroy(Order $order) {
        $order->delete();
        return $order;
    }
}
