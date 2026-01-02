<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Tests\Support;

abstract class PackageNames
{
    /**
     * directory starts after /app-modules
     *
     * @return string
     */
    public static function PackagePath(): string
    {
        return base_path(static::RelativePackagePath());
    }

    /**
     * @return string
     */
    public static function RelativePackagePath(): string
    {
        return static::pathyfy([currentStackName(), static::package_name]);
    }

    /**
     * @param $segments
     *
     * @return string
     */
    public static function pathyfy($segments): string
    {
        return implode('/', $segments);
    }

    public static function VendorPackageComponentPath($segment): string
    {
        return base_path(implode('/', [static::RelativePackagePath(), $segment]));
    }

    /**
     * Compiles Namespaces
     *
     * @param array|string $extras
     *
     * @return string
     */
    public static function namespacyfy(array|string $extras = []): string
    {
        if (is_string($extras)) {
            $extras = [$extras];
        }
        $def = array_merge([static::vendor_namespace, static::package_namespace], $extras ?? []);

        return implode('\\', $def);
    }
}
