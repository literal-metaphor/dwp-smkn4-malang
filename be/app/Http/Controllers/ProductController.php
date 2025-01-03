<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Shop;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Validate full product data
     */
    private function validateProductData(Request $req) {
        return $this->validateRequest($req, [
            'owner_id' => 'required|uuid|exists:users,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'string|nullable',
            'category' => 'required|string|in:food,drink,female_fashion,male_fashion,child_fashion,furniture',
        ]);
    }

    /**
     * Check if the user is the shopkeeper of the shop or an admin
     */
    public function assertAuthorized(Request $req) {
        // Check if the user is an admin
        if (!User::where('remember_token', $req->bearerToken())->first()->is_admin) {
            // Check if the user is the shopkeeper of the shop
            $user = User::where('remember_token', $req->bearerToken())->first();
            if (!$user->is_shop) {
                // return response()->json(['message' => 'User is not authorized'], 401);
                throw new \Exception('User is not authorized');
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
     * Paginate some products
     */
    public function paginate() {
        return response()->json(Product::orderBy('created_at', 'desc')->paginate(10));
    }

    /**
     * Paginate by category
     */
    public function paginateByCategory(string $category) {
        return response()->json(Product::where('category', $category)->orderBy('created_at', 'desc')->paginate(10));
    }

    /**
     * Index products by owner
     */
    public function indexByOwner(string $owner_id) {
        return response()->json(Product::where('owner_id', $owner_id)->get());
    }

    /**
     * Create a new product
     */
    public function store(Request $req) {
        try {
            $data = $this->validateProductData($req);

            $this->assertAuthorized($req);

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
        try {
            $data = $this->validateProductData($req);

            $this->assertAuthorized($req);

            Product::findOrFail($id)->update($data);

            return response()->json(Product::findOrFail($id));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy(Request $req, string $id) {
        try {
            $this->assertAuthorized($req);
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
    public function addPhoto(Request $req, string $id) {
        try {
            $this->assertAuthorized($req);

            $req->validate(['file' => 'required|max:10240|mimes:jpeg,png,jpg,svg']);

            // Check if the product exists
            Product::findOrFail($id);

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
    public function deletePhoto(Request $req, string $id,) {
        try {
            $this->assertAuthorized($req);

            // Check if the product exists
            Product::findOrFail($id);
            $photos = ProductPhoto::where('product_id', $id)->get();
            $files = File::whereIn('id', $photos->pluck('photo_id'))->get();

            foreach ($files as &$file) {
                $this->deleteFile($file->filename);
                $file->delete();
            }
            unset($file);

            return response()->json(['message' => 'Photo deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle wishlist of a product by a user
     */
    public function toggleWishlist(Request $req, string $user_id, string $id) {
        try {
            if (Wishlist::where('user_id', $user_id)->where('product_id', $id)->exists()) {
                Wishlist::where('user_id', $user_id)->where('product_id', $id)->delete();
                return response()->json(['message' => 'Wishlist removed']);
            } else {
                Wishlist::create([
                    'id' => Str::uuid(),
                    'product_id' => $id,
                    'user_id' => $user_id,
                ]);
                return response()->json(['message' => 'Wishlist added']);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get wishlists of a user
     */
    public function getWishlist(Request $req, string $user_id) {
        try {
            return response()->json(Wishlist::where('user_id', $user_id)->get());
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if a product is wishlist of a user
     */
    public function isWishlist(Request $req, string $user_id, string $id) {
        try {
            return response()->json(Wishlist::where('user_id', $user_id)->where('product_id', $id)->exists());
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search products
     */
    public function search(string $q) {
        try {
            return response()->json(Product::where('name', 'like', '%' . $q . '%')->get());
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
