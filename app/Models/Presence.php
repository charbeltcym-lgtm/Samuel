<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    protected $fillable = [
        'user_id',
        'date_repetition',
        'statut',
        'remarque',
        'synchronise',
    ];

    protected $casts = [
        'date_repetition' => 'date',
        'synchronise' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
