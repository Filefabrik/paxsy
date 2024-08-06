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
	'create Command Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:command';
		$arguments              = ['name' => 'TestCommand'];
		$expected_relative_path = 'src/Console/Commands/TestCommand.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Console\Commands'),
			'use Illuminate\Console\Command',
			'class TestCommand extends Command',
			'test-package:test',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
