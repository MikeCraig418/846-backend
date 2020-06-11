<?php

namespace N949mac\LinkSubmissionReview\Tests;

use N949mac\LinkSubmissionReview\Facades\LinkSubmissionReview;
use N949mac\LinkSubmissionReview\ServiceProvider;
use Orchestra\Testbench\TestCase;

class LinkSubmissionReviewTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'link-submission-review' => LinkSubmissionReview::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
