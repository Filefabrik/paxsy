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
	'policy without package',
	function() {
		clearLaravelDirectories([app_path('/Policies')]);

		$this->artisan('make:policy')
			 ->expectsQuestion('What should the policy be named?', 'MyTestPolicy')
			 ->expectsQuestion('What model should this policy apply to? (Optional)', 'TestPolicy')
		;
	},
);

it(
	'policy without policy name',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$command   = 'make:policy';
		$arguments = [];
		//  $arguments              = ['name' => 'TestPolicy'];
		$expected_relative_path = 'src/Policies/TryoutPolicy.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Policies'),
			'class TryoutPolicy',
			sprintf('use %s\%s', DefaultPackageNames::namespacyfy('Models'), 'NoModel;'),

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
			 ->expectsQuestion('Options for MakePolicy?', ['model'])
			 ->expectsQuestion('What should the policy be named?', 'TryoutPolicy')
			 ->expectsQuestion('What model should this policy apply to? (Optional)', 'NoModel')
		;

		//   makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'policy with model interaction',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$command                = 'make:policy';
		$arguments              = [];
		$arguments              = ['name' => 'PlayTestPolicy'];
		$expected_relative_path = 'src/Policies/TestPolicy.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Policies'),
			'class TestPolicy',

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
			// if purely model (bool/flag)

			 ->expectsQuestion('Options for MakePolicy?', ['model'])
		;

		//   makeComponentInPackage($this, $command, $arguments);
		//   $expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		//    checkComponentFilesAndDirectories($expected_full_path);

		//    expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
