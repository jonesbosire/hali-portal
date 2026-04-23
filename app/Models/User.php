<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\CausesActivity;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasUuids, SoftDeletes, CausesActivity;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status',
        'avatar', 'title', 'bio', 'linkedin_url', 'phone',
        'membership_tier_id', 'dues_due_date', 'last_login_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'two_factor_secret', 'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'dues_due_date'     => 'date',
            'password'          => 'hashed',
        ];
    }

    // Relationships
    public function membershipTier()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_tier_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_members', 'user_id', 'organization_id')
            ->withPivot('role', 'is_primary', 'joined_at')
            ->withTimestamps();
    }

    public function primaryOrganization()
    {
        return $this->organizations()->wherePivot('is_primary', true)->first();
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function opportunities()
    {
        return $this->hasMany(Opportunity::class, 'posted_by');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    // Helpers
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'secretariat']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function isFriend(): bool
    {
        return $this->role === 'friend';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function duesOverdue(): bool
    {
        return $this->dues_due_date !== null && now()->isAfter($this->dues_due_date->addDays(7));
    }

    public function duesInGracePeriod(): bool
    {
        if ($this->dues_due_date === null) {
            return false;
        }
        return now()->isAfter($this->dues_due_date) && ! $this->duesOverdue();
    }

    public function duesSoon(int $days = 14): bool
    {
        return $this->dues_due_date !== null
            && now()->lessThanOrEqualTo($this->dues_due_date)
            && now()->diffInDays($this->dues_due_date) <= $days;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // Serve through FileServeController (requires auth, outside web root)
            return route('files.serve', ['path' => $this->avatar]);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1A7A8A&color=fff&size=128';
    }
}
