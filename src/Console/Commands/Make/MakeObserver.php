<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\ObserverMakeCommand;

// @codeCoverageIgnore
class MakeObserver extends ObserverMakeCommand
{
	use TraitModularize;
}
