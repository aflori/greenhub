<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Adress;
use App\Models\User;
require_once __DIR__ .'/../utility.php';

class LastAssociationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderHasProductPivotTableValues = [
            "quantity"=> 2,
            "unit_price" => 13.5,
            "unit_price_vat" => 0.15
        ];
        createManyToManyToAllParent(Order::class, Product::class, "products", 2, $orderHasProductPivotTableValues);
        createManyToManyToAllParent(User::class, Adress::class, "registeredAdress", 2);
        createManyToManyToAllParent(Product::class, Category::class, "categories", 2);
    }
}
