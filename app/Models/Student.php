<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'id_number',
        'surname',
        'given_name',
        'middle_name',
        'year_level',
        'program',
        'email',
        'contact_number',
        'document_type',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'id_number', 'id_number');
    }
}
