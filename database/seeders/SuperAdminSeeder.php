<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Cree (ou met a jour) le super administrateur a partir des variables
     * d'environnement. Utile pour initialiser une base vide (ex: Neon sur Render).
     *
     * Variables attendues :
     *   SAMUEL_ADMIN_EMAIL, SAMUEL_ADMIN_PASSWORD, SAMUEL_ADMIN_NAME (optionnel)
     */
    public function run(): void
    {
        $email = config('samuel.admin_email');
        $password = config('samuel.admin_password');

        if (!$email || !$password) {
            return;
        }

        $name = config('samuel.admin_name', 'Administrateur principal');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );
    }
}
