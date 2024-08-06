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
	'create Middleware Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:middleware';
		$arguments              = ['name' => 'TestMiddleware'];
		$expected_relative_path = 'src/Http/Middleware/TestMiddleware.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Middleware').';',
			'class TestMiddleware',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
