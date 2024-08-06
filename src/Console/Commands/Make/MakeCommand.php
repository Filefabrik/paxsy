<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Support\Str\StringModifiers;
use Illuminate\Foundation\Console\ConsoleMakeCommand;

class MakeCommand extends ConsoleMakeCommand
{
	use TraitModularize;

	protected function replaceClass($stub, $name): string
	{
		$stub = parent::replaceClass($stub, $name);

		if ($package = $this->package()) {
			// todo check what the name is
			$cli_name = StringModifiers::modifyMakeCommand($name);

			$find = [
				'{{command}}',
				'{{ command }}',
				'dummy:command',
				'command:name',
				"app:{$cli_name}",
			];

			$stub = (string) str_replace($find, "{$package->getName()}:{$cli_name}", $stub);
		}

		return $stub;
	}
}
