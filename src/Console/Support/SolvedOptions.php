<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Support;

/**
 * Options through chained/nested make call commands
 */
class SolvedOptions
{
    /**
     * @var array
     */
    public static array $solved = [];

    /**
     * @param string ...$solves
     */
    public static function addSolvedComponent(...$solves): void
    {
        foreach ((array)$solves as $solved) {
            self::$solved[$solved] = true;
        }
    }

    /**
     * @return array
     */
    public static function solvedAsOption(): array
    {
        $opts = [];
        foreach (self::$solved as $n => $null) {
            $opts[] = str_replace('make:', '', $n);
        }

        return $opts;
    }

    public static function hasSolvedOptions(): bool
    {
        return (bool)self::$solved;
    }

    public static function reset(): void
    {
        self::$solved = [];
    }
}
