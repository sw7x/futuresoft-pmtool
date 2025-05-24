<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use Illuminate\Support\Facades\Schema;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;



class MigrateInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:in-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the migrations in the order specified in the file app/Console/Comands/MigrateInOrder.php \n Drop all the table in db before execute the command.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // Reset previous migrations if needed
        //$this->call('migrate:reset', ['--force' => true]);
        //$this->info('✅ All tables dropped. Now migrating in order...');

        
        /** Specify the names of the migrations files in the order you want to loaded
        *   $migrations =[ 
        *       'xxxx_xx_xx_000000_create_nameTable_table.php',
        *   ];
        */
        $migrations = [
            //'database/migrations/2014_10_12_000000_create_users_table.php',                   
            //'database/migrations/2014_10_12_100000_create_password_resets_table.php',         
            //'database/migrations/2019_08_19_000000_create_failed_jobs_table.php',             
            //'database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php',  
            //'database/migrations/2025_05_24_043557_create_store_table.php',
            
            'modules/Reporting/database/migrations/reports_table.php',
            
            'modules/Reporting/database/migrations/2025_05_23_072558_create_reports_table.php',
            
            'database/migrations/2025_05_24_043538_create_product_table.php',
        ];

        $migrationPaths = collect($migrations)
            ->map(function ($file) {
                if (!File::exists($file)) {
                    $this->error("❌ Migration file not found: $file");
                    $this->line('');
                    return null;
                }

                return $file;
            })
            ->filter(fn($path) => File::exists($path))
            ->toArray();

        /*$output =  $this->call('migrate', [
            '--path' => $migrationPaths,
            '--force' => true
        ]);*/

        $output = Artisan::call('migrate', [
            '--path' => $migrationPaths,
            '--force' => true,
        ]);

        $result = Artisan::output();
        //dump('===['.$result.']===');    

        // Count how many times 'Migrated:' appears
        $migratedCount = substr_count($result, 'Migrated:');
        //dump($migratedCount);

        if (str_contains($result, 'Nothing to migrate')) {
            
            $this->line('<info>'.$result.'</info>');        
        } else {            
            
            $formattedResult = str_replace(
                ['Migrating:', 'Migrated:'],
                ['<comment>Migrating:</comment>', '<info>Migrated:</info>'],
                $result
            );

            $this->line($formattedResult);

            if($migratedCount > 1){
                $this->info('✅ Migrations executed as one batch.');
            }
            
        }
        
        return 0;
    }
}
