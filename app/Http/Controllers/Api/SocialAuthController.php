<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to the Google OAuth provider.
     */
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'success' => true,
            'data'    => ['redirect_url' => $url],
        ]);
    }

    /**
     * Handle the Google OAuth callback.
     */
    public function callback(): JsonResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed.',
            ], 422);
        }

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'          => $googleUser->getName(),
                'password'      => bcrypt(Str::random(24)),
                'referral_code' => strtoupper(Str::random(8)),
                'is_active'     => true,
                'email_verified_at' => now(),
            ]
        );

        if (!$user->hasAnyRole(['Admin', 'Manager', 'Customer'])) {
            $user->assignRole('Customer');
        }

        $token = $user->createToken('google-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Google authentication successful.',
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
        ]);
    }
}
