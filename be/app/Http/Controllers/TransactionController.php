<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Validate full transaction data
     */
    private function validateTransactionData(Request $req) {
        $data = $this->validateRequest($req, [
            'customer_id' => 'required|uuid|exists:users,id',
            'items' => 'required|array',
        ]);

        // Enforce that the structure is exactly like the rules
        // Rules: product_id must represent a valid product, quantity must be an integer, two fields must be present
        $error = false;
        foreach ($data['items'] as $item) {
            $missing_fields = array_diff(['product_id', 'quantity'], array_keys($item));
            if (count($missing_fields) > 0) {
                // return response()->json(['message' => "Missing fields: " . implode(', ', $missing_fields)], 400);
                $error = true;
                throw new \Exception("Missing fields: " . implode(', ', $missing_fields));
            }

            if (!Product::findOrFail($item['product_id'])) {
                // return response()->json(['message' => 'product_id does not exist'], 400);
                $error = true;
                throw new \Exception('product_id does not exist');
            }

            if (!filter_var($item['quantity'], FILTER_VALIDATE_INT) || $item['quantity'] < 1) {
                // return response()->json(['message' => 'quantity must be an integer greater than 0'], 400);
                $error = true;
                throw new \Exception('quantity must be an integer greater than 0');
            }
        }

        return $error ? [] : $data;
    }

    /**
     * Assert that user is either shopkeeper, customer, or user
     */
    private function assertAuthorized(Request $req, string $transaction_id) {
        $user = User::where('remember_token', $req->bearerToken())->first();

        if ($user->is_admin) {
            return;
        }

        $transaction = Transaction::findOrFail($transaction_id);

        if ($transaction->customer_id !== $user->id) {
            $shop_ids = Product::whereIn('id', array_column($req['items'], 'product_id'))->pluck('shop_id');
            $shops = Shop::whereIn('id', $shop_ids)->get();
            $is_shopkeeper = $shops->contains('owner_id', $user->id);
            if (!$is_shopkeeper) {
                // return response()->json(['message' => 'User is not authorized'], 401);
                throw new \Exception('User is not authorized');
            }
        }
    }

    /**
     * Index all transactions
     * !for admin only, users shouldn't have access to see transaction details (make sure to enforce this middleware in api.php)
     */
    public function index() {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        foreach ($transactions as &$transaction) {
            $transaction_items = TransactionItem::where('transaction_id', $transaction->id)->get();
            $transaction->items = $transaction_items;
        }
        unset($transaction);
        return response()->json($transactions);
    }

    /**
     * Show a transaction by ID
     */
    public function show(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            $transaction = Transaction::findOrFail($id);
            $transaction->items = TransactionItem::where('transaction_id', $id)->get();
            return response()->json($transaction);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new transaction
     */
    public function store(Request $req) {
        try {
            $data = $this->validateTransactionData($req);

            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'customer_id' => $data['customer_id'],
            ]);

            $transaction_items = [];
            foreach ($data['items'] as $item) {
                $transaction_items[] = TransactionItem::create([
                    'id' => Str::uuid(),
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => Product::findOrFail($item['product_id'])->price * $item['quantity'],
                ]);
            }

            $transaction->items = $transaction_items;

            return response()->json($transaction);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // I'm not sure there would be an instance where admin would need to update a transaction detail, so I'm not implementing update yet.

    /**
     * Delete a transaction by ID
     */
    public function destroy(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            Transaction::findOrFail($id)->delete();
            return response()->json(['message' => 'Transaction deleted']);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
