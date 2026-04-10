<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name', 'slug', 'description', 'price_usd',
        'billing_cycle', 'features', 'max_users', 'is_active', 'display_order',
    ];

    protected function casts(): array
    {
        return [
            'price_usd' => 'decimal:2',
            'features' => 'array',
            'max_users' => 'integer',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }
}
