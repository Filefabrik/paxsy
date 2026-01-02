<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Helper;

trait TraitGenericLines
{
    /**
     * @var array
     */
    private array $lines = [];

    public function mapLinesInto($lineMethod): void
    {
        array_map(fn($line) => $lineMethod->line($line), $this->getLines());
    }

    /**
     * @param $content
     *
     * @return $this
     */
    protected function line($content): static
    {
        $this->lines[] = $content;

        return $this;
    }

    public function getLines(): array
    {
        return $this->lines;
    }
}
