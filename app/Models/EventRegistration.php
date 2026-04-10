<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_id', 'user_id', 'organization_id', 'status',
        'registration_notes', 'dietary_requirements',
        'registered_at', 'canceled_at', 'attended_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'canceled_at' => 'datetime',
            'attended_at' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
