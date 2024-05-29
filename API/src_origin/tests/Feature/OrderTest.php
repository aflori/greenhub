<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Order;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $url = "/api/orders";

    public function test_request_form_invalid(): void {
        $object = initDataBase();
        $user = $object[0];
        $product = $object[1];

        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->post($this->url);

        $response->assertStatus(422);
    }

    public function test_request_form_invalid_2(): void {
        $object = initDataBase();
        $user = $object[0];
        $product = $object[1];

        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postJson($this->url, [
                "total_amount" => 11,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => 'La tête à toto',
                    "city" => "Toto",
                    "zip_code" => "0+0",
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 1
                    ]
                ],
            ]);

        $response->assertStatus(422);
    }

    public function test_order_with_shipping_fee_missing_fields() : void {
        $object = initDataBase();
        $user = $object[0];
        $product = $object[1];

        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postJson($this->url, [
                "total_amount" => 11,
                "delivery_date" => "01/01/2025",
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => 'La tête à toto',
                    "city" => "Toto",
                    "zip_code" => "0+0",
                ],
                "delivery_adress" => [
                    "road_number" => 0,
                    "road_name" => 'La tête à toto',
                    "city" => "Toto",
                    "zip_code" => "0+0",
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 1
                    ]
                ],
            ]);

        $response->assertStatus(422);
    }

    public function test_request_not_authentificated(): void {
        $o = initDataBase();
        $response = $this
            //->withSession([])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->post($this->url);

        $response->assertStatus(401);
    }

    public function test_simple_product() : void {
        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        $quantity = 2;

        if($product->stock < $quantity) {
            $product->stock = $quantity + 1;
            $product->save();
        }
        
        $totalPrice = getPriceWithVat($quantity*$product->price, $product->vat_rate);
        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => $quantity
                    ]
                ]
            ]);

        $response->assertStatus(201);

        $order = Order::where('buyer_id', $user->id)->first();
        //dd($order);
        //$response->dd();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data", fn (AssertableJson $json) =>
                $json
                    ->where('id', $order->id)
                    ->where('total_price', $totalPrice)
                    ->has("products", 1)
                    ->has("products.0", fn (AssertableJson $json) => 
                        $json
                            ->where('id', $product->id)
                            ->where('quantity', $quantity)
                    )
                    ->missing('delivery')
                    ->etc()
            )
        );

    }

    public function test_multiple_products() : void {
        Brand::factory()->create();
        $user = User::factory()->create();
        $products = Product::factory(3)->create();

        $quantity = [2, 1, 3];
        $totalPrice = 0;

        foreach ($quantity as $key => $value) {
            $p = $products[$key];
            if($p->stock < $value) {
                $p->stock = $value + 1;
                $p->save();
            }
            $totalPrice += getPriceWithVat($value * $p->price, $p->vat_rate);
        }

        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $products[0]->id,
                        "quantity" => $quantity[0]
                    ],
                    [
                        "id" => $products[1]->id,
                        "quantity" => $quantity[1]
                    ],
                    [
                        "id" => $products[2]->id,
                        "quantity" => $quantity[2]
                    ],
                ]
            ]
        );

        $response->assertStatus(201);

        $order = Order::where('buyer_id', $user->id)->first();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has("data", fn (AssertableJson $json) =>
                $json    
                    ->where('id', $order->id)
                    ->where('total_price', $totalPrice)
                    ->has("products", 3)
                    ->has("products.0", fn (AssertableJson $json) => 
                        $json
                            ->where('id', $products[0]->id)
                            ->where('quantity', $quantity[0])
                    )
                    ->has("products.1", fn (AssertableJson $json) => 
                        $json
                            ->where('id', $products[1]->id)
                            ->where('quantity', $quantity[1])
                    )
                    ->has("products.2", fn (AssertableJson $json) => 
                        $json
                            ->where('id', $products[2]->id)
                            ->where('quantity', $quantity[2])
                    )
                    ->etc()
            )
        );
    }

    public function test_update_stock() : void {
        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        while( $product->stock <= 3) {
            $product = Product::factory()->create();
        }

        $stockBeforeRequest = $product->stock;

        $totalPrice = getPriceWithVat(3*$product->price, $product->vat_rate);
        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 3
                    ]
                ]
            ]);

        $response->assertStatus(201);

        $order = Order::where('buyer_id', $user->id)->first();

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json
                    ->has("products.0", fn (AssertableJson $json) => 
                        $json
                            ->where('id', $product->id)
                            ->has('quantity')
                    )
                    ->etc()
            )
        );

        $product = Product::find($product->id);

        $this->assertEquals($product->stock, $stockBeforeRequest - 3);
    }

    public function test_out_of_stock(): void {
        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        while( $product->stock >= 10) {
            $product->stock = 9;
            $product->save();
        }

        $totalPrice = getPriceWithVat(10*$product->price, $product->vat_rate);
        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 10
                    ]
                ]
            ]);
        
            $response->assertStatus(403);

            $sameProductWithUpdate = Product::find($product->id);
            $this->assertEquals($product->stock, $sameProductWithUpdate->stock);
            $this->assertEquals(Order::count(), 0);
    }

    public function test_not_existing_product() : void {
        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        $product->delete();

        $totalPrice = getPriceWithVat(1*$product->price, $product->vat_rate);
        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 1
                    ]
                ]
            ]);


        $this->assertEquals(Order::count(), 0);
        $response->assertStatus(422);
    }

    // update to confirm adress is ok
    public function test_order_with_delivery() : void {
        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        if ($product->stock < 1) {
            $product->stock = 2;
            $product->save();
        }

        $totalPrice = getPriceWithVat(1*$product->price, $product->vat_rate);
        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 1
                    ]
                ],
                "shipping_fee" => 0.5,
                "delivery_date" => "02/01/2025",
                "delivery_adress" => [
                    "road_number" => 1,
                    "road_name" => "allée des totos",
                    "city" => "Toto",
                    "zip_code" => "00001"
                ]
            ]);


            $response->assertStatus(201);

            $order = Order::where('buyer_id', $user->id)->first();

            $response->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn (AssertableJson $json) =>
                    $json->where('id', $order->id)
                        ->where('total_price', $totalPrice)
                        ->has("products", 1)
                        ->has('delivery')
                        ->has('delivery.adress', fn (AssertableJson $json) =>
                            $json->where("road_number", 1)
                                ->where("road_name", "allée des totos")
                                ->where("city", "Toto")
                                ->where("zip_code", "00001")
                        )
                        ->where('delivery.fee', 0.5)
                        ->where('delivery.date', "02/01/2025")
                        ->etc()
                )
                
            );
    }

    public function test_wrong_total_amount() : void {
        $this->markTestSkipped();

        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        $totalPrice = getPriceWithVat(2*$product->price, $product->vat_rate);
        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postjson($this->url, [
                "total_amount" => $totalPrice,
                "facturation_adress" => [
                    "road_number" => 0,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00000"
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "quantity" => 1
                    ]
                ]
            ]);


            $response->assertStatus(403);
            $this->assert(Order::count(), 0);
    }

    /* to implement later
    public function test_stock_failed_after_other_command(): void {}
    public function test_order_date_present_in_response(): void {}
    public function test_order_table_has_correct_field() : void {}
    public function test_correct_value_pivot_table_with_products(): void {}
    public function test_same_product_has_2_command(): void {}
    public function test_adress_is_ok(): void {}
    public function test_existing_adress_facturation(): void {}
    public function test_existing_adress_delivery(): void {}
    public function test_same_adress_facturation_and_delivery(): void {}
    */
}


function initDataBase() {
    $user = User::factory()->create();
    Brand::factory()->create();
    $product = Product::factory()->create();

    return [ $user, $product ];
}

function getPriceWithVat(float $priceWithoutVat, float $vatRate): float {
    return $priceWithoutVat * (1 + $vatRate);
}