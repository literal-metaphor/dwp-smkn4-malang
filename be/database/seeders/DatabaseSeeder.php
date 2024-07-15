<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a superadmin user
        User::create([
            'id' => Str::uuid(),
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('superadmin'), // !change this into client's request during production
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'cell_phone_number' => '1234567890',
            'country_code' => '62',
            'is_admin' => true,
        ]);
    }
}
