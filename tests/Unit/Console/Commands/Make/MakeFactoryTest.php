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
it(
	'create without package',
	function() {
		$command         = 'make:factory';
		$createArguments = ['name' => 'MyTestFactoryInLaravel'];
		// laravel appends Factory
		$expected_relative_path = 'database/factories/MyTestFactoryInLaravelFactory.php';
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

		$expected_full_path = base_path($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
	},
);
it(
	'create Factory Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);
		$command                = 'make:factory';
		$arguments              = ['name' => 'MyTestFactory'];
		$expected_relative_path = 'database/factories/MyTestFactory.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Database\Factories').';',
			'use Illuminate\Database\Eloquent\Factories\Factory;',

		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		$fc = file_get_contents($expected_full_path);
		expect($fc)->toContain(...$expected_substrings);
	},
);
it(
	'Factory interactions no Params',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$command                = 'make:factory';
		$arguments              = [/*'name' => 'MyTestFactory'*/];
		$expected_relative_path = 'database/factories/MyTestNamedFactory.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Database\Factories').';',
			'use Illuminate\Database\Eloquent\Factories\Factory;',

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
			 ->expectsQuestion('Options for MakeFactory?', ['model'])
			 ->expectsQuestion('What should the factory be named?', 'MyTestNamedFactory')
			 ->expectsQuestion('What model should this factory apply to? (Optional)', 'MySuperModel')
			// todo model option was not asked
			//   ->expectsQuestion('What is the name of the model?', 'null')
		;
		//   makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		$fc = file_get_contents($expected_full_path);
		expect($fc)->toContain(...$expected_substrings);
	},
);
it(
	'create Factory Component via command in Default-Vendor-Package Model Exists',
	function() {
		makePackageByArtisanCommand($this);
		$command                = 'make:factory';
		$arguments              = ['name' => 'MyTestFactory'];
		$expected_relative_path = 'database/factories/MyTestFactory.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Database\Factories').';',
			'use Illuminate\Database\Eloquent\Factories\Factory;',

		];
		makeComponentInPackage($this, 'make:model', ['name' => 'MyTest']);

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		$fc = file_get_contents($expected_full_path);
		expect($fc)->toContain(...$expected_substrings);
	},
);
// laravel does not offer model creation or selecting an already existing Model.
it(
	'ask for model creation',
	function() {
		makePackageByArtisanCommand($this);
		$command         = 'make:factory';
		$createArguments = ['name' => 'MyTestFactoryInPackage'];
		// laravel appends Factory
		$expected_relative_path = 'database/factories/MyTestFactoryInPackage.php';
		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => defaultTestPackage()->getPackageName(),
				],
				$createArguments,
			),
		)
			 ->assertExitCode(0)
			 ->expectsQuestion('What is the name of the model?', 'null')
		;

		$expected_full_path = base_path($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
	},
)->todo();
