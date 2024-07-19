<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    /**
     * Validate full comment data
     */
    private function validateCommentData(Request $req) {
        return $this->validateRequest($req, [
            'product_id' => 'required|uuid|exists:products,id',
            'content' => 'required|string',
        ]);
    }

    /**
     * Index all comments
     */
    public function index() {
        return response()->json(Comment::orderBy('created_at', 'desc')->get());
    }

    /**
     * Index all comments within a product
     */
    public function indexByProduct(string $product_id) {
        return response()->json(Comment::where('product_id', $product_id)->orderBy('created_at', 'desc')->get());
    }

    /**
     * Show a comment by ID
     */
    public function show(string $id) {
        return response()->json(Comment::findOrFail($id));
    }

    /**
     * Create a new comment
     */
    public function store(Request $req) {
        $data = $this->validateCommentData($req);

        try {
            $data['id'] = Str::uuid();
            $data['user_id'] = User::where('remember_token', $req->bearerToken())->first()->id;
            return response()->json(Comment::create($data));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a comment
     */
    public function update(Request $req, string $id) {
        $data = $this->validateCommentData($req);

        try {
            $data['user_id'] = User::where('remember_token', $req->bearerToken())->first()->id;
            $comment = Comment::findOrFail($id);

            if ($comment->user_id !== $data['user_id']) {
                throw new \Exception('Invalid token');
            }

            $comment->update($data);

            return response()->json($comment);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(Request $req, string $id) {
        try {
            $comment = Comment::findOrFail($id);

            if ($comment->user_id !== User::where('remember_token', $req->bearerToken())->first()->id) {
                throw new \Exception('Invalid token');
            }

            $comment->delete();

            return response()->json(['message' => 'Comment deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
