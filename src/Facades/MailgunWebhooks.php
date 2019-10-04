<?php

declare(strict_types=1);

namespace HgaCreative\MailgunWebhooks\Facades;

use Illuminate\Support\Facades\Facade;

class MailgunWebhooks extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return \HgaCreative\MailgunWebhooks\Contracts\MailgunWebhooks::class;
    }
}
