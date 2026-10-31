<?php

namespace App\Services;

use App\Models\Presence;
use App\Models\Samuel;
use Carbon\Carbon;

class PresenceService
{
    /**
     * Enregistre (ou met à jour) les statuts de présence d'un samedi.
     * Retourne le nombre de statuts réellement enregistrés.
     *
     * @param  array<int|string, string|null>  $statuts
     */
    public static function enregistrer(Carbon $date, array $statuts): int
    {
        $dateIso = $date->toDateString();
        $enregistres = 0;

        foreach ($statuts as $samuelId => $statut) {
            if ($statut === null || $statut === '') {
                continue;
            }

            $samuel = Samuel::find($samuelId);
            if (! $samuel) {
                continue;
            }

            // On cherche par jour (whereDate) car la colonne est un datetime :
            // comparer directement '2026-10-31' ne matcherait pas
            // '2026-10-31 00:00:00' et créerait un doublon.
            $presence = Presence::where('samuel_id', $samuel->id)
                ->whereDate('date_repetition', $dateIso)
                ->first();

            if ($presence) {
                $presence->update([
                    'statut' => $statut,
                    'synchronise' => true,
                ]);
            } else {
                Presence::create([
                    'samuel_id' => $samuel->id,
                    'date_repetition' => $dateIso,
                    'statut' => $statut,
                    'synchronise' => true,
                ]);
            }

            $enregistres++;
        }

        return $enregistres;
    }
}
