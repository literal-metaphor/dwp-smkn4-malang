<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use App\Models\ProductPhoto;
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
        try {
            Product::findOrFail($id)->delete();
            return response()->json(['message' => 'Product deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all photos of a product
     */
    public function getPhotos(string $id) {
        $photo_ids = ProductPhoto::where('product_id', $id)->get()->pluck('photo_id');
        $files = File::whereIn('id', $photo_ids)->get();
        return response()->json($files);
    }

    /**
     * Add photo to a product
     */
    public function addPhoto(Request $req, string $shop_id, string $id) {
        $this->assertAuthorized($req, $shop_id);

        $req->validate(['file' => 'required|mimes:jpeg,png,jpg,svg']);

        // Check if the product exists
        Product::findOrFail($id);

        try {
            $filename = $this->uploadFile($req);
            $file = File::create([
                'id' => Str::uuid(),
                'filename' => $filename,
            ]);

            ProductPhoto::create([
                'id' => Str::uuid(),
                'product_id' => $id,
                'photo_id' => $file->id,
            ]);

            $photo_ids = ProductPhoto::where('product_id', $id)->get()->pluck('photo_id');
            $files = File::whereIn('id', $photo_ids)->get();

            return response()->json($files);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a photo from a product
     */
    public function deletePhoto(Request $req, string $shop_id, string $id, string $photo_id) {
        $this->assertAuthorized($req, $shop_id);

        // Check if the product exists
        Product::findOrFail($id);

        // Delete the file and handle any errors
        try {
            // For some reason, File::findOrFail doesn't work. It doesn't stop even though the file doesn't exist. But it works, so let's just roll with it.
            $file = File::findOrFail($photo_id);
            $this->deleteFile($file->filename);
            ProductPhoto::where('product_id', $id)->where('photo_id', $photo_id)->delete();
            return response()->json(['message' => 'Photo deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
