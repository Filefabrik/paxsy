<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Composer;

use Symfony\Component\Console\Input\InputOption;

trait TraitFlags
{
	protected function configure(): void
	{
		parent::configure();

		// todo rename to package
		$this->getDefinition()
			 ->addOption(
			 	new InputOption(
			 		'--flags',
			 		null,
			 		InputOption::VALUE_OPTIONAL,
			 		'Additional Composer Flags such as -vvv',
			 	),
			 )
		;
	}

	protected function getFlags(): array|string|null
	{
		return $this->option('flags');
	}
}
