<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberBulletin extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title', 'content', 'sent_at', 'recipient_count', 'created_by', 'status',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'recipient_count' => 'integer',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
