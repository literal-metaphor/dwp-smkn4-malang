<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RatingController extends Controller
{
    /**
     * Validate full rating data
     */
    private function validateRatingData(Request $req) {
        return $this->validateRequest($req, [
            'product_id' => 'required|uuid|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    }

    /**
     * Index all ratings
     */
    public function index() {
        return response()->json(Rating::orderBy('created_at', 'desc')->get());
    }

    /**
     * Index all ratings within a product
     */
    public function indexByProduct(string $product_id) {
        return response()->json(Rating::where('product_id', $product_id)->orderBy('created_at', 'desc')->get());
    }

    /**
     * Show a rating by ID
     */
    public function show(string $id) {
        return response()->json(Rating::findOrFail($id));
    }

    /**
     * Create a new rating or update an existing one
     */
    public function store(Request $req) {
        $data = $this->validateRatingData($req);

        try {
            $data['user_id'] = User::where('remember_token', $req->bearerToken())->first()->id;
            $rating = Rating::where('user_id', $data['user_id'])->where('product_id', $data['product_id'])->first();

            if ($rating) {
                $data['id'] = $rating->id;
                $rating->update($data);
            } else {
                $data['id'] = Str::uuid();
                Rating::create($data);
            }

            return response()->json(Rating::where('user_id', $data['user_id'])->where('product_id', $data['product_id'])->first());
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a rating
     */
    public function destroy(Request $req, string $id) {
        try {
            $rating = Rating::findOrFail($id);

            if ($rating->user_id !== User::where('remember_token', $req->bearerToken())->first()->id) {
                throw new \Exception('Invalid token');
            }

            $rating->delete();

            return response()->json(['message' => 'Rating deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
