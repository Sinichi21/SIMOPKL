<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThreadChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat',
        'user_id',
        'thread_id',
        'by'
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medias(): HasMany
    {
        return $this->hasMany(ThreadChatMedia::class);
    }
}
