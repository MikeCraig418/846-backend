<?php

namespace App\Console\Commands\LinkSubmission;

use App\Models\Evidence;
use App\Models\LinkSubmission;
use App\Models\ReviewedLink;
use Illuminate\Console\Command;
use N949mac\LinkSubmissionReview\Facades\LinkSubmissionReview;

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

            $review = LinkSubmissionReview::setLinkSubmission($linkSubmission)->review();

            if ($review->isDuplicate()) {
                $linkSubmission->link_status = $review->getLinkStatus();
                $linkSubmission->link_status_ref = $review->getLinkStatusRef();
                $linkSubmission->save();
            }


        }
    }
}
