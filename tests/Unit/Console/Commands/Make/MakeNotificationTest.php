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
	'create Notification Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:notification';
		$arguments              = ['name' => 'TestNotification'];
		$expected_relative_path = 'src/Notifications/TestNotification.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Notifications'),
			'class TestNotification',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
    'with php unit',
    function() {
        makePackageByArtisanCommand($this);
        withGuiInteractions();

        $command                = 'make:notification';
        $arguments              = [/*'name' => 'TestComponent'*/];
        $expected_relative_path = 'src/Notifications/Noodyweacation.php';
        $expected_substrings    = [
            'namespace '.DefaultPackageNames::namespacyfy('Notifications'),
            'class Noodyweacation',
        ];

        //	makeComponentInPackage($this, $command, $arguments);

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
        )
             ->assertExitCode(0)
             ->expectsQuestion('Options for MakeNotification?', ['test'])
             ->expectsQuestion('What should the notification be named?', 'Noodyweacation')
            // todo check class content
            // todo ...maybe bullshit to render feature test
             ->expectsQuestion('Options for MakeTest?', ['phpunit'])
        ;

        $expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

        checkComponentFilesAndDirectories($expected_full_path);
        expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);

        // test output
        $expected_full_path = DefaultPackageNames::VendorPackageComponentPath('tests/Feature/Notifications/NoodyweacationTest.php');

        checkComponentFilesAndDirectories($expected_full_path);
    }
);
