<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgram extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'event_id', 'title', 'description',
        'speaker', 'speaker_title',
        'start_time', 'end_time', 'sort_order',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Formatted time range, e.g. "09:00 – 10:30"
     */
    public function getTimeRangeAttribute(): string
    {
        if (!$this->start_time) return '';
        $range = substr($this->start_time, 0, 5);
        if ($this->end_time) {
            $range .= ' – ' . substr($this->end_time, 0, 5);
        }
        return $range;
    }
}
