<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresenceSession extends Model
{
    protected $fillable = [
        'date_repetition',
        'signed_at',
        'signed_by',
    ];

    protected $casts = [
        'date_repetition' => 'date',
        'signed_at' => 'datetime',
    ];

    public function signataire()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
