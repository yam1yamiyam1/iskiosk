<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'id_number',
        'surname',
        'given_name',
        'middle_name',
        'year_level',
        'program',
        'document_type',
        'email',
        'contact_number',
        'tracking_code',
        'status',
        'remarks',
        'date_claimed',
        'batch_id',
        'email_sent_at',
        'email_message_id',
        'retrieved_at',
        'marked_by',
    ];

    protected $casts = [
        'date_claimed'   => 'datetime',
        'email_sent_at'  => 'datetime',
        'retrieved_at'   => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(BatchDocument::class, 'batch_id');
    }

    public function programb(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'program');
    }

    public function document_typeb(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type');
    }
}
