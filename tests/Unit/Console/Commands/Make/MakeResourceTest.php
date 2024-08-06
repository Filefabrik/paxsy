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
	'create Resource Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:resource';
		$arguments              = ['name' => 'TestResource'];
		$expected_relative_path = 'src/Http/Resources/TestResource.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Resources').';',
			'class TestResource',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
