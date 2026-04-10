<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryListing extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'organization_id', 'user_id', 'listing_title', 'bio',
        'specializations', 'countries_served', 'languages',
        'linkedin_url', 'twitter_url', 'is_public', 'is_featured', 'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'countries_served' => 'array',
            'languages' => 'array',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'last_updated_at' => 'datetime',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function primaryContact()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
