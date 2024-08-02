<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
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
            'method' => 'required|string|in:cod',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'address_criteria' => 'required|string',
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
            $shop_ids = Product::whereIn('id', array_column($req['items'], 'product_id'))->pluck('owner_id');
            $shops = User::whereIn('id', $shop_ids)->get();
            $is_shopkeeper = $shops->contains('owner_id', $user->id);
            if (!$is_shopkeeper) {
                // return response()->json(['message' => 'User is not authorized'], 401);
                throw new \Exception('User is not authorized');
            }
        }
    }

    /**
     * Index by user
     */
    public function indexByUser(string $user_id) {
        try {
            $output = [];

            // Check all transactions where customer_id = $user_id
            $customer_transactions = Transaction::where('customer_id', $user_id)->get()->toArray();

            // Check all transaction_items where the corresponding product's owner_id is $user_id, then get all the transactions from those transaction_items
            $shop_transaction_items = TransactionItem::whereIn('product_id', Product::where('owner_id', $user_id)->pluck('id'))->get();
            $shop_transactions = [];
            foreach ($shop_transaction_items as &$transaction_item) {
                $shop_transactions[] = Transaction::where('id', $transaction_item->transaction_id)->first();
            }

            // Merge the two queries
            $output = array_merge($customer_transactions, $shop_transactions);

            // Remove duplicates
            if ($shop_transactions) {
                $output = array_unique($output);
            }

            // Add product details
            foreach ($output as &$transaction) {
                $items = TransactionItem::where('transaction_id', $transaction['id'])->get();

                foreach ($items as $item) {
                    $item->product = Product::where('id', $item->product_id)->first();
                    $item->product->owner = User::where('id', $item->product->owner_id)->first();
                    $item->product->owner->avatar = File::where('id', $item->product->owner->avatar_id)->first();
                }

                $transaction['items'] = $items;
            }
            unset($transaction);

            // Sort by created_at
            usort($output, function ($a, $b) {
                return $b['created_at'] > $a['created_at'];
            });

            // Return the response
            return response()->json($output);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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

            // I know I could make this shorter, but let's just say I'm shorter on time right now
            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'customer_id' => $data['customer_id'],
                'method' => $data['method'],
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'address_criteria' => $data['address_criteria'],
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

    /**
     * Update a transaction
     */
    public function update(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            $data = $this->validateTransactionData($req);
            $transaction = Transaction::findOrFail($id);
            $transaction->update($data);
            return response()->json($transaction);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm delivery of a transaction item
     */
    public function confirmDelivery(Request $req, string $id) {
        try {
            $this->assertAuthorized($req, $id);
            $transaction_item = TransactionItem::findOrFail($id);
            $transaction_item->update(['status' => 'delivered', 'delivery_date' => Carbon::now()]);
            return response()->json($transaction_item);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

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
