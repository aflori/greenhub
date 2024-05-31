<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class XssMiddleWareTest extends TestCase
{
    use RefreshDatabase;

    protected $urlFormat = '/api/product/%s/comment';

    public function test_try_to_use_road_without_connection(): void
    {
        $objects = createUserAndProduct();
        $product = $objects[1];
        $url = sprintf($this->urlFormat, $product->id);

        $response = $this->postJson($url, ['comment' => 'quelquechose']);

        // echo $response;
        $response->assertStatus(401);
    }

    public function test_try_to_use_ok_road(): void
    {
        $objects = createUserAndProduct();
        $user = $objects[0];
        $product = $objects[1];
        $url = sprintf($this->urlFormat, $product->id);

        $response = $this->actingAs($user)->postJson($url, ['comment' => 'quelquechose']);

        // echo $response;
        $response->assertStatus(200);
        $response->assertJson(['comment' => 'quelquechose']);
    }

    public function test_try_to_use_road_with_tag(): void
    {
        $objects = createUserAndProduct();
        $user = $objects[0];
        $product = $objects[1];
        $url = sprintf($this->urlFormat, $product->id);

        $response = $this->actingAs($user)->postJson($url, ['comment' => '<b>quelquechose</b>']);

        // echo $response;
        $response->assertStatus(200);
        $response->assertJson(['comment' => 'quelquechose']);
    }

    /*
    public function test_fail(): void
    {
        $objects = createUserAndProduct();
        $user = $objects[0];
        $product = $objects[1];
        $url = sprintf($this->urlFormat, $product->id);

        $response = $this->actingAs($user)->postJson($url, ["comment" => "quelquechose"]);

        // echo $response;
        $response->assertStatus(200);
        $response->assertJson(["comment" => "quelque chose"]);
    }
     */
}

function createUserAndProduct()
{
    $user = new User;
    $user->first_name = 'Test';
    $user->last_name = 'User';
    $user->pseudoname = 'Test User';
    $user->email = 'test@example.net';
    $user->password = Hash::make('password');
    $user->role = 'client';

    $user->save();

    $brand = new Brand;
    $brand->name = 'brand';

    $brand->save();

    $product = new Product;
    $product->name = 'product name';
    $product->price = 8.25;
    $product->vat_rate = 0;
    $product->stock = 3;
    $product->description = 'Lorem ipsum dolor sit amet, qui minim labore adipisicing minim sint cillum sint consectetur cupidatat.';
    $product->environmental_impact = 2;
    $product->origin = 'France';
    $product->brand_id = $brand->id;

    $product->save();

    return [$user, $product];
}
