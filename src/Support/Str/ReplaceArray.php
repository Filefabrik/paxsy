<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Str;

class ReplaceArray
{
    public static function searchReplace(string $s, array $searchReplace): string
    {
        return (string) str_replace(array_keys($searchReplace), array_values($searchReplace), $s);
    }
}
