<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentPhoto;
use App\Models\File;
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

    /**
     * Get photos of a comment
     */
    public function getPhotos(string $id) {
        $photo_ids = CommentPhoto::where('comment_id', $id)->pluck('photo_id');
        $files = File::whereIn('id', $photo_ids)->get();
        return response()->json($files);
    }

    /**
     * Add photo to a comment
     */
    public function addPhoto(Request $req, string $id) {
        try {
            $req->validate(['file' => 'required|max:10240|mimes:jpeg,png,jpg,svg']);

            $comment = Comment::findOrFail($id);
            if (User::where('remember_token', $req->bearerToken())->first()->id !== $comment->user_id) {
                throw new \Exception('Invalid token');
            }

            $filename = $this->uploadFile($req);

            $file = File::create([
                'id' => Str::uuid(),
                'filename' => $filename,
            ]);

            $photo = CommentPhoto::create([
                'id' => Str::uuid(),
                'comment_id' => $id,
                'photo_id' => $file->id,
            ]);

            return response()->json($photo);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a photo from a comment
     */
    public function deletePhoto(Request $req, string $comment_id, string $photo_id) {
        try {
            $comment = Comment::findOrFail($comment_id);
            if (User::where('remember_token', $req->bearerToken())->first()->id !== $comment->user_id) {
                throw new \Exception('Invalid token');
            }

            $file = File::findOrFail($photo_id);
            $this->deleteFile($file->filename);
            $file->delete();

            return response()->json(['message' => 'Photo deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
