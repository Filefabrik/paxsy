<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);

namespace Filefabrik\Paxsy\Tests\Support;

/**
 * For Testing purposes, use constants to handle/compare stuff.
 */
class DefaultPackageNames extends PackageNames
{
    public const vendor_name = 'my-test-vendor';

    public const package_name = 'the-test-package';

    public const vendor_namespace = 'MyTestVendor';

    public const package_namespace = 'TheTestPackage';

    public const package_name_singular = 'the-test-package';

    public const package_name_plural = 'the-test-packages';
}
