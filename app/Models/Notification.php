<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'titre',
        'message',
        'source',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getEstLueAttribute(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Notifie tous les administrateurs principaux (et un admin donné).
     */
    public static function notifierAdmins(string $titre, string $message, string $type = 'info', ?string $source = null, array $data = []): void
    {
        $admins = User::whereIn('role', ['super_admin', 'admin'])->get();

        foreach ($admins as $admin) {
            static::create([
                'user_id' => $admin->id,
                'type' => $type,
                'titre' => $titre,
                'message' => $message,
                'source' => $source,
                'data' => $data,
            ]);
        }
    }
}
