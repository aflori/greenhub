<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Label;
use App\Models\Product;
require_once __DIR__ .'/../utility.php';

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::factory(15)->create();

        createManyToManyToAllParent(Product::class, Label::class, "labels", 1);
    }
}
