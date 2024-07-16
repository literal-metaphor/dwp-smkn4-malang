<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Validate full product data
     */
    private function validateProductData(Request $req) {
        return $this->validateRequest($req, [
            'shop_id' => 'required|uuid|exists:shops,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'string|nullable',
            'category' => 'required|string|in:food,drink,female_fashion,male_fashion,child_fashion,furniture',
        ]);
    }

    /**
     * Check if the user is the shopkeeper of the shop or an admin
     */
    public function assertAuthorized(Request $req, string $shop_id) {
        // Check if the user is an admin
        if (!User::where('remember_token', $req->bearerToken())->first()->is_admin) {
            // Check if the user is the shopkeeper of the shop
            $user = User::where('remember_token', $req->bearerToken())->first();
            $shop = Shop::findOrFail($shop_id);
            if ($shop->owner_id !== $user->id) {
                return response()->json(['message' => 'User is not the shopkeeper of this shop'], 401);
            }
        }
    }

    /**
     * Index all products
     */
    public function index() {
        return response()->json(Product::orderBy('created_at', 'desc')->get());
    }

    /**
     * Show a product by ID
     */
    public function show(string $id) {
        return response()->json(Product::findOrFail($id));
    }

    /**
     * Create a new product
     */
    public function store(Request $req) {
        $data = $this->validateProductData($req);

        // Check if the user is the shopkeeper of the shop
        $this->assertAuthorized($req, $data['shop_id']);

        // Create the product and handle any errors
        try {
            $data['id'] = Str::uuid();
            return response()->json(Product::create($data));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a product
     */
    public function update(Request $req, string $id) {
        $data = $this->validateProductData($req);

        // Check if the user is the shopkeeper of the shop
        $this->assertAuthorized($req, $data['shop_id']);

        // Update the product and handle any errors
        try {
            Product::findOrFail($id)->update($data);
            return response()->json(Product::findOrFail($id));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy(Request $req, string $shop_id, string $id) {
        $this->assertAuthorized($req, $shop_id);
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
