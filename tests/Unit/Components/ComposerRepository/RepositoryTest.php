<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Components\ComposerRepository\Repository;

it(
    'repository body',
    function () {
        $parsed = Repository::body('app-paxsy-testing/that-is-a-package-path');

        $jsonString = <<<'JSON'
{
    "type": "path",
    "url": "app-paxsy-testing/that-is-a-package-path",
    "options": {
        "symlink": true
    }
}

JSON;

        expect($parsed)->toBe($jsonString);
    },
);
