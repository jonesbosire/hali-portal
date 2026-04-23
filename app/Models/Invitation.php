<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'email', 'token', 'role', 'membership_tier_id', 'organization_id', 'invited_by', 'expires_at', 'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public static function generate(string $email, string $role = 'member', ?string $organizationId = null, ?string $invitedBy = null, ?string $membershipTierId = null): self
    {
        return static::create([
            'email'              => $email,
            'token'              => Str::random(64),
            'role'               => $role,
            'membership_tier_id' => $membershipTierId,
            'organization_id'    => $organizationId,
            'invited_by'         => $invitedBy,
            'expires_at'         => now()->addDays(7),
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function isPending(): bool
    {
        return !$this->isExpired() && !$this->isAccepted();
    }

    // Relationships
    public function membershipTier()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_tier_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
