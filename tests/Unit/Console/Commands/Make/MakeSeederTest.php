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
	'create Seeder Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:seeder';
		$arguments              = ['name' => 'TestSeederTd'];
		$expected_relative_path = 'database/seeders/TestSeederTd.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Database\Seeders'),
			'use Illuminate\Database\Seeder',
			'class TestSeederTd extends Seeder',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'create Seeder without package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:seeder';
		$arguments              = ['name' => 'TestingSeederPure'];
		$expected_relative_path = 'database/seeders/TestingSeederPure.php';
		$expected_substrings    = [
			'namespace Database\Seeders;',
			'use Illuminate\Database\Seeder;',
			'class TestingSeederPure extends Seeder',
		];

		$this->artisan(
			$command,
			$arguments,
		)
			 ->assertExitCode(0)
		;

		$expected_full_path = base_path($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
		//	expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	}
);
