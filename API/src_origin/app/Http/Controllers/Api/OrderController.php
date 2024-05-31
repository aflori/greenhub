<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\OrderRessource;
use App\Models\Adress;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

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
        $user = $request->user();
        $facturationAdress = Adress::factory()->create();

        $deliveryAdress = getAdressModel($request->post('delivery_adress')) ?? $facturationAdress;
        $totalAmount = $request->post('total_amount');
        $productsBuyed = $request->post('products');
        $facturationDate = '01/01/01';
        $deliveryDate = $request->post('delivery_date') ?? $facturationDate;
        $shippingFee = $request->post('shipping_fee') ?? 0.0;

        if (isInvalidStock($productsBuyed)) {
            return response()->json(['error' => 'unavailable products'], 403);
        }

        if (isInvalidGlobalAmount($totalAmount, $productsBuyed, $shippingFee)) {
            return response()->json(['error' => 'incorrect total amount price'], 403);
        }

        $newOrder = new Order();

        // $newOrder->save();
        // return $newOrder;

        $newOrder->number = 0;
        $newOrder->order_date = $facturationDate;
        $newOrder->delivery_date = $deliveryDate;
        $newOrder->bill = 0.0;
        $newOrder->vat_rate = 0.0;
        $newOrder->shipping_fee = $shippingFee;
        $newOrder->total_price = $totalAmount;
        $newOrder->buyer_id = $user->id;
        $newOrder->facturation_adress = $facturationAdress->id;
        $newOrder->delivery_adress = $deliveryAdress->id;
        $newOrder->save();

        foreach ($productsBuyed as $product) {

            $productBuyedId = $product['id'];
            $productBuyedQuantity = $product['quantity'];
            $productBuyed = \App\Models\Product::query()->find($productBuyedId);

            $newOrder->products()->attach($productBuyed, [
                'quantity' => $productBuyedQuantity,
                'unit_price' => '0',
                'unit_price_vat' => 0.0,
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

function isInvalidStock(array $listOfProductBuyed): bool
{
    return ! isValidStock($listOfProductBuyed);
}

function isValidStock(array $listOfProductBuyed): bool
{
    foreach ($listOfProductBuyed as $buyedProduct) {
        $dbProduct = \App\Models\Product::query()->find($buyedProduct['id']);
        if ($dbProduct->stock < $buyedProduct['quantity']) {
            return false;
        }
    }

    return true;
}

function getAdressModel(?array $adressArray): ?Adress
{
    if ($adressArray === null) {
        return null;
    }

    $adress = new Adress();
    $adress->number = $adressArray['road_number'];
    $adress->road = $adressArray['road_name'];
    $adress->postal_code = $adressArray['zip_code'];
    $adress->city = $adressArray['city'];
    $adress->country = 'France';
    $adress->save();

    return $adress;
}

function isInvalidGlobalAmount(float $totalAmount, array $productsBuyed, float $shippingFee): bool
{
    return ! isValidGlobalAmount($totalAmount, $productsBuyed, $shippingFee);
}

function isValidGlobalAmount(float $totalAmount, array $productsBuyed, float $shippingFee): bool
{
    $trueTotalAmount = $shippingFee;
    $totalAmount = round($totalAmount, 2);

    foreach ($productsBuyed as $productBuyed) {
        $product = \App\Models\Product::query()->find($productBuyed['id']);
        $quantity = $productBuyed['quantity'];

        $unitProductPrice = getUnitProductPrice($product);
        $trueTotalAmount += $quantity * $unitProductPrice;
    }

    $trueTotalAmount = round($trueTotalAmount, 2);

    return $trueTotalAmount === $totalAmount;
}

function getUnitProductPrice(Product $product): float
{
    $price = $product->price;
    $vat = $product->vat_rate * $price;

    return $price + $vat;
}
