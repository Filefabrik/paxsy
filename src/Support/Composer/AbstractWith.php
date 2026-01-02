<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Composer;

/**
 * @phpstan-type Transaction array{"command":string,"result":string}|array{}
 * @phpstan-type Transactions array{int,Transaction}|array{}
 */
abstract class AbstractWith
{
    protected array $startTransaction = [];
    protected array $endTransaction = [];
    /**
     * @var array
     */
    protected array $commandExpressions = [];
    /**
     * @var Transactions
     */
    protected array $transactions = [];
    /**
     * @var Composer|null
     */
    private ?Composer $laravelHostComposer = null;
    /**
     * @var Transactions
     */
    private array $results = [];

    private bool $singleMode = true;

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
     * @return Transactions
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    protected function startTransaction(): void
    {
        if ($this->startTransaction) {
            static::executeCommand(...$this->startTransaction);
        }
    }

    abstract protected function executeCommand(string $command, ?string $prefix = null);

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

    /**
     * @return Composer
     */
    protected function getLaravelHostComposer(): Composer
    {
        return $this->laravelHostComposer ??= \Filefabrik\Paxsy\Console\Commands\Admin\Composer::getLaravelHostComposer(
        );
    }
}
