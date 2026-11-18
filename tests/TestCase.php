<?php

namespace Pondol\Editor\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pondol\Editor\EditorServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            EditorServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('pondol-editor.middleware', ['web']);
    }
}
