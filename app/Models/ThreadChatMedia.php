<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreadChatMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'filename'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(ThreadChat::class);
    }
}
