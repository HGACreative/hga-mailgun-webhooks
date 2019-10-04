<?php

declare(strict_types=1);

namespace HgaCreative\MailgunWebhooks;

use HgaCreative\MailgunWebhooks\Contracts\MailgunWebhooks;
use Illuminate\Support\ServiceProvider;

class StorageManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        if ($this->app->runningInConsole()) {
            $migrations = __DIR__.'/../database/migrations/';

            $this->publishes([
                $migrations => database_path('migrations'),
            ], 'mailgunWebhooks-migrations');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(MailgunWebhooks::class, function ($app) {
            return new \HgaCreative\MailgunWebhooks\MailgunWebhooks($app);
        });
    }
}
