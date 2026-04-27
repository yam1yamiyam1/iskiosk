<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pass extends Model
{
    protected $fillable = [
        'tracking_code',
        'staff_id',
        'document_ids'
    ];

    protected $casts = [
        'document_ids' => 'array'
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}