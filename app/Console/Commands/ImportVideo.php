<?php

namespace App\Console\Commands;

use App\Models\Evidence;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pb:import-video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Videos into Evidence';

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
        $count = 0;
        foreach (Evidence::whereNull('video')->get() as $evidence) {

            $url = $evidence->url;
            $response = Http::withHeaders([
                'x-rapidapi-host' => 'getvideo.p.rapidapi.com',
                'x-rapidapi-key' => config('846.rapidapi_key')
            ])->get('https://getvideo.p.rapidapi.com/', [
                'url' => $url,
            ]);

            if ($response = $response->json()) {

                print_r($response);

                if (isset($response['status']) && $response['status'] == 1) {

                    $video = new Video();
                    $video->title = $response['title'];
                    $video->description = $response['description'] ?? null;
                    $video->site = $response['site'];
                    $video->uploader = $response['uploader'] ?? null;
                    $video->url = $response['url'] ?? null;
                    $video->duration = $response['duration'] ?? 0;
                    $video->tags = $response['tags'] ?? [];
                    $video->thumbnail = $response['thumbnail'] ?? null;
                    $video->streams = $response['streams'] ?? [];
                    $video->meta = $response;
                    $video->evidence_id = $evidence->id;
                    $evidence->video = 'ok';
                    $video->save();

                } else {
                    $evidence->video = $response['message'];
                }

                $evidence->save();

            }

            if ($count++ > 40) exit;
        }

    }
}
