<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);




use Filefabrik\Paxsy\Support\Helper\OverrideCommands;

it(
    'count commands',
    function() {
        expect(count(OverrideCommands::commands()))->toBe(23);
    },
);
it(
    'All Override Commands',
    function($makeCommand, $commandClass) {
        $allCommands = OverrideCommands::commands();
        expect($allCommands[$makeCommand])->toBe($commandClass);
    },
)->with('all override commands');

it(
    'pure make keys',
    function($makeCommand) {
        expect(OverrideCommands::pureMakeCommands())->toContain($makeCommand);
    },
)->with('all override commands');
