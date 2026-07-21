<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Sync the cart from frontend to backend database for logged-in users.
     */
    public function sync(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('sanctum')->user() ?? Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $cartItems = $request->input('cartItems', []);

        $cart = $user->carts()->firstOrCreate(['session_id' => session()->getId()]);
        
        // Clear existing items and replace with current frontend cart state
        // This ensures absolute parity between Pinia and Database
        $cart->items()->delete();

        $itemsToInsert = [];
        foreach ($cartItems as $item) {
            if (empty($item['id']) || empty($item['quantity'])) continue;

            $itemsToInsert[] = [
                'cart_id' => $cart->id,
                'product_id' => $item['id'],
                'color_id' => $item['colorId'] ?? null,
                'size_id' => $item['sizeId'] ?? null,
                'quantity' => $item['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($itemsToInsert)) {
            \App\Models\CartItem::insert($itemsToInsert);
        }

        $cart->update(['last_activity_at' => now()]);

        return response()->json(['message' => 'Cart synced successfully']);
    }
}
