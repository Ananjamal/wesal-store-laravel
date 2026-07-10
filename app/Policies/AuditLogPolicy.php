<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AuditLog;

class AuditLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AuditLog $auditLog): bool
    {
        return $user->hasRole('Admin');
    }
}
