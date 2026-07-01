<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Only the order owner, Admin, or Manager can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id
            || $user->hasAnyRole(['Admin', 'Manager']);
    }

    /**
     * Only Admin or Manager can update order status.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->hasAnyRole(['Admin', 'Manager']);
    }

    /**
     * Only Admin can delete an order.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole('Admin');
    }
}
