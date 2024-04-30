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

    public function test_request_not_authentificated(): void
    {
        $o = initDataBase();
        $response = $this
            ->withSession([])
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
    $product = Product::factory()->create();

    return [ $user, $product ];
}
