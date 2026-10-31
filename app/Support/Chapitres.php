<?php

namespace App\Support;

class Chapitres
{
    /**
     * Liste officielle des chapitres de formation.
     *
     * @return array<int, string>
     */
    public static function tous(): array
    {
        return [
            1 => 'Histoire du Samuel et prière du servant',
            2 => 'Comportements, qualités et attributs des Samuels',
            3 => 'L\'Assemblée liturgique',
            4 => 'Étude des objets sacrés',
            5 => 'Rôle du polyvalent dans la messe',
            6 => 'Porte-croix',
            7 => 'Rôle du porte-croix dans les différents types de messes',
            8 => 'Importance du Samuel dans l\'Église et surtout la nature',
            9 => 'Pratiques',
        ];
    }

    public static function titre(int $numero): ?string
    {
        return self::tous()[$numero] ?? null;
    }

    /**
     * Contenu HTML du chapitre, ou null s'il n'a pas encore été rédigé.
     */
    public static function contenu(int $numero): ?string
    {
        $chemin = resource_path("chapitres/{$numero}.html");

        return is_file($chemin) ? file_get_contents($chemin) : null;
    }
}
