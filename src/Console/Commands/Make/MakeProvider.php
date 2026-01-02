<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\ProviderMakeCommand;

// @codeCoverageIgnore
class MakeProvider extends ProviderMakeCommand
{
    use TraitPackagizer;
}
