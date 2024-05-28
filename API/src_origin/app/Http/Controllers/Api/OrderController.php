<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Adress;


class OrderController extends Controller
{
    /*
        public function index()
        {
            return Order::all();
        }
    */

    /**
     * create an order
     *
     * create an order to an user where order data is on the request.
     */
    public function store(CreateOrderRequest $request)
    {
        $newOrder = new Order();

        // $newOrder->save();
        // return $newOrder;
        $user = User::factory()->create();
        $adress = Adress::factory()->create();

        $newOrder->number = 0;
        $newOrder->order_date = "1/1/2020";
        $newOrder->delivery_date = "1/1/2020";
        $newOrder->bill = 0.0;
        $newOrder->vat_rate = 0.0;
        $newOrder->shipping_fee = 0.0;
        $newOrder->total_price = 0.0;
        $newOrder->buyer_id = $user->id;
        $newOrder->facturation_adress = $adress->id;
        $newOrder->delivery_adress = $adress->id;
        $newOrder->save();
        return $newOrder;
    }

    /*
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
    */
}
