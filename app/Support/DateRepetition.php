<?php

namespace App\Support;

use Carbon\Carbon;

class DateRepetition
{
    /**
     * Résout la date d'une répétition : elle a toujours lieu le samedi.
     * Sans date fournie, on prend le samedi le plus récent (dernière répétition).
     * Une date qui n'est pas un samedi est ramenée au samedi précédent.
     */
    public static function resoudre(?string $valeur = null): Carbon
    {
        try {
            $date = $valeur ? Carbon::parse($valeur) : Carbon::now();
        } catch (\Throwable $e) {
            $date = Carbon::now();
        }

        if ($date->dayOfWeek !== Carbon::SATURDAY) {
            $date = $date->copy()->previous(Carbon::SATURDAY);
        }

        return $date->startOfDay();
    }
}
