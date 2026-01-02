<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Paxsy\Support\VendorPackageNames;

class Facade
{
    public static function variables(VendorPackageNames $vendorPackageNames, ?FromConfig $config = null): Variables
    {
        $config ??= new FromConfig('default');

        return (new Variables())->setReplacementMaps($config->replacementMap())
                                ->setRendererClasses($config->getVariablesRenderer())
                                ->addVariables('package', $vendorPackageNames)
        ;
    }
}
