<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Organization extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasSlug;

    protected $fillable = [
        'name', 'slug', 'type', 'country', 'region',
        'logo_path', 'website_url', 'description',
        'founding_year', 'students_supported', 'scholarship_total', 'status',
    ];

    protected function casts(): array
    {
        return [
            'founding_year' => 'integer',
            'students_supported' => 'integer',
            'scholarship_total' => 'decimal:2',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function members()
    {
        return $this->belongsToMany(User::class, 'organization_members', 'organization_id', 'user_id')
            ->withPivot('role', 'is_primary', 'joined_at')
            ->withTimestamps();
    }

    public function primaryContact()
    {
        return $this->belongsToMany(User::class, 'organization_members', 'organization_id', 'user_id')
            ->wherePivot('is_primary', true)
            ->first();
    }

    public function directoryListing()
    {
        return $this->hasOne(DirectoryListing::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMembers($query)
    {
        return $query->where('type', 'member');
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1A7A8A&color=fff&size=128&bold=true';
    }
}
