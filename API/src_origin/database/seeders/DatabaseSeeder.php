<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            DiscountSeeder::class,
            ProductSeeder::class,
            CategorySeeder::class,
            BlogArticleSeeder::class,
            CompanySeeder::class,
            LabelSeeder::class,
            AdressSeeder::class,
            UserSeeder::class,
            CommentSeeder::class,
            OrderSeeder::class,
            LastAssociationTableSeeder::class,
        ]);
    }
}
