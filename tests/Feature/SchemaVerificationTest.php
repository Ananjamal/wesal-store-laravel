<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Models\Address;
use App\Models\AuditLog;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\FlashSale;
use App\Models\GiftCard;
use App\Models\GiftCardUsage;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Referral;
use App\Models\Review;
use App\Models\ShippingMethod;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_instantiate_and_create_all_models_and_verify_relations(): void
    {
        // 1. User
        $user = User::factory()->create([
            'email' => 'customer@wisal.com',
            'phone' => '1234567890',
        ]);
        $this->assertDatabaseHas('users', ['email' => 'customer@wisal.com']);

        // 2. Category
        $category = Category::factory()->create([
            'name' => 'Scented Candles',
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'Scented Candles']);

        // 3. Product
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Classic Jasmine Candle',
            'price_cents' => 4500,
        ]);
        $this->assertDatabaseHas('products', ['name' => 'Classic Jasmine Candle']);
        $this->assertEquals(45.0, $product->price);
        $this->assertEquals($category->name, $product->category->name);

        // 4. ProductImage
        $productImage = ProductImage::factory()->create([
            'product_id' => $product->id,
            'sort_order' => 1,
        ]);
        $this->assertDatabaseHas('product_images', ['product_id' => $product->id]);
        $this->assertEquals($product->id, $productImage->product->id);

        // 5. Address
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);
        $this->assertDatabaseHas('addresses', ['user_id' => $user->id, 'is_default' => true]);
        $this->assertEquals($user->id, $address->user->id);

        // 6. Cart
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->assertEquals($user->id, $cart->user->id);

        // 7. CartItem
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('cart_items', ['cart_id' => $cart->id, 'product_id' => $product->id]);
        $this->assertEquals($product->id, $cartItem->product->id);

        // 8. ShippingMethod
        $shipping = ShippingMethod::factory()->create([
            'cost_cents' => 3500,
        ]);
        $this->assertDatabaseHas('shipping_methods', ['cost_cents' => 3500]);

        // 9. Coupon
        $coupon = Coupon::factory()->create([
            'code' => 'TESTPERCENT',
            'type' => 'percentage',
            'value' => 15,
        ]);
        $this->assertDatabaseHas('coupons', ['code' => 'TESTPERCENT']);

        // 10. Order
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Pending,
            'coupon_id' => $coupon->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ]);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'order_number' => $order->order_number]);
        $this->assertEquals($user->id, $order->user->id);
        $this->assertEquals($coupon->id, $order->coupon->id);

        // 11. OrderItem
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price_cents' => 4500,
        ]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_id' => $product->id]);
        $this->assertEquals($product->id, $orderItem->product->id);

        // 12. Review
        $review = Review::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
        ]);
        $this->assertDatabaseHas('reviews', ['user_id' => $user->id, 'product_id' => $product->id]);
        $this->assertEquals($product->id, $review->product->id);

        // 13. Wishlist (Pivot)
        $user->wishlists()->attach($product->id);
        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);
        $this->assertEquals(1, $user->wishlists()->count());

        // 14. Post
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Celebrating Heritage Design',
        ]);
        $this->assertDatabaseHas('posts', ['title' => 'Celebrating Heritage Design']);

        // 15. LoyaltyPoint
        $loyaltyPoint = LoyaltyPoint::factory()->create([
            'user_id' => $user->id,
            'points' => 50,
            'order_id' => $order->id,
        ]);
        $this->assertDatabaseHas('loyalty_points', ['user_id' => $user->id, 'points' => 50]);

        // 16. Referral
        $referee = User::factory()->create();
        $referral = Referral::factory()->create([
            'referrer_id' => $user->id,
            'referred_id' => $referee->id,
        ]);
        $this->assertDatabaseHas('referrals', ['referrer_id' => $user->id, 'referred_id' => $referee->id]);

        // 17. GiftCard
        $giftCard = GiftCard::factory()->create([
            'initial_amount_cents' => 10000,
            'remaining_amount_cents' => 10000,
            'created_by' => $user->id,
        ]);
        $this->assertDatabaseHas('gift_cards', ['initial_amount_cents' => 10000]);

        // 18. GiftCardUsage
        $giftCardUsage = GiftCardUsage::factory()->create([
            'gift_card_id' => $giftCard->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount_cents' => 3000,
        ]);
        $this->assertDatabaseHas('gift_card_usages', ['gift_card_id' => $giftCard->id, 'amount_cents' => 3000]);

        // 19. FlashSale & 20. FlashSaleProduct (Pivot Model/Table)
        $flashSale = FlashSale::factory()->create();
        $flashSale->products()->attach($product->id, [
            'sale_price_cents' => 3000,
            'quantity_limit' => 10,
            'sold_quantity' => 2,
        ]);
        $this->assertDatabaseHas('flash_sale_product', ['flash_sale_id' => $flashSale->id, 'product_id' => $product->id]);

        // 21. AuditLog
        $auditLog = AuditLog::factory()->create([
            'user_id' => $user->id,
            'action' => 'login',
        ]);
        $this->assertDatabaseHas('audit_logs', ['user_id' => $user->id, 'action' => 'login']);

        // 22. StoreSetting
        $setting = StoreSetting::factory()->create([
            'key' => 'custom_setting_key',
            'value' => 'custom_value',
        ]);
        $this->assertDatabaseHas('store_settings', ['key' => 'custom_setting_key']);
        $this->assertEquals('custom_value', StoreSetting::getValue('custom_setting_key'));

        // 23. PaymentTransaction
        $tx = PaymentTransaction::factory()->create([
            'order_id' => $order->id,
            'amount_cents' => 4500,
        ]);
        $this->assertDatabaseHas('payment_transactions', ['order_id' => $order->id, 'amount_cents' => 4500]);
    }
}
