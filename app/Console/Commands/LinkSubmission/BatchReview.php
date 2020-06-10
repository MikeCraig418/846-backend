<?php

namespace App\Console\Commands\LinkSubmission;

use App\Models\Evidence;
use App\Models\LinkSubmission;
use App\Models\ReviewedLink;
use Illuminate\Console\Command;

class BatchReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link-submission:batch-review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $linkSubmissions = LinkSubmission::where('link_status', 'First Seen')->get();

        foreach ($linkSubmissions as $linkSubmission) {

            $urls = [
                $linkSubmission->submission_media_url,
                $linkSubmission->submission_url,
            ];

            foreach ($urls as $url) {

                $this->info($url);

                $urlParts = parse_url($url);

                $IdPattern = false;
                $id = false;

                $hosts = [];


                if ($urlParts['host'] == 'twitter.com') {
                    $IdPattern = '/([0-9]+)/m';

                    $hosts = [
                        'twitter.com'
                    ];
                } else if ($urlParts['host'] == 'youtube.com' || $urlParts['host'] == 'youtu.be') {
                    $IdPattern = '/([0-9a-zA-Z\-_]+)/m';

                    $hosts = [
                        'youtube.com',
                        'youtu.be',
                    ];
                }

                if ($IdPattern) {

                    preg_match_all($IdPattern, $url, $matches, PREG_SET_ORDER, 0);

                    $id = $matches[0][0] ?? false;

                    if (!$id) continue;
                }

                $hosts = array_unique($hosts);


                //
                // Let's start looking for dupes!
                //

                // (1) The the pb2020 approved links

                $checkModels = [
                    'evidence' => ['model' => Evidence::select('*'), 'source' => 'PB2020 Data Feed'],
//                    'reviewed_links' => ['model' => ReviewedLink::select('*'), 'source' => 'model'],
                ];

                foreach ($checkModels as $key => $checkModel) {
                    $model = $checkModel['model'];

                    foreach ($hosts as $host) {
                        if (!$id) {
                            $model = $model->where('url', 'like', '%' . $host . '%' . $id . '$');
                        } else
                            $model = $model->where('url', 'like', '%' . $url . '%');
                    }

                    $modelCount = $model->count();

                    $model = $model->first();

                    if ($modelCount) {
                        $linkSubmission->link_status = 'Duplicate';
                        $linkSubmission->link_status_ref = $checkModel['source'] == 'model' ? $model->source : $checkModel['source'];
                        $linkSubmission->save();
                        break;
                    } else {
                        echo $modelCount;
                    }
                }

            }


        }
    }
}
