<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

use Illuminate\Console\Command;

interface WithInterface
{
	public function execute(): static;

	public function add(string $expression, mixed $flags = null): static;

	public function addVendorPackage(...$params): static;

	public function removeVendorPackage(...$params): static;

	public function addRepository(...$params): static;

	public function removeRepository(...$params): static;

	public function lastTransactionToConsole(Command $command);

	public function singleMode(): static;
}
