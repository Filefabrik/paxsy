<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Console\Commands\Composer\RepositoryCommand;
use Filefabrik\Paxsy\Console\Commands\Composer\VendorPackageCommand;
use Filefabrik\Paxsy\Paxsy;
use Filefabrik\Paxsy\Tests\Support\CommandReflectorTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

beforeEach(function () {
    currentStackName();
    useShellDisabled();
});
it(
    'Shell execution disabled',
    function () {
        $this->artisan(
            'paxsy:repository',
            [VendorPackageCommand::VendorPackageIdent => 'testing-vendor/the-package'],
        )
             ->assertExitCode(0)
        ;
    },
);
it(
    'Shell execution disabled --remove',
    function () {
        $this->artisan(
            'paxsy:repository',
            [VendorPackageCommand::VendorPackageIdent => 'testing-vendor/the-package', '--remove' => true],
        )
             ->assertExitCode(0)
        ;
    },
);
it(
    'signature options arguments',
    function () {
        $dummy = new class () extends RepositoryCommand {
            use CommandReflectorTrait;
        };

        $expectArgs = [
            'vendor/package',
            InputArgument::REQUIRED,
            'The "vendor/package" under /'.Paxsy::currentStackName().'/{package}',
        ];

        expect($dummy->getSignature())
            ->toContain('paxsy:repository')
            ->and($dummy->reflectArguments())
            ->toContain($expectArgs)
            ->and($dummy->reflectOptions())
            ->toContain(
                [
                    'remove',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'Remove the Repository for the package',
                ],
                [
                    'flags',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'Additional flags',
                ],
            )
        ;
    },
);
it(
    'Assert has Flags Remove',
    function () {
        $this->artisan('paxsy:repository', ['--help' => true])
             ->expectsOutputToContain('--remove')
             ->expectsOutputToContain('--flag')
             ->assertExitCode(0)
        ;
    },
);
