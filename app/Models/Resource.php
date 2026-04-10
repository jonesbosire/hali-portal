<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'type', 'file_path',
        'external_url', 'thumbnail', 'is_members_only',
        'download_count', 'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'is_members_only' => 'boolean',
            'download_count' => 'integer',
        ];
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return $this->external_url;
    }

    public function incrementDownloads(): void
    {
        $this->increment('download_count');
    }
}
