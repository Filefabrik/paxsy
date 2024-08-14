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
	'create Cast Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:mail';
		$arguments              = ['name' => 'TestMail'];
		$expected_relative_path = 'src/Mail/TestMail.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Mail').';',
			'class TestMail',
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

		$command                = 'make:mail';
		$arguments              = [/*'name' => 'TestComponent'*/];
		$expected_relative_path = 'src/Mail/Mailasse.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Mail'),
			'class Mailasse',
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
			 ->expectsQuestion('Options for MakeMail?', ['test'])
			 ->expectsQuestion('What should the mailable be named?', 'Mailasse')
			// todo check class content
			// todo ...maybe bullshit to render feature test
			 ->expectsQuestion('Options for MakeTest?', ['phpunit'])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);

		// test output
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('tests/Feature/Mail/MailasseTest.php');

		checkComponentFilesAndDirectories($expected_full_path);
	}
);
