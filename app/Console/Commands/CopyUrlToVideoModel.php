<?php

namespace App\Console\Commands;

use App\Models\Evidence;
use App\Models\Incident;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CopyUrlToVideoModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:copy-url-to-video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import incidents from 2020PB Repo';

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

        $videos = Video::all();

        foreach($videos as $video) {
            $video->evidence_url = $video->evidence->url;
            $video->save();
        }


    }


}
