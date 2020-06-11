<?php

namespace N949mac\LinkSubmissionReview;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/link-submission-review.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('link-submission-review.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'link-submission-review'
        );

        $this->app->bind('link-submission-review', function () {
            return new LinkSubmissionReview();
        });
    }
}
