<?php

namespace Database\Seeders;

use App\Models\Adress;
use Illuminate\Database\Seeder;

class AdressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Adress::factory(30)->create();
    }
}
