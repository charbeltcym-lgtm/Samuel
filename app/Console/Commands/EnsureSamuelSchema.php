<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class EnsureSamuelSchema extends Command
{
    protected $signature = 'samuel:ensure-schema';

    protected $description = 'Verifie le schema de la base et le reconstruit si necessaire (Neon/Render).';

    public function handle(): int
    {
        $schemaOk = false;

        try {
            $schemaOk = Schema::hasTable('users') && Schema::hasColumn('users', 'role');
        } catch (\Throwable $e) {
            $this->warn('Verification du schema impossible : ' . $e->getMessage());
        }

        if ($schemaOk) {
            $this->info('Schema OK : application des migrations manquantes.');
            Artisan::call('migrate', ['--force' => true]);
        } else {
            $this->warn('Schema incomplet detecte : reconstruction complete (migrate:fresh --force).');
            Artisan::call('migrate:fresh', ['--force' => true]);
        }

        $this->output->write(Artisan::output());

        if (config('samuel.admin_email') && config('samuel.admin_password')) {
            $this->info('Creation du super administrateur.');
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\SuperAdminSeeder',
                '--force' => true,
            ]);
            $this->output->write(Artisan::output());
        }

        return self::SUCCESS;
    }
}
