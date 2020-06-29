<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:run-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from Data Feed, Grab Geos';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('pb:import-incidents');
        Artisan::call('pb:import-incidents');

        $this->info('pb:update-lat-long');
        Artisan::call('pb:update-lat-long');

        $this->info('✅ Done.');
    }
}
