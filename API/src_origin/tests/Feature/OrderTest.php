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

    public function test_simple_product() : void {

    }

    public function test_multiple_products() : void {

    }

    public function test_update_stock() : void {

    }

    public function test_not_existing_product() : void {

    }

    public function test_wrong_total_amount() : void {

    }

    public function test_wrong_vat_amount() : void {

    }

    public function test_wrong_total_raw_price() : void {

    }

    public function test_order_with_shipping_fee() : void {

    }

    public function test_order_with_shipping_fee_missing_fields() : void {

    }

    public function test_out_of_stock(): void {

    }
}


function initDataBase() {
    $user = User::factory()->create();
    Brand::factory()->create();
    $products = Product::factory(5)->create();

    return [ $user, $products[0] ];
}
