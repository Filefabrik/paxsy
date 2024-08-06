<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

/**
 * @phpstan-type Transaction array{"command":string,"result":string}|array{}
 * @phpstan-type Transactions array{int,Transaction}|array{}
 */
abstract class AbstractWith
{
	abstract protected function executeCommand(string $command, ?string $prefix = null);

	/**
	 * @var Composer|null
	 */
	private ?Composer $laravelHostComposer = null;

	protected array $startTransaction = [];

	protected array $endTransaction = [];

	/**
	 * @var array
	 */
	protected array $commandExpressions = [];

	/**
	 * @var Transactions
	 */
	private array $results = [];

	private bool $singleMode = true;

	/**
	 * @var Transactions
	 */
	protected array $transactions = [];

	public function add(string $expression, mixed $flags = null): static
	{
		$this->commandExpressions[] = $expression.$this->renderFlags($flags);
		// call directly
		if ($this->singleMode) {
			$this->execute()
				 ->clear()
			;
		}

		return $this;
	}

	protected function renderFlags(mixed $flags = null): ?string
	{
		if (null === $flags) {
			return null;
		}
		if (is_array($flags)) {
			$flags = implode(' ', $flags);
		}

		return $flags ? ' '.ltrim($flags, ' ') : '';
	}

	protected function startTransaction(): void
	{
		if ($this->startTransaction) {
			static::executeCommand(...$this->startTransaction);
		}
	}

	protected function endTransaction(): void
	{
		if ($this->endTransaction) {
			static::executeCommand(...$this->endTransaction);
		}
	}

	/**
	 * @param Transaction $result
	 *
	 * @return void
	 */
	protected function addResult(array $result): void
	{
		$this->results[] = $result;
	}

	public function batchMode(): static
	{
		$this->singleMode = false;

		return $this;
	}

	public function singleMode(): static
	{
		$this->singleMode = true;

		return $this;
	}

	public function isSingle(): bool
	{
		return $this->singleMode === true;
	}

	/**
	 * @return Transactions
	 */
	public function getTransactions(): array
	{
		return $this->transactions;
	}

	/**
	 * @return Composer
	 */
	protected function getLaravelHostComposer(): Composer
	{
		return $this->laravelHostComposer ??= \Filefabrik\Paxsy\Console\Commands\Admin\Composer::getLaravelHostComposer();
	}

	/**
	 * All batchable
	 *
	 * @return array|null
	 */
	public function getCommandExpressions(): ?array
	{
		return $this->commandExpressions;
	}

	/**
	 * @return array|null
	 */
	public function getResults(): ?array
	{
		return $this->results;
	}

	/**
	 * @return Transactions
	 */
	public function lastTransaction(): array
	{
		$transactions = $this->getTransactions();

		return end($transactions);
	}

	/**
	 * @return $this
	 */
	public function clear(): static
	{
		$this->commandExpressions = [];
		$this->results            = [];

		return $this;
	}

	abstract public function execute(): static;
}
