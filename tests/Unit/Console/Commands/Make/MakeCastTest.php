<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

beforeEach(function() {
	removePackageStack();
});

// todo not testable in package without laravel context
it(
	'Call original make:cast',
	function() {
		$this->artisan('make:cast', ['--help' => true, 'name' => 'myTestCast'])
			 ->assertExitCode(0)
		;
	},
);
it(
	'create Cast Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:cast';
		$arguments              = ['name' => 'JsonCast'];
		$expected_relative_path = '/src/Casts/JsonCast.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Casts'),
			'class JsonCast',
		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
