<?php

namespace N949mac\LinkSubmissionReview;

use App\Models\Evidence;
use App\Models\LinkSubmission;
use App\Models\ReviewedLink;

class LinkSubmissionReview
{

    protected $urls = [];
    protected $linkSubmission;

    protected $checkModels;

    protected $linkStatus;
    protected $linkStatusRef;


    public function setLinkSubmission($linkSubmission)
    {

        $this->checkModels = [
            'evidence' => ['model' => Evidence::select('*'), 'source' => 'PB2020 Data Feed'], // (1) The the pb2020 approved links
            'reviewed_links' => ['model' => ReviewedLink::select('*'), 'source' => 'model'],  // (2) other lists
        ];

        $this->urls = [
            $linkSubmission->submission_media_url,
            $linkSubmission->submission_url,
        ];

        return $this;
    }

    public function setUrls($urls = [])
    {
        foreach ($urls as $key=>$url) {
            $urls[$key] = trim(strtolower($this->addhttp($url)));
        }


        $this->checkModels = [
            'evidence' => ['model' => Evidence::select('*'), 'source' => 'PB2020 Data Feed'], // (1) The the pb2020 approved links
            'reviewed_links' => ['model' => ReviewedLink::select('*'), 'source' => 'model'],  // (2) other lists
            'link_submissions' => ['model' => LinkSubmission::select('*'), 'source' => 'Duplicate Link Submission'], // (3)
        ];

        $this->urls = $urls;

        return $this;
    }

    public function review()
    {

        $linkSubmission = $this->linkSubmission;

        $urls = $this->urls;

        foreach ($urls as $url) {
//            echo "|" . $url . "|\n";

            $urlParts = parse_url($url);

            $IdPattern = false;
            $id = false;

            $hosts = [];


            if ($urlParts['host'] == 'twitter.com') {
                $IdPattern = '/([0-9]+)/';
                $groupNum = 0;
                $hosts = [
                    'twitter.com'
                ];

            } else if ($urlParts['host'] == 'youtube.com' || $urlParts['host'] == 'youtu.be') {
                $IdPattern = '/(\.be|\.com)\/([0-9a-zA-Z\-_]+)/';
                $groupNum = 2;
                $hosts = [
                    'youtube.com',
                    'youtu.be',
                ];
            }

            if ($IdPattern) {

                preg_match_all($IdPattern, $url, $matches, PREG_SET_ORDER, 0);

                $id = $matches[0][$groupNum] ?? false;

                if (!$id) continue;
            }

            $hosts = array_unique($hosts);


            //
            // Let's start looking for dupes!
            //

            $checkModels = $this->checkModels;

            foreach ($checkModels as $key => $checkModel) {

//                echo "======= $key =======\n";
                if ($key == "link_submissions") {

                    $checkFields = [
                        'submission_url',
                        'submission_media_url',
                    ];
                } else {

                    $checkFields = [
                        'url'
                    ];
                }

                $model = $checkModel['model'];

                foreach ($checkFields as $urlField) {

                    $count = 0;

                    foreach ($hosts as $host) {
                        $count++;

                        if ($count == 1) {

                            if (!$id) {
                                $model = $model->where($urlField, 'like', '%' . $host . '%' . $id . '$');
                            } else
//                                echo "where($urlField, 'like', '%' . $host . '%' . $id . '$')\n";
                                $model = $model->where($urlField, 'like', '%' . $url . '%');
                        } else {

                            if (!$id) {
                                $model = $model->orWhere($urlField, 'like', '%' . $host . '%' . $id . '$');
                            } else
//                                echo "orWhere($urlField, 'like', '%' . $host . '%' . $id . '$')\n";
                                $model = $model->orWhere($urlField, 'like', '%' . $url . '%');
                        }
                    }

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

    public function getLinkStatus()
    {
        return $this->linkStatus;
    }

    public function getLinkStatusRef()
    {
        return $this->linkStatusRef;
    }

    public function isDuplicate()
    {
        return $this->getLinkStatus() == 'Duplicate';
    }

    public function addhttp($url) {
        if  ( $ret = parse_url($url) ) {
            if ( !isset($ret["host"]) )
            {
                $url = "https://" . trim($url);
            }
        }
        return $url;
    }
}
