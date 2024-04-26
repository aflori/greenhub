<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogArticle;
use App\Models\Product;
require_once __DIR__ .'/../utility.php';

class BlogArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogArticle::factory(25)->create()
        ->each(
            createManyToManyRelationships(Product::class, "relatedProducts")
        );

    }
}
