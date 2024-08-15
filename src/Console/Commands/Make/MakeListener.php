<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Support\Str;

// todo bug, will not generate the Event and not showing the "Create Event" prompt
class MakeListener extends ListenerMakeCommand
{
	use TraitPackagizer;
	use TraitOptions;
	use TraitCreatesMatchingTest;
	use TraitCallDelegation;

	protected function buildClass($name): array|string
	{
		$event = $this->option('event');

		if ($event && $this->isInNamespace($name)) {
			$stub = str_replace(
				['DummyEvent', '{{ event }}'],
				class_basename($event),
				GeneratorCommand::buildClass($name),
			);

			return str_replace(
				['DummyFullEvent', '{{ eventNamespace }}'],
				Namespacering::trim($event),
				$stub,
			);
		}

		return parent::buildClass($name);
	}

	/**
	 * Todo Move to package Helper Methods
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	protected function isInNamespace($name): bool
	{
		return $this->package() && Str::startsWith(
			$name,
			$this->package()
														->srcPackageNamespace()
		);
	}
}
