<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Temporary debug
        \Illuminate\Support\Facades\Log::info('LOGIN ATTEMPT', [
            'email' => $credentials['email'],
            'password_length' => strlen($credentials['password']),
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        \Illuminate\Support\Facades\Log::info('USER FOUND', [
            'found' => $user ? true : false,
            'is_active' => $user?->is_active,
            'password_match' => $user ? \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password) : false,
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            \Illuminate\Support\Facades\Log::info('LOGIN SUCCESS', ['user_id' => Auth::id()]);

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->syncGuestData($user, $request->input('cartItems', []), $request->input('wishlistItems', []));

            $intended = redirect()->getIntendedUrl();
            if ($intended && Str::contains($intended, '/admin')) {
                return redirect('/');
            }

            return redirect()->intended('/');
        }

        \Illuminate\Support\Facades\Log::warning('LOGIN FAILED - Auth::attempt returned false');

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'referral_code' => strtoupper(Str::random(8)),
            'is_active' => true,
        ]);

        $user->assignRole('Customer');

        Auth::login($user);
        $this->syncGuestData($user, $request->input('cartItems', []), $request->input('wishlistItems', []));

        $intended = redirect()->getIntendedUrl();
        if ($intended && \Illuminate\Support\Str::contains($intended, '/admin')) {
            return redirect('/');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['items.product.media', 'items.color', 'items.size'])
            ->latest()
            ->get();

        // Append first media URL to each product for the modal
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $media = $item->product->getFirstMedia('product-images')
                          ?? $item->product->getFirstMedia('product-cover');
                    $item->product->image_url = $media ? $media->getUrl() : null;
                }
            }
        }

        return Inertia::render('Profile', [
            'orders' => $orders
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', app()->getLocale() === 'ar' ? 'تم تحديث الملف الشخصي بنجاح!' : 'Profile updated successfully!');
    }

    private function syncGuestData(\App\Models\User $user, array $cartItems, array $wishlistItems)
    {
        // 1. Sync Wishlist (filtering out non-existent product IDs)
        if (!empty($wishlistItems)) {
            $rawProductIds = collect($wishlistItems)->pluck('id')->filter()->toArray();
            if (!empty($rawProductIds)) {
                $validProductIds = \App\Models\Product::whereIn('id', $rawProductIds)->pluck('id')->toArray();
                if (!empty($validProductIds)) {
                    $user->wishlists()->syncWithoutDetaching($validProductIds);
                }
            }
        }

        // 2. Sync Cart (filtering out non-existent product IDs)
        if (!empty($cartItems)) {
            $rawProductIds = collect($cartItems)->pluck('id')->filter()->toArray();
            if (!empty($rawProductIds)) {
                $validProductIds = \App\Models\Product::whereIn('id', $rawProductIds)->pluck('id')->toArray();

                if (!empty($validProductIds)) {
                    $cart = $user->carts()->firstOrCreate(['session_id' => session()->getId()]);
                    
                    foreach ($cartItems as $item) {
                        if (empty($item['id']) || empty($item['quantity'])) continue;
                        if (!in_array($item['id'], $validProductIds)) continue;
                        
                        $existingItem = $cart->items()
                            ->where('product_id', $item['id'])
                            ->where('color_id', $item['colorId'] ?? null)
                            ->where('size_id', $item['sizeId'] ?? null)
                            ->first();

                        if ($existingItem) {
                            $existingItem->increment('quantity', $item['quantity']);
                        } else {
                            $cart->items()->create([
                                'product_id' => $item['id'],
                                'color_id' => $item['colorId'] ?? null,
                                'size_id' => $item['sizeId'] ?? null,
                                'quantity' => $item['quantity'],
                            ]);
                        }
                    }
                    $cart->update(['last_activity_at' => now()]);
                }
            }
        }
    }
}
