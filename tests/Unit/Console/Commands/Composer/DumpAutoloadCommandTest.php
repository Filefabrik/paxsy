<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Console\Commands\Composer\DumpAutoloadCommand;

beforeEach(function () {
    useShellDisabled();
});
it(
    'signature',
    function () {
        $dummy = new class () extends DumpAutoloadCommand {
            public function getSignature()
            {
                return $this->signature;
            }
        };

        expect($dummy->getSignature())->toBe('paxsy:dump-autoload');
    },
);
it(
    'handle dummy command',
    function () {
        $this->artisan('paxsy:dump-autoload')
             ->assertExitCode(0)
        ;
    },
);
