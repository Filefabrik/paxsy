<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\PolicyMakeCommand;

class MakePolicy extends PolicyMakeCommand
{
	use TraitModularize {
		TraitModularize::getPath as getModularPath;
	}

	/**
	 * Todo Policy has in laravel 11 the question for matching Model. That should be also work in Paxsy
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
}
