<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Any authenticated Customer can create a review.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Customer');
    }

    /**
     * Only the review author or Admin can update.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id
            || $user->hasRole('Admin');
    }

    /**
     * Only the review author or Admin can delete.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id
            || $user->hasRole('Admin');
    }
}
