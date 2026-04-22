<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasSlug;

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'type',
        'start_datetime', 'end_datetime', 'timezone',
        'location_type', 'venue_name', 'venue_address', 'virtual_link',
        'cover_image', 'max_attendees', 'is_members_only', 'is_featured',
        'registration_opens_at', 'registration_closes_at',
        'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'registration_opens_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'is_members_only' => 'boolean',
            'is_featured' => 'boolean',
            'max_attendees' => 'integer',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function programs()
    {
        return $this->hasMany(EventProgram::class)->orderBy('sort_order')->orderBy('start_time');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees()
    {
        return $this->hasMany(EventRegistration::class)->whereIn('status', ['registered', 'attended']);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now())->orderBy('start_datetime');
    }

    public function scopePast($query)
    {
        return $query->where('start_datetime', '<', now())->orderByDesc('start_datetime');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Helpers
    public function isRegistrationOpen(): bool
    {
        if ($this->registration_opens_at && now()->lt($this->registration_opens_at)) {
            return false;
        }
        if ($this->registration_closes_at && now()->gt($this->registration_closes_at)) {
            return false;
        }
        return $this->status === 'published';
    }

    public function isFull(): bool
    {
        if (!$this->max_attendees) return false;
        return $this->attendees()->count() >= $this->max_attendees;
    }

    public function spotsLeft(): ?int
    {
        if (!$this->max_attendees) return null;
        return max(0, $this->max_attendees - $this->attendees()->count());
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            'indaba' => 'bg-accent-DEFAULT text-white',
            'webinar' => 'bg-primary-DEFAULT text-white',
            'conference' => 'bg-purple-600 text-white',
            'workshop' => 'bg-green-600 text-white',
            default => 'bg-gray-500 text-white',
        };
    }
}
