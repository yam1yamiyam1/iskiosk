<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchDocument extends Model
{
    protected $table = 'batch_documents';

    protected $fillable = [
        'user_id',
        'document_id',
        'added_at',
        'finalized_at',
        'status',
    ];

    protected $casts = [
        'added_at'     => 'datetime',
        'finalized_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
