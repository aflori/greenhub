<?php

namespace Database\Seeders;

use App\Models\BlogArticle;
use App\Models\Product;
use Illuminate\Database\Seeder;

require_once __DIR__.'/../utility.php';

class BlogArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogArticle::factory(25)->create()
            ->each(
                createManyToManyRelationships(Product::class, 'relatedProducts')
            );

    }
}
