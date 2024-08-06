<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Support\SolvedOptions;
use Symfony\Component\Console\Command\Command;

/**
 * @internal
 */
trait TraitCallDelegation
{
	private bool $sequenceStarter = false;

	/**
	 * Call another console command with package.
	 *
	 * @param Command|string $command
	 * @param array          $arguments
	 *
	 * @return int
	 */
	public function call($command, array $arguments = []): int
	{
		SolvedOptions::hasSolvedOptions() || ($this->sequenceStarter = true);
		// internal fixing packages
		$package = $this->package();
		if ($package) {
			$arguments['--package'] = $package->getName();
			// internal flag that options in the called command can be hide from options-select
			SolvedOptions::addSolvedComponent($this->getName(), $command);
		}

		$res = $this->runCommand($command, $arguments, $this->output);

		if ($this->sequenceStarter) {
			SolvedOptions::reset();
		}
		return $res;
	}
}
