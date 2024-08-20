<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

beforeEach(function() {
    removePackageStack();
});

it(
    'create Rule Component via command in Default-Vendor-Package',
    function() {
        makePackageByArtisanCommand($this);

        $command                = 'make:rule';
        $arguments              = ['name' => 'TestRule'];
        $expected_relative_path = 'src/Rules/TestRule.php';
        $expected_substrings    = [
            'namespace ' . DefaultPackageNames::namespacyfy('Rules') . ';',
            'class TestRule',
        ];

        makeComponentInPackage($this, $command, $arguments);
        $expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

        checkComponentFilesAndDirectories($expected_full_path);

        expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
    },
);
it(
    'rule implicit',
    function() {
        makePackageByArtisanCommand($this);
        withGuiInteractions();
        $command                = 'make:rule';
        $arguments              = [];
        $expected_relative_path = 'src/Rules/TestRule2.php';
        $expected_substrings    = [
            'namespace ' . DefaultPackageNames::namespacyfy('Rules') . ';',
            'class TestRule2',
            'public $implicit = true;'
        ];

        $packageName = defaultTestPackage()
            ->getPackageName()
        ;
        $this->artisan(
            $command,
            array_merge(
                [
                    '--package' => $packageName,
                ],
                $arguments,
            ),
        )->expectsQuestion('Options for MakeRule?', ['implicit'])
             ->expectsQuestion('What should the rule be named?', 'TestRule2')
             ->assertOk()
        ;


        $expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

        checkComponentFilesAndDirectories($expected_full_path);

        expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
    },
);
