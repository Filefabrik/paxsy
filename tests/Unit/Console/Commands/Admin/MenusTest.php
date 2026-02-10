<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

use Filefabrik\Paxsy\Console\Commands\Admin\Menus;

it(
    'Main Menu contains all Items',
    function($label, $method) {
        $foundFlag = false;
        foreach (Menus::$mainMenu as $item) {
            if ($label === $item['label']) {
                expect($method === $item['method'])->toBeTrue();
                $foundFlag = true;
                break;
            }
        }
        expect($foundFlag)->toBeTrue();
    },
)->with('main menu');

it(
    'Plug correctly',
    function($label, $method) {
        $res = Menus::selectableMenu(Menus::$mainMenu);

        expect($res[$method])->toBe($label);
    },
)->with('main menu');
