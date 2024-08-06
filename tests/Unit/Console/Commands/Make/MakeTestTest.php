<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

beforeEach(function() {
	removePackageStack();
	clearLaravelFiles();
});
afterEach(function() {
	clearLaravelFiles();
});
// Todo Feature or Unit Test since 11?
// todo Check is Pest Or is PHPUnit
it(
	'without package Unit Unit',
	function() {
		$arguments = ['name' => 'PHPUnit_Unit_Laravel', '--phpunit' => true, '--unit' => true];
		$command   = 'make:test';
		$this->artisan(
			$command,
			array_merge(
				[

				],
				$arguments,
			),
		)
			 ->assertExitCode(0)
		;
	},
);
it(
	'without package Pest Feature',
	function() {
		$arguments = ['name' => 'MyPESTFeatureTestingIntoLaravel', '--phpunit' => false, '--unit' => false];
		$command   = 'make:test';
		$this->artisan(
			$command,
			array_merge(
				[

				],
				$arguments,
			),
		)
			 ->assertExitCode(0)
		;
	},
);
it(
	'create Test PHPUnit UNIT Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:test';
		$arguments              = ['name' => 'MyUnitPHPUnitTesting', '--phpunit' => true, '--unit' => true];
		$expected_relative_path = 'tests/Unit/MyUnitPHPUnitTesting.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Tests\Unit').';',
			'use PHPUnit\Framework\TestCase;',
			'class MyUnitPHPUnitTesting extends TestCase',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'create Test PHPUnit FEATURE Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:test';
		$arguments              = ['name' => 'MyFeaturePHPUnitTesting', '--phpunit' => true];
		$expected_relative_path = 'tests/Feature/MyFeaturePHPUnitTesting.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Tests\Feature').';',
			'use Tests\TestCase;',
			'class MyFeaturePHPUnitTesting extends TestCase',
			'public function test_example(): void',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'create Test Pest UNIT Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:test';
		$arguments              = ['name' => 'MyUnitPESTPHPTesting', '--pest' => true, '--unit' => true];
		$expected_relative_path = 'tests/Unit/MyUnitPESTPHPTesting.php';
		$expected_substrings    = ['test(\'example\', function () {',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'create Test Pest FEATURE Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:test';
		$arguments              = ['name' => 'MyFeaturePESTPHPTesting', '--pest' => true];
		$expected_relative_path = 'tests/Feature/MyFeaturePESTPHPTesting.php';
		$expected_substrings    = ['test(\'example\', function () {',
			'$response = $this->get(\'/\');',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
