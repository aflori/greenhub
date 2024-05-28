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
                        "quantity" => 2
                    ]
                ]
            ]);

        $response->assertStatus(201);

        $order = Order::where('buyer_id', $user->id)->get();

        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->where('id', $order->id)
                ->where('total_price', $totalPrice)
                ->has("products", 1)
                ->has("products.0", fn (AssertableJson $json) => 
                    $json
                        ->where('id', $product->id)
                        ->where('quantity', 2)
                )
                ->missing('delivery')
                ->etc()
        );

    }

    public function test_multiple_products() : void {
        $this->markTestSkipped();

        Brand::factory()->create();
        $user = User::factory()->create();
        $products = Product::factory(3)->create();

        $quantity = [2, 1, 3];
        $totalPrice = 0;

        foreach ($quantity as $key => $value) {
            $p = $products[$key];
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

        $order = Order::where('buyer_id', $user->id)->get();

        $response->assertJson(fn (AssertableJson $json) =>
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
            );
    }

    public function test_update_stock() : void {
        $this->markTestSkipped();

        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

        while( $product->stock <= 3) {
            $product = Product::factory()->create();
        }

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

        $order = Order::where('buyer_id', $user->id)->get();

        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->has("products.0", fn (AssertableJson $json) => 
                    $json
                        ->where('id', $product->id)
                )
                ->etc()
        );

        $updatedProduct = Product::find($product->id);

        $this->assertEquals($updatedProduct->stock, $product->stock - 3);
    }

    public function test_out_of_stock(): void {
        $this->markTestSkipped();

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

    public function test_order_with_delivery() : void {
        $this->markTestSkipped();
        
        $o = initDataBase();
        $user = $o[0];
        $product = $o[1];

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
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00001"
                ]
            ]);


            $response->assertStatus(201);

            $order = Order::where('buyer_id', $user->id)->get();
    
            $response->assertJson(fn (AssertableJson $json) =>
                $json
                    ->where('id', $order->id)
                    ->where('total_price', $totalPrice)
                    ->has("products", 1)
                    ->has("products.0", fn (AssertableJson $json) => 
                        $json
                            ->where('id', $product->id)
                            ->where('quantity', 1)
                    )
                    ->has('delivery')
                    ->has('delivery.adress')
                    ->has('delivery.fee')
                    ->has('delivery.date')
                    ->etc()
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