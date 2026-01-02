<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Components;

use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Application;

/**
 * split interface into booting, make commands and resolve commands
 */
interface ComponentInterface
{
    public static function make_commands(): array;

    public static function resolveCommands(Application $application, Artisan $artisan);
}
