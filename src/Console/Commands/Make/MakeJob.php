<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\JobMakeCommand;

// @codeCoverageIgnore
class MakeJob extends JobMakeCommand
{
	use TraitModularize;
}
