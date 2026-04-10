<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'organization_id', 'plan_id', 'status',
        'current_period_start', 'current_period_end',
        'stripe_subscription_id', 'stripe_customer_id', 'canceled_at',
    ];

    protected function casts(): array
    {
        return [
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trialing']);
    }

    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }
}
