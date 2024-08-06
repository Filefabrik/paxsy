<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\EventMakeCommand;

// @codeCoverageIgnore
class MakeEvent extends EventMakeCommand
{
	use TraitModularize;
}
