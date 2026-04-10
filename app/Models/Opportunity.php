<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title', 'organization_id', 'posted_by', 'type',
        'description', 'requirements', 'location', 'salary_range',
        'application_url', 'deadline_at', 'status', 'is_members_only',
    ];

    protected function casts(): array
    {
        return [
            'deadline_at' => 'datetime',
            'is_members_only' => 'boolean',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('deadline_at')->orWhere('deadline_at', '>=', now());
            });
    }

    public function scopePublic($query)
    {
        return $query->where('is_members_only', false);
    }

    public function isExpired(): bool
    {
        return $this->deadline_at && $this->deadline_at->isPast();
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            'job' => 'bg-blue-100 text-blue-800',
            'fellowship' => 'bg-purple-100 text-purple-800',
            'scholarship' => 'bg-yellow-100 text-yellow-800',
            'internship' => 'bg-green-100 text-green-800',
            'volunteer' => 'bg-pink-100 text-pink-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
