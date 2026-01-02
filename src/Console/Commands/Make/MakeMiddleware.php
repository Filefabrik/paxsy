<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Routing\Console\MiddlewareMakeCommand;

// @codeCoverageIgnore
class MakeMiddleware extends MiddlewareMakeCommand
{
    use TraitPackagizer;
}
