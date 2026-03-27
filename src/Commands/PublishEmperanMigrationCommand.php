<?php

namespace Bale\Emperan\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\select;

class PublishEmperanMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emperan:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish migrations from Bale Emperan package (All or specific file)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrationPath = realpath(__DIR__ . '/../../database/migrations/');
        
        if (!File::isDirectory($migrationPath)) {
            $this->error("Migration directory not found at: {$migrationPath}");
            return;
        }

        $files = glob($migrationPath . '/*.{php,stub}', GLOB_BRACE);

        if (empty($files)) {
            $this->error('No migrations found in the package.');
            return;
        }

        // Prepare options for select
        $options = ['All'];
        $fileMap = [];
        
        foreach ($files as $file) {
            $basename = basename($file);
            $options[] = $basename;
            $fileMap[$basename] = $file;
        }

        // Use Laravel Prompts select (radio-like behavior)
        $choice = select(
            label: 'Which migration(s) would you like to publish?',
            options: $options,
            default: 'All'
        );

        if ($choice === 'All') {
            $this->info('Publishing all migrations...');
            foreach ($files as $index => $file) {
                $this->publishFile($file, $index);
            }
            $this->info('✅ All migrations published successfully.');
        } else {
            if (isset($fileMap[$choice])) {
                $this->publishFile($fileMap[$choice]);
                $this->info("✅ Migration [{$choice}] published successfully.");
            } else {
                $this->error("Migration [{$choice}] not found.");
            }
        }
    }

    /**
     * Copy the migration file to the application database/migrations directory.
     *
     * @param string $source
     * @param int $offset
     * @return void
     */
    protected function publishFile($source, $offset = 0)
    {
        $filename = basename($source);
        
        // Clean name from stub or double extension
        $cleanName = str_replace(['.php', '.stub'], '', $filename);
        
        // Remove existing timestamp if any (to avoid double timestamp)
        $cleanName = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{2}\d{2}\d{2}_/', '', $cleanName);
        
        // Generate new timestamp
        $timestamp = date('Y_m_d_His', time() + $offset);
        $targetName = "{$timestamp}_{$cleanName}.php";
        $targetPath = database_path('migrations/' . $targetName);

        // Ensure directory exists
        if (!File::isDirectory(database_path('migrations'))) {
            File::makeDirectory(database_path('migrations'), 0755, true);
        }

        File::copy($source, $targetPath);
        $this->line("<info>  \xe2\x9e\x9c Published:</info> {$targetName}");
    }
}
