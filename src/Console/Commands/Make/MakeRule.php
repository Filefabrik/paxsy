<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Illuminate\Foundation\Console\RuleMakeCommand;

class MakeRule extends RuleMakeCommand
{
    use TraitPackagizer, TraitCallDelegation,TraitOptions;
}
