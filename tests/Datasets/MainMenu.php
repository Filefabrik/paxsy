<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);


dataset(
    'main menu',
    [
        ['label' => 'add components', 'method' => 'handle_package'],
        ['label' => 'new laravel composer package', 'method' => 'create_package'],
        ['label' => 'list packages', 'method' => 'list_packages'],
        // Handle composer Package into the Laravel-Host
        [
            'label'  => 'add VendorPackage with there Repository (Laravel Host Composer )',
            'method' => 'composer_add_repository_vendor_package',
        ],
        [
            'label'  => 'remove VendorPackage with there Repository (Laravel Host Composer)',
            'method' => 'composer_remove_repository_vendor_package',
        ],
        ['label' => 'Composer Update (experimental)', 'method' => 'composer_update'],
    ],
);
