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
	'create Models Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:model';
		$arguments              = ['name' => 'TestModel'];
		$expected_relative_path = 'src/Models/TestModel.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Models').';',
			'class TestModel',
		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'model without package',
	function() {
		forcePaxsyConfig();
		$command         = 'make:model';
		$createArguments = ['name' => 'TestModelWithoutPackage'];

		$this->artisan(
			$command,
			array_merge(
				[

				],
				$createArguments,
			),
		)
			 ->assertExitCode(0)
		;

		$expected_substrings = [
			'namespace App\Models;',
			'class TestModelWithoutPackage',
		];
		$expected_full_path = app_path('Models/TestModelWithoutPackage.php');

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'Model with Controller',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:model';
		$arguments              = ['name' => 'TestModelWithKaumtroller', '-c' => true];
		$expected_relative_path = 'src/Models/TestModelWithKaumtroller.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Models').';',
			'class TestModelWithKaumtroller',
		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'Model with All',
	function() {
		forcePaxsyConfig();
		makePackageByArtisanCommand($this);

		$command                = 'make:model';
		$arguments              = ['name' => 'TestModelWithAll', '--all' => true];
		$expected_relative_path = 'src/Models/TestModelWithAll.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Models').';',
			'class TestModelWithAll',
		];
		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => defaultTestPackage()->getPackageName(),
				],
				$arguments,
			),
		)
			 ->assertExitCode(0)
		;
		// todo check factory and controllers also
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
