<?php declare(strict_types=1);
/**
 * PHP version 8.2
 *
 * @see MakeComponent
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Make\MakeComponent;
use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

beforeEach(function() {
	removePackageStack();
});

it(
	'gui with test ',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$command                = 'make:view';
		$arguments              = [/*'name' => 'TestComponent'*/];
		$expected_relative_path = 'resources/views/my-thing/sub.blade.php';

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
			 ->expectsQuestion('Options for MakeView?', ['test'])
			 ->expectsQuestion('What should the view be named?', 'my-thing.sub')
			// todo check class content
			// ->expectsQuestion('Options for MakeTest?', ['phpunit'])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		//expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);

		// test output
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('tests/Feature/View/MyThing/SubTest.php');

		checkComponentFilesAndDirectories($expected_full_path);
	},
);
