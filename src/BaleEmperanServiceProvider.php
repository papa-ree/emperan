<?php

namespace Paparee\BaleEmperan;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Paparee\BaleEmperan\Commands\BaleEmperanCommand;

class BaleEmperanServiceProvider extends ServiceProvider
{
    /**
     * Method register()
     * 
     * Digunakan untuk mendaftarkan service, binding, atau command
     * ke dalam service container Laravel.
     */
    public function register(): void
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        $commands = [
        ];

        foreach ($commands as $key => $class) {
            $this->app->bind($key, $class);
        }

        $this->commands(array_keys($commands));
    }

    /**
     * Method boot()
     * 
     * Dipanggil setelah semua service diregistrasi.
     * Digunakan untuk load resource seperti:
     * - view
     * - migration
     * - konfigurasi
     * - Livewire component
     */
    public function boot(): void
    {
        // $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
