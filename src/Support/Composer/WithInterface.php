<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

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
