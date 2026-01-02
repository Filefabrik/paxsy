<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Components;

class ComponentHelper
{
    /**
     * Makes a list of commands from the components
     *
     * @param array<int,class-string<ComponentInterface>> $components
     *
     * @return array
     */
    public static function makeCommands(array $components): array
    {
        $commands = [];
        foreach ($components as $component) {
            $commands = [...$commands, ...$component::make_commands()];
        }

        return $commands;
    }
}
