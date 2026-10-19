<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Super administrateur initial
    |--------------------------------------------------------------------------
    |
    | Definies par variables d'environnement, ces valeurs servent a creer (ou
    | mettre a jour) le super administrateur lors du seeding initial.
    |
    */

    'admin_name' => env('SAMUEL_ADMIN_NAME', 'Administrateur principal'),
    'admin_email' => env('SAMUEL_ADMIN_EMAIL'),
    'admin_password' => env('SAMUEL_ADMIN_PASSWORD'),

];
