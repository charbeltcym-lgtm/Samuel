<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Samuel extends Model
{
    protected $fillable = [
        'nom',
        'prenoms',
        'actif',
        'ordre',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    /**
     * Ordre d'affichage : les Samuels ayant un ordre prioritaire (1, 2…)
     * d'abord, puis les autres par ordre alphabétique.
     */
    public function scopeOrdonne(Builder $query): Builder
    {
        return $query->orderBy('ordre')->orderBy('nom')->orderBy('prenoms');
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function getNomCompletAttribute(): string
    {
        return trim($this->nom . ' ' . ($this->prenoms ?? ''));
    }
}
