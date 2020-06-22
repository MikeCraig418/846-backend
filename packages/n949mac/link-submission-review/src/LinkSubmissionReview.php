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
            'link_submissions' => ['model' => LinkSubmission::where('link_status', '=', 'First Seen')->select('*'), 'source' => 'Duplicate Link Submission'], // (3)
        ];

        $this->urls = [
            $linkSubmission->submission_media_url,
            $linkSubmission->submission_url,
        ];

        return $this;
    }

    public function setUrls($urls = [])
    {
        foreach ($urls as $key => $url) {
            $urls[$key] = trim(strtolower(self::addhttp($url)));
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

        $this->linkStatus = 'First Seen';

        $linkSubmission = $this->linkSubmission;

        $urls = $this->urls;

        $patterns = [

            /*
            'identifier' => [
                'regex' => '', // The regex used to identify the link source and extract the ID
                'regex_group' => 1, // The matching group of the ID
                'regexp' => '' // The regex used to search for duplicates in the database,
            ],
            */

            'youtube' => [
                'regex' => '/(?:youtube\.com\/(?:[^#\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^\#"&?\/\s]{11})/',
                'regex_group' => 1,
                'regexp' => '(youtube\.com\/([^#\/]+\/[^:]+\/|(v|e(mbed)?)\/|[^:]*[?&]v=)|youtu\.be\/)(%ID%)',
            ],
            'twitter' => [
                'regex' => '/https?:\/\/twitter\.com\/(?:#!\/)?(?:\w+)\/status(es)?\/(\d+)/',
                'regex_group' => 2,
                'regexp' => 'https?:\/\/twitter\.com\/(#!\/)?([a-zA-Z0-9_\-]+)\/status(es)?\/(%ID%))',
            ],
            'video.twimg' => [
                'regex' => '/video\.twimg\.com\/[^\/]+\/([0-9]+)/',
                'regex_group' => 1,
                'regexp' => 'video\.twimg\.com\/[^\/]+\/(%ID%)',
            ],
        ];

        foreach ($urls as $url) {
//            echo "\n|" . $url . "|\n";

            $useRegex = false;
            $id = null;
            $regexp = null;

            foreach ($patterns as $patternKey => $pattern) {
                preg_match_all($pattern['regex'], $url, $matches, PREG_SET_ORDER, 0);
                if ($matches) {
                    $id = $matches[0][$pattern['regex_group']];
                    $regexp = str_replace('%ID%', $id, $pattern['regexp']);
                    $useRegex = true;
                    break;
                }
            }


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

                $count = 0;
                $whereRaw = "";
                foreach ($checkFields as $urlField) {
                    $count++;

                    if ($count == 1) {
                        if (!$id) {
                            $whereRaw .= $urlField . ' like "' . $url . '"';
                        } else
                            $whereRaw .= $urlField . ' regexp "' . $regexp . '"';
                    } else {

                        if (!$id) {
                            $whereRaw .= ' OR ' . $urlField . ' like "' . $url . '"';
                        } else
                            $whereRaw .= ' OR ' . $urlField . ' regexp "' . $regexp . '"';
                    }
                }

                $model = $model->whereRaw("( $whereRaw )");

                $modelCount = $model->count();

//                echo $key . ": " . $id . " count: " . $modelCount . " " . $regexp . "\n";

                $model = $model->first();

                if ($modelCount) {
                    $this->linkStatus = 'Duplicate';
                    $this->linkStatusRef = $checkModel['source'] == 'model' ? $model->source : $checkModel['source'];

                    return $this;
                } else {
//                    echo $modelCount;
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

    public static function addhttp($url)
    {
        if ($ret = parse_url($url)) {
            if (!isset($ret["scheme"])) {
                $url = "https://" . trim($url);
            }
        }

        return $url;
    }
}
