<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Validate review data
     */
    private function validateReviewData(Request $req) {
        return $this->validateRequest($req, [
            'product_id' => 'required|uuid|exists:products,id',
            'user_id' => 'required|uuid|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
            'photo' => 'nullable|mimes:jpeg,png,jpg,svg',
        ]);
    }

    /**
     * Index all reviews within a product
    */

    /**
     * Create a new review
     */

    /**
     * Update a review
    */

    /**
     * Delete a review
     */
}
