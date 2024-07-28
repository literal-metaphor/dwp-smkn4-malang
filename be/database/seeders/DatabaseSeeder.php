<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
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
        $productData = [
            [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => 'Pizza',
                'description' => 'A delicious pizza',
                'price' => 10000,
                'category' => 'food',
            ],
            [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => 'Cola',
                'description' => 'A popular soda drink',
                'price' => 2000,
                'category' => 'drink',
            ],
            [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => 'Little Black Dress',
                'description' => 'A classic little black dress for women',
                'price' => 50000,
                'category' => 'female_fashion',
            ],
            [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => 'Formal Suit',
                'description' => 'A high-quality formal suit for men',
                'price' => 150000,
                'category' => 'male_fashion',
            ],
            [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => "Children's T-Shirt",
                'description' => 'A fun and colorful t-shirt for children',
                'price' => 10000,
                'category' => 'child_fashion',
            ],
            [
                'id' => Str::uuid(),
                'owner_id' => $merchant->id,
                'name' => 'Modern Sofa',
                'description' => 'A comfortable and stylish sofa for your living room',
                'price' => 500000,
                'category' => 'furniture',
            ],
        ];
        foreach ($productData as $data) {
            Product::create($data);
        }
    }
}
