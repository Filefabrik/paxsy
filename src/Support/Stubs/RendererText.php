<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Stubs;

class RendererText implements VariablesRendererInterface
{
    /**
     * @param array $variablesMap
     * @param mixed $objects
     *
     * @return array
     */
    public static function toArray(array $variablesMap, mixed $objects = null): array
    {
        return $variablesMap;
    }
}
