<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CDNPurgeCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdn:purge-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge Cache from Pullzone';

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
        $response = Http::withHeaders([
            'AccessKey' => config('846.bunnycdn_key'),
        ])->post('https://bunnycdn.com/api/pullzone/' . config('846.bunnycdn_pullzone_id') . '/purgeCache');

        echo $response->body();
        echo $response->status();

    }
}
