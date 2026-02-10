<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Paxsy\Support\VendorPackageNames;

/**
 * Render all 'package.relPackageDir' and so on
 */
class RendererPackage implements VariablesRendererInterface
{
    /**
     * @param array                   $variablesMap
     * @param VendorPackageNames|null $objects
     *
     * @return array
     */
    public static function toArray(array $variablesMap, mixed $objects = null): array
    {
        $vars = [];

        foreach ($variablesMap as $key => $value) {
            $parsedV    = self::get($value, $objects);
            $vars[$key] = $parsedV;
        }

        return $vars;
    }

    /**
     * @param string             $segmentExpression
     * @param VendorPackageNames $moduleConfig
     *
     * @return ?string
     */
    public static function get(string $segmentExpression, VendorPackageNames $moduleConfig): ?string
    {
        return match ($segmentExpression) {
            'relPackageDir'    => $moduleConfig->relPackageDir(),
            'packagePath'      => $moduleConfig->packageBasePath(),
            'composerName'     => $moduleConfig->toComposerName(),
            'vendor.class'     => $moduleConfig->vendor
                                               ->toClass(),
            'package.class'    => $moduleConfig->package
                                               ->toClass(),
            'package.singular' => $moduleConfig->package
                                               ->toSingularName(),
            'package.plural'   => $moduleConfig->package
                                               ->toPluralName(),
            'package.name'     => $moduleConfig->package
                                               ->toName(),
            // todo handle null because wanted and not found.
            // todo log error
            default            => null,
        };
    }
}
