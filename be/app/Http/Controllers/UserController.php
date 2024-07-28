<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Validate full user data
     */
    private function validateUserData(Request $req) {
        return $this->validateRequest($req, [
            'username' => 'required|alpha_dash|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255|nullable',
            // 'cell_phone_number' => 'required|string|max:15',
            // 'country_code' => 'required|string|max:2',
        ]);
    }

    /**
     * Assert that user is an admin
     */
    // ? Didn't we have this in middleware already?
    private function assertAdmin(Request $req) {
        // return auth()->user()->is_admin ? true : response()->json(['message' => 'User is not an admin'], 401);
        if (!User::where('remember_token', $req->bearerToken())->first()->is_admin) {
            throw new \Exception('User is not an admin');
        }
    }

    /**
     * Verify the user's token and ban status
     */
    private function assertAuthorized(Request $req, string $id) {
        $user = User::findOrFail($id);
        if ($user->banned) {
            throw new \Exception('User is banned');
        }
        if ($user->remember_token !== $req->bearerToken()) {
            throw new \Exception('Invalid token');
        }
    }

    /**
     * Handle OAuth for a user
     */
    public function oauth(Request $req) {
        $data = $this->validateRequest($req, [
            "email" => "required|email|max:255",
            "password" => "required|string|max:255"
        ]);

        // Check if the email is already registered
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            // If the user exists, log them in
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json(['message' => 'Invalid password'], 401);
            }
            if ($user->banned) {
                return response()->json(['message' => 'User is banned'], 401);
            }
            $user->remember_token = bin2hex(random_bytes(16));
            $user->save();
            return response()->json($user);
        } else {
            $data = $this->validateUserData($req);

            // If the user doesn't exist, register them
            $data['password'] = Hash::make($data['password']);
            $data['id'] = Str::uuid();
            $data['remember_token'] = bin2hex(random_bytes(16));

            try {
                $user = User::create($data);
                return response()->json($user);
            } catch (\Throwable $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }
    }


    /**
     * Register a new user and create a new token for them
     */
    public function register(Request $req) {
        $data = $this->validateUserData($req);

        $data['password'] = Hash::make($data['password']);
        $data['id'] = Str::uuid();

        // Sign up the user and handle any errors
        try {
            $user = User::create($data);

            // Create a new token for the user
            $user->remember_token = bin2hex(random_bytes(16));
            $user->save();

            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Public method for verify
     */
    public function verify(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            return response()->json(User::findOrFail($id));
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Log in the user by creating a new token for them
     */
    public function login(Request $req) {
        $data = $this->validateRequest($req, [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        // Attempt to log in the user and handle any errors
        try {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                return response()->json(['message' => 'Invalid email'], 401);
            }
            if (!Hash::check($data['password'], $user->password)) {
                return response()->json(['message' => 'Invalid password'], 401);
            }
            if ($user->banned) {
                return response()->json(['message' => 'User is banned'], 401);
            }
            $user->remember_token = bin2hex(random_bytes(16));
            $user->save();
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Log out the user by deleting their token
     */
    public function logout(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            $user = User::findOrFail($id);
            $user->remember_token = null;
            $user->save();
            return response()->json($user);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // *IMPORTANT NOTE* Admin must not be exposed to the public on read methods
    /**
     * Index all users
     */
    public function index() {
        return response()->json(User::orderBy('created_at', 'desc')->get()->filter(function ($user) { return !in_array(true, [$user->is_admin]); }));
    }

    /**
     * Show a user by ID
     */
    public function show(string $id) {
        return response()->json(!User::findOrFail($id)->is_admin ? User::findOrFail($id) : []);
    }

    /**
     * Create a new user
     * @deprecated use register instead
     */
    // public function store(Request $req) {
    //     $data = $this->validateUserData($req);

    //     $data['password'] = Hash::make($data['password']);
    //     $data['id'] = Str::uuid();

    //     // Create the user and handle any errors
    //     try {
    //         return response()->json(User::create($data));
    //     } catch (\Throwable $e) {
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }

    /**
     * Update a user
     */
    public function update(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            $data = $this->validateUserData($req);

            // Update the user and handle any errors
            try {
                $user = User::findOrFail($id)->update($data);
                return response()->json(User::findOrFail($id));
            } catch (\Throwable $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            User::where('id', $id)->findOrFail()->delete();
            return response()->json(['message' => 'User deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle a user's ban status
     */
    public function toggleBan(Request $req, string $id) {
        try {
            $this->assertAdmin($req);
            $user = User::where('id', $id)->findOrFail();
            $user->banned = !$user->banned;
            $user->save();
            return response()->json(['message' => "$user->username is now ".($user->banned ? 'banned' : 'unbanned') . "."]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin(Request $req, string $id) {
        try {
            $this->assertAdmin($req);
            $user = User::where('id', $id)->findOrFail();
            $user->is_admin = !$user->is_admin;
            $user->save();
            return response()->json(['message' => "$user->username is now ".($user->is_admin ? 'promoted to admin' : 'demoted from admin') . "."]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle shop status
     */
    public function toggleShop(Request $req, string $id) {
        try {
            $this->assertAdmin($req);
            $user = User::findOrFail($id);
            $user->is_shop = !$user->is_shop;
            $user->save();
            return response()->json(['message' => "$user->username is now ".($user->is_shop ? 'promoted to shop' : 'demoted from shop') . "."]);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the user's avatar
     */
    public function getAvatar(string $id) {
        $user = User::findOrFail($id);
        $avatar = File::findOrFail($user->avatar_id);
        return response()->json($avatar);
    }

    /**
     * Store the user's avatar or update it if it already exists
     */
    public function storeAvatar(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            if ($req->hasFile('file')) {
                $req->validate(['file' => 'required|max:10240|mimes:jpeg,png,jpg,svg']);

                $user = User::findOrFail($id);

                // Delete the old file if it exists
                if ($user->avatar_id) {
                    $oldFile = File::findOrFail($user->avatar_id);
                    $this->deleteFile($oldFile->filename);
                    $oldFile->delete();
                }

                // Upload the new file
                $filename = $this->uploadFile($req);
                $file = File::create([
                    'id' => Str::uuid(),
                    'filename' => $filename,
                ]);

                // Update the user
                $user->avatar_id = $file->id;
                $user->save();

                return response()->json($file);
            } else {
                throw new \Exception('No file uploaded');
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete the user's avatar
     */
    public function destroyAvatar(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);

            // Delete the file and handle any errors
            try {
                $user = User::findOrFail($id);

                // Delete the old file if it exists
                if ($user->avatar_id) {
                    $oldFile = File::findOrFail($user->avatar_id);
                    $this->deleteFile($oldFile->filename);
                    $oldFile->delete();
                }

                return response()->json(['message' => 'Avatar deleted']);
            } catch (\Throwable $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
