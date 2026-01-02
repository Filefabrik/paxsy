<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);




use Filefabrik\Paxsy\Console\Commands\Info\ListCommand;

beforeEach(function() {
    removePackageStack();
});
it(
    'signature and description',
    function() {
        $dummy = new class () extends ListCommand {
            public function getSignature()
            {
                return $this->signature;
            }
        };

        expect($dummy->getSignature())
            ->toBe('paxsy:list')
            ->and($dummy->getDescription())
            ->toBe('List all Packages with additional information\'s')
        ;
    },
);
it(
    'Paxsy List Test',
    function() {
        rerouteStubsDirectory();
        $this->artisan(ListCommand::class)
             ->expectsOutput('You have 0 packages installed.')
             ->assertExitCode(0)
        ;

        $this->artisan(
            'paxsy:package',
            ['vendor' => 'test vendor 2', 'package' => 'pgk testing 2', 'stubs' => 'default'],
        )
             ->assertExitCode(0)
        ;

        $this->artisan(ListCommand::class)
             ->expectsOutput('You have 1 package installed.')
             ->assertExitCode(0)
        ;
        $this->artisan(
            'paxsy:package',
            ['vendor' => 'test vendor 32', 'package' => 'pgk testing 32', 'stubs' => 'default'],
        )
             ->assertExitCode(0)
        ;

        $this->artisan(ListCommand::class)
             ->expectsOutput('You have 2 packages installed.')
             ->assertExitCode(0)
        ;
    },
);
