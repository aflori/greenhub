<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Product;
use Illuminate\Database\Seeder;

require_once __DIR__.'/../utility.php';

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Label::factory(15)->create();

        createManyToManyToAllParent(Product::class, Label::class, 'labels', 1);
    }
}
