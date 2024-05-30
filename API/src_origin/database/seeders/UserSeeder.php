<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(25)->create();
        User::factory(2)->grantAdminRole()->create();
        User::factory(5)->grantCompanyRole()->create();
    }
}
