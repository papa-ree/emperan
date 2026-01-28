<?php

namespace Bale\Emperan;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Bale\Emperan\Commands\InstallEmperanCommand;

class EmperanServiceProvider extends ServiceProvider
{
    /**
     * Method register()
     * 
     * Digunakan untuk mendaftarkan service, binding, atau command
     * ke dalam service container Laravel.
     */
    public function register(): void
    {
        // Load helper functions
        require_once __DIR__ . '/helpers.php';

        $this->mergeConfigFrom(__DIR__ . '/../config/emperan.php', 'emperan');
        $this->mergeConfigFrom(__DIR__ . '/../config/landing-page.php', 'landing-page');
        $this->registerCommands();
        $this->offerPublishing();
    }

    protected function registerCommands(): void
    {
        $commands = [
            'command.emperan:install' => InstallEmperanCommand::class,
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
        $this->app->booted(function () {
            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'emperan');
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        $this->registerBladeComponents();
    }

    protected function registerBladeComponents(): void
    {
        $componentPath = __DIR__ . '/../resources/views/components';

        if (File::isDirectory($componentPath)) {
            foreach (File::allFiles($componentPath) as $file) {
                if ($file->getExtension() === 'blade') {
                    $componentName = str_replace('.blade.php', '', $file->getFilename());
                    // Register as <x-emperan::component-name />
                    Blade::component('emperan::' . $componentName, 'emperan::' . $componentName);
                }
            }
        }
    }

    /**
     * Publish file agar bisa diubah oleh user.
     */
    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/emperan.php' => config_path('emperan.php'),
        ], 'emperan:config');

        $this->publishes([
            __DIR__ . '/../config/landing-page.php' => config_path('landing-page.php'),
        ], 'emperan:landing-page');

        $this->publishes($this->getMigrations(), 'emperan:migrations');

    }

    /**
     * Mengambil semua file migration dari direktori package.
     */
    protected function getMigrations(): array
    {
        $migrations = [];
        $sourcePath = __DIR__ . '/../database/migrations/';

        // Pastikan direktori ada
        if (!is_dir($sourcePath)) {
            return $migrations;
        }

        // Loop semua file migration (baik .php maupun .stub)
        foreach (glob($sourcePath . '*.{php,stub}', GLOB_BRACE) as $file) {
            $filename = basename($file);

            // Jika file stub, ganti menjadi nama migration yang benar di aplikasi
            $targetFile = $this->getMigrationFileName($filename);

            $migrations[$file] = $targetFile;
        }

        return $migrations;
    }

    /**
     * Membuat nama file migration yang sesuai dengan timestamp laravel.
     */
    protected function getMigrationFileName(string $filename): string
    {
        $timestamp = date('Y_m_d_His');
        $migrationName = str_replace(['.php', '.stub'], '', $filename) . '.php';

        return database_path('migrations/' . $timestamp . '_' . $migrationName);
    }
}
