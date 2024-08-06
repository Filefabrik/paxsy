<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

use Illuminate\Console\Command;

class WithShellExec extends AbstractWith implements WithInterface
{
	public function __construct()
	{
		$this->startTransaction = ['cd '.$this->getLaravelHostComposer()
												->directory(),
			'_startTransaction', ];
		$this->endTransaction = ['cd '.$this->getLaravelHostComposer()
												->getOriginalWorkingDir(),
			'_endTransaction'];
	}

	/**
	 * @return static
	 */
	public function execute(): static
	{
		$expressions = $this->getCommandExpressions();

		if ($expressions) {
			$this->startTransaction();
			$this->executeExpressions($expressions);
			$this->endTransaction();
			// perhaps for later
			$this->transactions[] = $this->getResults();
		}

		return $this;
	}

	/**
	 * @param array $expressions
	 *
	 * @return void
	 */
	protected function executeExpressions(array $expressions): void
	{
		foreach ($expressions as $expression) {
			$this->executeCommand($expression);
		}
	}

	public function lastTransactionToConsole(Command $command): void
	{
		foreach ($this->lastTransaction() as $result) {
			$output = $result['command'];

			$command->line($output);
		}
	}

	/**
	 * @param string      $command
	 * @param string|null $prefix
	 *
	 * @return void
	 */
	protected function executeCommand(string $command, ?string $prefix = null): void
	{
		$this->addResult(['command' => $command, 'result' => shell_exec($command), 'prefix' => $prefix]);
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return $this
	 */
	public function addVendorPackage(...$params): static
	{
		$this->add($this->addVendorPackageCommand(...$params));

		return $this;
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return string
	 */
	public function addVendorPackageCommand(...$params): string
	{
		[$vendor_package_name, $flags] = $this->extractFlags($params, 1);

		return "composer require $vendor_package_name".$this->renderFlags($flags);
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return $this
	 */
	public function removeVendorPackage(...$params): static
	{
		$this->add($this->removeVendorPackageCommand(...$params));

		return $this;
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return string
	 */
	public function removeVendorPackageCommand(...$params): string
	{
		[$vendor_package_name, $flags] = $this->extractFlags($params, 1);

		return "composer remove $vendor_package_name".$this->renderFlags($flags);
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return $this
	 */
	public function addRepository(...$params): static
	{
		$this->add($this->addRepositoryCommand(...$params));

		return $this;
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return string
	 */
	public function addRepositoryCommand(...$params): string
	{
		[$key, $content, $flags] = $this->extractFlags($params, 2);

		return "composer config repositories.paxsy-$key '{$content}'".$this->renderFlags($flags);
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return $this
	 */
	public function removeRepository(...$params): static
	{
		$this->add($this->removeRepositoryCommand(...$params));

		return $this;
	}

	protected function removeRepositoryCommand(...$params): string
	{
		[$key, $flags] = $this->extractFlags($params, 1);

		return "composer config repositories.paxsy-$key --unset".$this->renderFlags($flags);
	}

	private function extractFlags($params, $flagsIndex)
	{
		$params[$flagsIndex] ??= null;

		return $params;
	}
}
