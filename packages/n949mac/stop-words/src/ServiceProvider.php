<?php

namespace N949mac\StopWords;

use Laravel\Nova\Nova;
use N949mac\StopWords\Nova\StopWord;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/stop-words.php';
    const MIGRATION_PATH = __DIR__ . '/../database/migrations/2020_08_21_155940_create_stop_words_table.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('stop-words.php'),
        ], 'config');

        $this->loadMigrationsFrom(self::MIGRATION_PATH);


        Nova::resources([
            StopWord::class
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'stop-words'
        );

        $this->app->bind('stop-words', function () {
            return new StopWords();
        });
    }
}
