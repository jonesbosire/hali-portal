<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'organization_id', 'subscription_id', 'amount_usd',
        'status', 'stripe_invoice_id', 'paid_at', 'due_date', 'pdf_path', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_usd' => 'decimal:2',
            'paid_at' => 'datetime',
            'due_date' => 'datetime',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
