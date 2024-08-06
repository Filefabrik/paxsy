<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Composer\Console\Input\InputArgument;
use Composer\Console\Input\InputOption;

trait TraitArgumentOption
{
	protected function configureArguments(): array
	{
		return array_map(fn(array $line) => new InputArgument(...$line), $this->getArguments());
	}

	protected function configureOptions(): array
	{
		return array_map(fn(array $line) => new InputOption(...$line), $this->getOptions());
	}
}
