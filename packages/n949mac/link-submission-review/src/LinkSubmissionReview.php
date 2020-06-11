<?php

namespace N949mac\LinkSubmissionReview;

use App\Models\Evidence;
use App\Models\ReviewedLink;

class LinkSubmissionReview
{

    protected $urls = [];
    protected $linkSubmission;

    protected $linkStatus;
    protected $linkStatusRef;


    public function setLinkSubmission($linkSubmission)
    {

        $this->urls = [
            $linkSubmission->submission_media_url,
            $linkSubmission->submission_url,
        ];




        return $this;
    }

    public function review()
    {

        $linkSubmission = $this->linkSubmission;

        $urls = $this->urls;

        foreach ($urls as $url) {

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

            $checkModels = [
                'evidence' => ['model' => Evidence::select('*'), 'source' => 'PB2020 Data Feed'], // (1) The the pb2020 approved links
                'reviewed_links' => ['model' => ReviewedLink::select('*'), 'source' => 'model'],  // (2) other lists
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
                    $this->linkStatus = 'Duplicate';
                    $this->linkStatusRef = $checkModel['source'] == 'model' ? $model->source : $checkModel['source'];
                    return $this;
                } else {
//                        echo $modelCount;
                }
            }

        }
        return $this;
    }

    public function getLinkStatus() {
        return $this->linkStatus;
    }
    public function getLinkStatusRef() {
        return $this->linkStatusRef;
    }

    public function isDuplicate() {
        return $this->getLinkStatus() == 'Duplicate';
    }
}
