<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Samuel extends Model
{
    protected $fillable = [
        'nom',
        'prenoms',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function getNomCompletAttribute(): string
    {
        return trim($this->nom . ' ' . ($this->prenoms ?? ''));
    }
}
