<?php

namespace Database\Seeders;

use App\Models\Product;
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
        $superadmin = User::create([
            'id' => Str::uuid(),
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('superadmin'), // !change this into client's request during production
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'is_admin' => true,
        ]);

        // Create a merchant user
        $merchant = User::create([
            'id' => Str::uuid(),
            'username' => 'merchant',
            'email' => 'merchant@example.com',
            'password' => bcrypt('merchant'), // !change this into client's request during production
            'first_name' => 'Merchant',
            'last_name' => 'User',
            'is_shop' => true,
        ]);
        // $shop = Shop::create([
        //     'id' => Str::uuid(),
        //     'name' => 'Merchant Shop',
        //     'owner_id' => $merchant->id
        // ]);

        // Create some products
        $productData = [];
        for ($i = 0; $i < 100; $i++) {
            $productData[] = [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => fake()->word(),
                'description' => fake()->sentence(),
                'price' => fake()->numberBetween(1000, 1000000),
                'category' => fake()->randomElement(['food', 'drink', 'female_fashion', 'male_fashion', 'child_fashion', 'furniture']),
            ];
        }
        foreach ($productData as $product) {
            Product::create($product);
        }
    }
}
