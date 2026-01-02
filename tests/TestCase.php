<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        /** @noinspection PhpFullyQualifiedNameUsageInspection */
        return [
            \Filefabrik\Paxsy\Providers\PaxsyServiceProvider::class,
            \Filefabrik\Paxsy\Providers\PaxsyCommandsServiceProvider::class,
            \Livewire\LivewireServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        // todo rename to packages
        return [
        ];
    }
}
