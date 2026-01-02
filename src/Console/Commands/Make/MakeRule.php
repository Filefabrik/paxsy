<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Illuminate\Foundation\Console\RuleMakeCommand;

class MakeRule extends RuleMakeCommand
{
    use TraitPackagizer;
    use TraitCallDelegation;
    use TraitOptions;
}
