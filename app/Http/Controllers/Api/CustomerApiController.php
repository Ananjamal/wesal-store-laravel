<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerApiController extends Controller
{
    public function orders(Request $request)
    {
        $orders = Auth::user()->orders()
            ->with(['items.product.images'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function orderDetails(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order->load(['items.product.images', 'items.color', 'items.size', 'coupon']);

        // Timeline steps status
        $timeline = [
            ['key' => 'pending', 'label_ar' => 'تم استلام الطلب', 'label_en' => 'Order Placed', 'completed' => true, 'time' => $order->created_at->format('Y-m-d H:i')],
            ['key' => 'processing', 'label_ar' => 'جاري تجهيز الشحنة', 'label_en' => 'Processing', 'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']), 'time' => $order->updated_at->format('Y-m-d H:i')],
            ['key' => 'shipped', 'label_ar' => 'خرجت للشحن مع المندوب', 'label_en' => 'Out for Delivery', 'completed' => in_array($order->status, ['shipped', 'delivered']), 'time' => null],
            ['key' => 'delivered', 'label_ar' => 'تم التوصيل بنجاح', 'label_en' => 'Delivered', 'completed' => $order->status === 'delivered', 'time' => null],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'timeline' => $timeline
            ]
        ]);
    }

    public function wishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('product.images')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlist
        ]);
    }

    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean'
        ]);

        $address = Address::create([
            'user_id' => Auth::id(),
            ...$validated
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة العنوان بنجاح',
            'data' => $address
        ]);
    }

    public function reviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
