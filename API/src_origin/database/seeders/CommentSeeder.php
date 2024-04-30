<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Company;
use App\Models\BlogArticle;
use App\Models\Product;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::factory(70)->create()->
        each( actionToCreatePolymorphicTable() );
    }
}
function actionToCreatePolymorphicTable(): callable {
    return function (Comment $comment) {
        createPolymorphicTable($comment,
                [ Company::class, BlogArticle::class, Product::class],
                "coment_on_table", "table_key" );
    };
}