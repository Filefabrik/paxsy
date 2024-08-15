<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\PolicyMakeCommand;

class MakePolicy extends PolicyMakeCommand
{
	use TraitCallDelegation, TraitPackagizer {
		TraitPackagizer::getPath as getModularPath;
	}

	/**
	 * Re-Pathing the Original Path from Laravel Output to the 'the-package' path
	 *
	 * @param $name
	 *
	 * @return array|string
	 */
	protected function getPath($name): array|string
	{
		return $this->package() ? $this->getModularPath($name) : parent::getPath($name);
	}

	/**
	 * Build the class with the given name.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function buildClass($name)
	{
		$stub = $this->replaceUserNamespace(
			parent::buildClass($name)
		);

		$model = $this->option('model');
		// if model === model ask

		return $model ? $this->replaceModel($stub, $model) : $stub;
	}
}
