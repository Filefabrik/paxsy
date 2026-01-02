<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Support\Stubs\FromConfig;
use Filefabrik\Paxsy\Support\Stubs\Helper;

beforeEach(function() {
    removePackageStack();
});
// produces output without replacements
it(
    'Testing the Writer empty Vars',
    function() {
        makePackageByArtisanCommand($this);
        $defaultPackage   = defaultTestPackage();
        $selectedStubsSet = 'default';
        $stubsConfig      = new FromConfig($selectedStubsSet);

        $stubsHelper = Helper::createStubs(
            packageBasePath: $defaultPackage->packageBasePath(),
            stubsMap       : $stubsConfig->stubs(),
            stubsDirectory : $stubsConfig->directory(),
            variables      : [],
        );

        $stubsHelper->writeStubs();
    },
);
