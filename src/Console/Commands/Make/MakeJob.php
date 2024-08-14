<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Illuminate\Foundation\Console\JobMakeCommand;

// @codeCoverageIgnore
class MakeJob extends JobMakeCommand
{
	use TraitModularize;
	use TraitOptions;
	use TraitCreatesMatchingTest;
	use TraitCallDelegation;
}
