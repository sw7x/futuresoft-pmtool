<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateInOrder extends Command
{
    protected $signature = 'migrate:in-order 
                            {--fresh : Drop all tables and re-run migrations}
                            {--seed : Run seeders after migration}
                            {--force : Force run in production}
                            {--module= : Run specific module only}
                            {--rollback : Rollback ALL migrations (uses migrate:reset per path)}
                            {--step= : Rollback a specific number of steps instead of all}';

    protected $description = 'Run core and all module migrations in order';

    // Define module execution order (dependency-based)
    protected array $moduleOrder = [
        'reporting',
        'leave',
        // add more modules here in dependency order
    ];

    // Files inside database/migrations to run at the very end
    protected array $coreLastMigrations = [
        'database/migrations/9999_12_30_000001_add_foreign_key_constraints_to_all_tables.php',
    ];

    public function handle(): int
    {
        if ($this->option('rollback')) {
            return $this->handleRollback();
        }

        if ($this->option('fresh')) {
            return $this->handleFresh();
        }

        return $this->handleMigrate();
    }

    // -------------------------------------------------------
    // Migrate
    // -------------------------------------------------------
    protected function handleMigrate(): int
    {
        // 1. Core migrations (excluding last migrations)
        $this->runMigration('Core', 'database/migrations', $this->coreLastMigrations);

        // 2. Specific module or all modules
        if ($module = $this->option('module')) {
            $this->runMigration($module, "modules/{$module}/database/migrations");
        } else {
            foreach ($this->moduleOrder as $module) {
                $this->runMigration($module, "modules/{$module}/database/migrations");
            }
        }

        // 3. Run foreign key constraints migration last
        $this->runLastMigrations();

        // 4. Seed if requested
        if ($this->option('seed')) {
            $this->runSeeder();
        }

        $this->info('');
        $this->info('✅ All migrations completed successfully.');
        return self::SUCCESS;
    }

    // -------------------------------------------------------
    // Fresh
    // -------------------------------------------------------
    protected function handleFresh(): int
    {
        if (!$this->option('force') && app()->isProduction()) {
            $this->error('Use --force flag in production!');
            return self::FAILURE;
        }

        $this->warn('⚠️  Dropping all tables and re-running migrations...');
        $this->call('db:wipe', ['--force' => true]);

        return $this->handleMigrate();
    }

    // -------------------------------------------------------
    // Rollback
    // -------------------------------------------------------
    protected function handleRollback(): int
    {
        $steps = $this->option('step'); // null if not provided
        $useReset = is_null($steps);   // no --step = rollback ALL

        if ($useReset) {
            $this->warn('⏪ Rolling back ALL migrations (migrate:reset per path)...');
        } else {
            $this->warn("⏪ Rolling back migrations (steps: {$steps})...");
        }

        // Rollback order is reverse of migration order:
        // 1. Foreign key constraints first (was last to migrate)
        foreach (array_reverse($this->coreLastMigrations) as $migrationPath) {
            if (!File::exists(base_path($migrationPath))) continue;

            $this->warn('  ↩ Rolling back: ' . basename($migrationPath));
            $this->rollbackPath($migrationPath, $useReset, $steps);
        }

        // 2. Modules in reverse order
        foreach (array_reverse($this->moduleOrder) as $module) {
            $path = "modules/{$module}/database/migrations";
            if (!File::isDirectory(base_path($path))) continue;

            $this->warn("  ↩ Rolling back: {$module}");
            $this->rollbackPath($path, $useReset, $steps);
        }

        // 3. Core last (was first to migrate)
        $this->warn('  ↩ Rolling back: Core');
        $this->rollbackPath('database/migrations', $useReset, $steps);

        $this->info('');
        $this->info('✅ Rollback completed.');
        return self::SUCCESS;
    }

    /**
     * Rollback a specific path using reset (all) or rollback (steps).
     */
    protected function rollbackPath(string $relativePath, bool $useReset, $steps=null): void
    {
        if ($useReset) {
            // migrate:reset rolls back ALL migrations in the path regardless of batches
            $this->callSilently('migrate:reset', [
                '--path'  => $relativePath,
                '--force' => true,
            ]);
        } else {
            $this->callSilently('migrate:rollback', [
                '--path'  => $relativePath,
                '--step'  => (int) $steps,
                '--force' => true,
            ]);
        }
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    protected function runMigration(string $label, string $relativePath, array $exclude = []): void
    {
        $fullPath = base_path($relativePath);

        if (!File::isDirectory($fullPath)) {
            $this->warn("  ⚠ Skipped [{$label}]: path not found → {$relativePath}");
            return;
        }

        $excludeBasenames = collect($exclude)
            ->map(fn($e) => basename($e))
            ->toArray();

        $files = collect(File::files($fullPath))
            ->filter(fn($file) => !in_array($file->getFilename(), $excludeBasenames))
            ->values();

        if ($files->isEmpty()) {
            $this->warn("  ⚠ Skipped [{$label}]: no migration files found");
            return;
        }

        $this->info("  🔄 Migrating: {$label}");

        foreach ($files as $file) {
            $this->call('migrate', [
                '--path'  => $relativePath . '/' . $file->getFilename(),
                '--force' => true,
            ]);
        }
    }

    protected function runLastMigrations(): void
    {
        foreach ($this->coreLastMigrations as $migrationPath) {
            if (!File::exists(base_path($migrationPath))) {
                $this->warn("  ⚠ Skipped [Last]: file not found → {$migrationPath}");
                continue;
            }

            $filename = basename($migrationPath);
            $this->info("  🔗 Running last migration: {$filename}");

            $this->call('migrate', [
                '--path'  => $migrationPath,
                '--force' => true,
            ]);
        }
    }

    protected function runSeeder(): void
    {
        $this->info('');
        $this->info('🌱 Running seeders...');
        $this->call('db:seed', ['--force' => true]);
    }

    // Auto-discover modules not listed in moduleOrder
    protected function discoverModules(): array
    {
        $modulesPath = base_path('modules');

        if (!File::isDirectory($modulesPath)) return [];

        return collect(File::directories($modulesPath))
            ->map(fn($path) => basename($path))
            ->filter(fn($module) => !in_array($module, $this->moduleOrder))
            ->values()
            ->toArray();
    }


}


