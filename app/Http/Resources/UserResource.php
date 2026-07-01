<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'email'                  => $this->email,
            'phone'                  => $this->phone,
            'loyalty_points_balance' => $this->loyalty_points_balance,
            'referral_code'          => $this->referral_code,
            'is_active'              => $this->is_active,
            'roles'                  => $this->getRoleNames(),
            'email_verified_at'      => $this->email_verified_at,
            'created_at'             => $this->created_at,
        ];
    }
}
