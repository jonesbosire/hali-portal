<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id', 'membership_tier_id', 'gateway', 'gateway_reference',
        'gateway_transaction_id', 'amount', 'currency', 'status',
        'payment_method', 'quickbooks_invoice_id', 'paid_at', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount'  => 'decimal:2',
            'paid_at' => 'datetime',
            'meta'    => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_tier_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'successful';
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }
}
