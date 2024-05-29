<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Adress;

use App\Http\Resources\OrderRessource;

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
        $user = $request->user();
        $adress = Adress::factory()->create();

        $totalAmount = $request->post("total_amount");
        $productsBuyed = $request->post("products");

        $newOrder->number = 0;
        $newOrder->order_date = "1/1/2020";
        $newOrder->delivery_date = "1/1/2020";
        $newOrder->bill = 0.0;
        $newOrder->vat_rate = 0.0;
        $newOrder->shipping_fee = 0.0;
        $newOrder->total_price = $totalAmount;
        $newOrder->buyer_id = $user->id;
        $newOrder->facturation_adress = $adress->id;
        $newOrder->delivery_adress = $adress->id;
        $newOrder->save();

        foreach( $productsBuyed as $product) {

            $productBuyedId = $product['id'];
            $productBuyedQuantity = $product['quantity'];
            $productBuyed = Product::find($productBuyedId);
    
            $newOrder->products()->attach($productBuyed, [
                "quantity" => $productBuyedQuantity,
                "unit_price" => "0",
                "unit_price_vat" => 0.0
            ]);

            $productBuyed->stock -= $productBuyedQuantity;
            $productBuyed->save();
        }

        return new OrderRessource($newOrder);
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
