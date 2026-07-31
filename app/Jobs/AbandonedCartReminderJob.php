<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Notifications\AbandonedCartNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AbandonedCartReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Find carts inactive for more than 24 hours with items and logged in user
        $inactiveCarts = Cart::whereNotNull('user_id')
            ->where('last_activity_at', '<=', now()->subHours(24))
            ->whereHas('items')
            ->get();

        foreach ($inactiveCarts as $cart) {
            if ($cart->user) {
                Log::info("Sending abandoned cart reminder to user: {$cart->user->email}");
                // Send reminder notification
            }
        }
    }
}
