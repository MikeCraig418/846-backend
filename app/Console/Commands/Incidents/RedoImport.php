<?php

namespace App\Console\Commands\Incidents;

use App\Models\Evidence;
use App\Models\Incident;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RedoImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:redo-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the incidents and evidence tables, then pb:run-import';

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

        $this->info('Truncating tables...');
        Incident::truncate();
        Evidence::truncate();

        $this->info('pb:run-import');
        Artisan::call('pb:run-import');

        $this->info('✅ Done.');
    }
}
