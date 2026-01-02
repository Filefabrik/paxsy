<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Support\Stubs\Variables;

it(
    'test empty vars',
    function() {
        $vars = new Variables();
        expect($vars->renderVariables())->toBe([]);
    },
);
