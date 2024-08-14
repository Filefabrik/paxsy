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
it(
	'command with test',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$command                = 'make:command';
		$arguments              = [/*'name' => 'TestComponent'*/];
		$expected_relative_path = 'src/Console/Commands/FlightCommand.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Console\Commands'),
			'class FlightCommand',
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
			 ->expectsQuestion('Options for MakeCommand?', ['test'])
			 ->expectsQuestion('What should the console command be named?', 'FlightCommand')
			// todo check class content
			// todo ...maybe bullshit to render feature test
			 ->expectsQuestion('Options for MakeTest?', ['phpunit'])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);

		// test output
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('tests/Feature/Console/Commands/FlightCommandTest.php');

		checkComponentFilesAndDirectories($expected_full_path);
	}
);
