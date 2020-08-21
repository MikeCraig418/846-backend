<?php

namespace N949mac\StopWords\Tests;

use N949mac\StopWords\Facades\StopWords;
use N949mac\StopWords\ServiceProvider;
use Orchestra\Testbench\TestCase;

class StopWordsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'stop-words' => StopWords::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
