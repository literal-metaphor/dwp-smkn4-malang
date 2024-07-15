<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Validate full shop data
     */
    private function validateShopData(Request $req) {
        return $this->validateRequest($req, [
            'name' => 'required|string|max:255',
            'owner_id' => 'required|uuid|exists:users,id',
        ]);
    }

    /**
     * Get a list of shops by limit
     */
    public function index() {
        return response()->json(Shop::orderBy('created_at', 'desc')->limit(10)->get());
    }

    /**
     * Show a shop by ID
     */
    public function show(string $id) {
        return response()->json(Shop::findOrFail($id));
    }

    /**
     * Create a new shop
     */
    public function store(Request $req) {
        $data = $this->validateShopData($req);

        // Create the shop and handle any errors
        try {
            // Error if shop already exists
            if (Shop::where('owner_id', $data['owner_id'])->first()) {
                return response()->json(['message' => 'This user already has a shop'], 409);
            }

            $data['id'] = Str::uuid();
            return response()->json(Shop::create($data));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a shop
     */
    public function update(Request $req, string $id) {
        $data = $this->validateShopData($req);

        // Update the shop and handle any errors
        try {
            Shop::findOrFail($id)->update($data);
            return response()->json(Shop::findOrFail($id));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a shop
     */
    public function destroy(Request $req, string $id) {
        Shop::where('id', $id)->firstOrFail()->delete();
        return response()->json(['message' => 'Shop deleted']);
    }
}
