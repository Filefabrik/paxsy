<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Str;

use UnexpectedValueException;

/**
 * Changes Kebab Cases
 */
class CaseConverters
{
    /**
     * @param string $vendor
     * @param string $packageName
     *
     * @return string
     */
    public static function composerName(string $vendor, string $packageName): string
    {
        // todo verify lower cases
        // no space
        // no slash

        return $vendor.'/'.$packageName;
    }

    /**
     * @param string $composerName
     *
     * @return array
     */
    public static function fromVendorPackageName(string $composerName): array
    {
        $ex = self::splitComposerName($composerName);

        return (count($ex) === 2) ? $ex :
            throw new UnexpectedValueException('Composer-Name does not looks valid:'.$composerName);
    }

    /**
     * @param string $composerName
     *
     * @return array
     */
    public static function splitComposerName(string $composerName): array
    {
        return explode('/', $composerName);
    }
}
