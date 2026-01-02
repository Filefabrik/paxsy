<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Console\Commands\Composer\UpdateCommand;

it(
    'signature',
    function() {
        $dummy = new class () extends UpdateCommand {
            public function getSignature()
            {
                return $this->signature;
            }
        };

        expect($dummy->getSignature())->toBe('paxsy:composer-update');
    },
);
// todo feature test
it(
    'Flags expectation',
    function() {
        $this->artisan('paxsy:composer-update', ['--help' => true])
             ->expectsOutputToContain('--flags')
             ->assertExitCode(0)
        ;
    },
);

it(
    'Execute with disabled',
    function() {
        useShellDisabled();

        $this->artisan('paxsy:composer-update', [])
             ->assertExitCode(0)
        ;
    },
);
