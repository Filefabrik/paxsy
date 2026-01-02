<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Components\Livewire;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Filefabrik\Paxsy\Console\Commands\TraitPackageSupport;

trait TraitLivewirePackagizer
{
    use TraitPackageSupport;
    use TraitOptions;
}
