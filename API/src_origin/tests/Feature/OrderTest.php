<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Brand;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected $url = "/api/orders";

    public function test_request_form_invalid(): void
    {
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

    public function test_request_form_invalid_2(): void
    {
        $object = initDataBase();
        $user = $object[0];
        $product = $object[1];

        $response = $this
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->actingAs($user)
            ->postJson($this->url, [
                "total_amount" => 22,
                "total_vat" => 2,
                "total_product_price" => 20,
                "shipping_fee" => 0,
                "delivery_date" => "01/01/2025",
                "facturation_adress" => [
                    "road_number" => 18,
                    "road_name" => "Avenus des totos",
                    "city" => "la tête à toto",
                    "zip_code" => "00000",
                ],
                "delivery_adress" => [
                    "road_number" => 18,
                    "road_name" => "Avenus des totos",
                    "city" => "la tête à toto",
                    "zip_code" => "00000",
                ],
                "products" => [
                    [
                        "id" => $product->id,
                        "unit_price" => '0',
                        "quantity_buyed" => 2,
                        "total_price" => 22
                    ]
                ]
            ]);

        $response->assertStatus(422);
    }

    public function test_request_not_authentificated(): void
    {
        $o = initDataBase();
        $response = $this
            //->withSession([])
            ->withHeaders([
                'accept' => 'application/json',
            ])
            ->post($this->url);

        $response->assertStatus(401);

    }
}


function initDataBase() {
    $user = User::factory()->create();
    Brand::factory()->create();
    $products = Product::factory(5)->create();

    return [ $user, $products[0] ];
}
