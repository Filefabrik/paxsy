<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

/**
 * Shows only the commands they must execute
 */
class WithDisabled extends WithShellExec implements WithInterface
{
	/**
	 * @return static
	 */
	public function execute(): static
	{
		$expressions = $this->getCommandExpressions();

		if ($expressions) {
			$this->executeExpressions($expressions);

			// perhaps for later
			$this->transactions[] = $this->getResults();
		}

		return $this;
	}

	/**
	 * @param string      $command
	 * @param string|null $prefix
	 *
	 * @return void
	 */
	protected function executeCommand(string $command, ?string $prefix = null): void
	{
		$this->addResult(['command' => $command, 'result' => null, 'prefix' => $prefix]);
	}
}
