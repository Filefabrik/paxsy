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
	'create Job Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:listener';
		$arguments              = ['name' => 'TestListener'];
		$expected_relative_path = 'src/Listeners/TestListener.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Listeners').';',
			'class TestListener',
		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'create Listener with event',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:listener';
		$arguments              = ['name' => 'TestListener', '--event' => 'event'];
		$expected_relative_path = 'src/Listeners/TestListener.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Listeners').';',
			'class TestListener',
		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
