<?php

namespace HgaCreative\MailgunWebhooks\tests;

use Orchestra\Testbench\TestCase;
use HgaCreative\MailgunWebhooks\Facades\MailgunWebhooks;
use HgaCreative\MailgunWebhooks\MailgunWebhooksServiceProvider;

class MailgunWebhooksTestCase extends TestCase
{

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [MailgunWebhooksServiceProvider::class];
    }

    /**
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return ['MailgunWebhooks' => MailgunWebhooks::class];
    }
}
