<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class MigrateInOrder extends Command
{
    protected $signature =  'migrate:in-order 
                                {--fresh : Drop all tables and re-run migrations}
                                {--seed : Run seeders after migration}
                                {--force : Force run in production}
                                {--module= : Run specific module only}
                                {--rollback : Rollback ALL migrations (uses migrate:reset per path)}
                                {--step= : Rollback a specific number of steps (see notes on scope below)}';

    protected $description = 'Run app and all module migrations in order';

    // Files inside database/migrations to run at the very end (after every module)
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
    // Migrate
    // -------------------------------------------------------
    protected function handleMigrate(): int
    {
        // Production safety gate — applies to the normal (non --fresh) path too.
        // --fresh already checks this itself before it ever calls handleMigrate(),
        // so this is a no-op in that case, but protects the plain `migrate:in-order` path.
        if (app()->isProduction() && !$this->option('force')) {
            if (!$this->confirm('You are running in PRODUCTION. Continue with migrations?')) {
                $this->error('Aborted. Use --force to skip this confirmation.');
                return self::FAILURE;
            }
        }

        // 1. App/root migrations (excluding the FK-constraints file)
        $this->runMigration('App', 'database/migrations', $this->coreLastMigrations);

        // 2. Module migrations
        $singleModule = $this->option('module');

        if ($singleModule) {
            $this->runMigration($singleModule, "modules/{$singleModule}/database/migrations");
        } else {
            $this->runAllModuleMigrationsInOrder();
        }

        // 3. FK constraints last — only meaningful once ALL modules have migrated.
        // Skip when running a single module in isolation, since other modules'
        // tables referenced by the FK migration may not exist yet.
        if (!$singleModule) {
            $this->runLastMigrations();
        } else {
            $this->warn('  ⚠ Skipped foreign key constraints migration (single --module run).');
            $this->warn('    Run `migrate:in-order` without --module once all modules are migrated.');
        }

        // 4. Seed if requested
        if ($this->option('seed')) {
            $this->runSeeder();
        }

        $this->info('');
        $this->info('✅ All migrations completed successfully.');
        return self::SUCCESS;
    }

    // -------------------------------------------------------
    // Rollback
    // -------------------------------------------------------
    protected function handleRollback(): int
    {
        $steps = $this->option('step'); // null if not provided
        $useReset = is_null($steps);    // no --step = rollback ALL

        if ($useReset) {
            $this->warn('⏪ Rolling back ALL migrations (migrate:reset per path)...');
        } else {
            $this->warn("⏪ Rolling back migrations (steps: {$steps})...");
            $this->warn('    Note: --step is applied PER PATH (FK file, then each module file, then App),');
            $this->warn('    not as a single global counter. If you need an exact global step count,');
            $this->warn('    combine --step with --module to target one path precisely.');
        }

        // 1. Foreign key constraints first (was last to migrate)
        foreach (array_reverse($this->coreLastMigrations) as $migrationPath) {
            if (!File::exists(base_path($migrationPath))) {
                continue;
            }

            $this->warn('  ↩ Rolling back: ' . basename($migrationPath));
            $this->rollbackPath($migrationPath, $useReset, $steps);
        }

        // 2. All module migrations, in reverse global (timestamp) order
        $moduleFiles = $this->getAllModuleMigrationFiles()->reverse()->values();

        if ($moduleFiles->isEmpty()) {
            $this->warn('  ⚠ No module migrations found to roll back.');
        } else {
            foreach ($moduleFiles as $file) {
                $this->warn("  ↩ Rolling back: [{$file['module']}] {$file['filename']}");
                $this->rollbackPath($file['relative_path'], $useReset, $steps);
            }
        }

        // 3. App last (was first to migrate)
        $this->warn('  ↩ Rolling back: App');
        $this->rollbackPath('database/migrations', $useReset, $steps);

        $this->info('');
        $this->info('✅ Rollback completed.');
        return self::SUCCESS;
    }

    /**
     * Rollback a specific path using reset (all) or rollback (steps).
     */
    protected function rollbackPath(string $relativePath, bool $useReset, $steps = null): void
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

    /**
     * Run every migration file found in a directory (sorted by filename),
     * excluding any explicitly listed files.
     */
    protected function runMigration(string $label, string $relativePath, array $exclude = []): void
    {
        $fullPath = base_path($relativePath);

        if (!File::isDirectory($fullPath)) {
            $this->warn("  ⚠ Skipped [{$label}]: path not found → {$relativePath}");
            return;
        }

        $excludeBasenames = collect($exclude)
            ->map(fn ($e) => basename($e))
            ->toArray();

        $files = collect(File::files($fullPath))
            ->filter(fn ($file) => !in_array($file->getFilename(), $excludeBasenames))
            ->sortBy(fn ($file) => $file->getFilename()) // enforce timestamp order explicitly
            ->values();

        if ($files->isEmpty()) {
            $this->warn("  ⚠ Skipped [{$label}]: no migration files found");
            return;
        }

        $this->info("  🔄 Migrating: {$label}");

        foreach ($files as $file) {
            $this->line("    → {$file->getFilename()}");
            $this->call('migrate', [
                '--path'  => $relativePath . '/' . $file->getFilename(),
                '--force' => true,
            ]);
        }

        $this->newLine();

    }

    /**
     * Scan every module folder under modules/, collect their migration files,
     * and return them sorted globally by filename (timestamp prefix) across
     * ALL modules combined — not grouped/ordered by module.
     *
     * This removes the need for a manually maintained module order list:
     * as long as migration filenames are timestamped at creation time,
     * global sort naturally preserves correct dependency order between modules.
     */
    protected function getAllModuleMigrationFiles(): Collection
    {
        $modulesPath = base_path('modules');

        if (!File::isDirectory($modulesPath)) {
            return collect();
        }

        $files = collect();

        foreach (File::directories($modulesPath) as $moduleDir) {
            $module = basename($moduleDir);
            $migrationsPath = $moduleDir . '/database/migrations';

            if (!File::isDirectory($migrationsPath)) {
                continue;
            }

            foreach (File::files($migrationsPath) as $file) {
                $files->push([
                    'module'        => $module,
                    'filename'      => $file->getFilename(),
                    'relative_path' => "modules/{$module}/database/migrations/{$file->getFilename()}",
                ]);
            }
        }

        return $files->sortBy('filename')->values();
    }

    /**
     * Run all module migrations across every discovered module,
     * in global timestamp order.
     */
    protected function runAllModuleMigrationsInOrder(): void
    {
        $files = $this->getAllModuleMigrationFiles();

        if ($files->isEmpty()) {
            $this->warn('  ⚠ No module migrations found.');
            return;
        }

        $this->info('  🔄 Migrating all modules (global timestamp order)...');

        foreach ($files as $file) {            
            $this->line("    → <fg=white;bg=blue>[{$file['module']}-module]</> {$file['filename']}");

            $this->call('migrate', [
                '--path'  => $file['relative_path'],
                '--force' => true,
            ]);
            
            $this->newLine();
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
}
