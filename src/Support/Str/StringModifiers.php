<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Str;

use Illuminate\Support\Str;

/**
 * All string modifiers they are used inside paxsy listed here. For better Unit-Testing and have all together.
 */
class StringModifiers
{
    /**
     * @param string $name Namespace to the Command from MyTestVendor\TheTestPackage\Console\Commands\TestCommand to test-command
     *
     * @return string
     */
    public static function modifyMakeCommand(string $name): string
    {
        return (string) Str::of($name)
                          ->classBasename()
                          ->kebab()
        ;
    }
}
