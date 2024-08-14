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

		$command                = 'make:job';
		$arguments              = ['name' => 'TestJob'];
		$expected_relative_path = 'src/Jobs/TestJob.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Jobs').';',
			'class TestJob',
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

		$command                = 'make:job';
		$arguments              = [/*'name' => 'TestComponent'*/];
		$expected_relative_path = 'src/Jobs/JannisJobblessJob.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Job'),
			'class JannisJobblessJob',
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
			 ->expectsQuestion('Options for MakeJob?', ['test'])
			 ->expectsQuestion('What should the job be named?', 'JannisJobblessJob')
			// todo check class content
			// todo ...maybe bullshit to render feature test
			 ->expectsQuestion('Options for MakeTest?', ['phpunit'])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);

		// test output
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('tests/Feature/Jobs/JannisJobblessJobTest.php');

		checkComponentFilesAndDirectories($expected_full_path);
	}
);
