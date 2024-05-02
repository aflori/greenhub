<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Discount;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(150)
            ->hasImages(1)
            ->create();
        Product::factory(50)
          ->withDiscount(1)
          ->hasImages(1)
          ->create();
    }
}
