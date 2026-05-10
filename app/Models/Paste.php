<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class Paste extends Model
{
    use HasFactory;

    public const LANGUAGES = [
        'text',
        'php',
        'javascript',
        'sql',
        'json',
        'html',
        'css',
        'bash',
        'python',
    ];

    public const VISIBILITIES = [
        'private',
        'unlisted',
        'public',
    ];

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'content',
        'language',
        'password_hash',
        'visibility',
        'expires_at',
        'burn_after_reading',
        'read_at',
        'max_views',
        'views_count',
        'last_viewed_at',
        'delete_token',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'burn_after_reading' => 'boolean',
            'read_at' => 'datetime',
            'max_views' => 'integer',
            'views_count' => 'integer',
            'last_viewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isBurned(): bool
    {
        return $this->burn_after_reading && $this->read_at !== null;
    }

    public function hasReachedViewLimit(): bool
    {
        return $this->max_views !== null && $this->views_count >= $this->max_views;
    }

    public function isPasswordProtected(): bool
    {
        return filled($this->password_hash);
    }

    public function decryptContent(): string
    {
        return Crypt::decryptString($this->content);
    }

    public function getDecryptedContentAttribute(): string
    {
        return $this->decryptContent();
    }

    public function statusLabel(): string
    {
        if ($this->isExpired()) {
            return __('safe_paste.status.expired');
        }

        if ($this->isBurned()) {
            return __('safe_paste.status.burned');
        }

        if ($this->hasReachedViewLimit()) {
            return __('safe_paste.status.view_limit');
        }

        return __('safe_paste.status.active');
    }
}
