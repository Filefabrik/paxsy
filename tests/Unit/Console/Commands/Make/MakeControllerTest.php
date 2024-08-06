<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;
use Illuminate\Filesystem\Filesystem;

beforeEach(function() {
	removePackageStack();
});

it(
	'create Controller Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:controller';
		$arguments              = ['name' => 'TestController'];
		$expected_relative_path = 'src/Http/Controllers/TestController.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestController',
			// in laravel 11 has changed. perhaps check old controller stubs
			'use Illuminate\Http\Request;',
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
				 //->expectsQuestion('Extra additional Options?','Create')
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
// todo remove model from /app/Models/TestModels
it(
	'without package but with model',
	function() {
		makePackageByArtisanCommand($this);

		$modelClass = 'TestingControllerModels';
		(new Filesystem())->delete([base_path('app/Models/TestingControllerModels.php'), base_path('app/Http/Controllers/TestController2.php')]);
		$command   = 'make:controller';
		$arguments = ['name' => 'TestController2', '--model' => $modelClass];

		$this->artisan(
			$command,
			[
			] + [...$arguments],
		)
			 ->expectsQuestion('A App\Models\TestingControllerModels model does not exist. Do you want to generate it?', 1)
			 ->assertOk()
		;
	},
);
it(
	'without buggy model name',
	function() {
		makePackageByArtisanCommand($this);
		$packageName = defaultTestPackage()
			->getPackageName()
		;

		$command   = 'make:controller';
		$arguments = ['name' => 'TestController2', '--model' => ' m d \\ dek// '];
		$this->artisan(
			$command,
			['--package' => $packageName,
			] + [...$arguments],
		);
	},
)->throws(InvalidArgumentException::class, 'Model name contains invalid characters.');

it(
	'create Controller with Model',
	function() {
		makePackageByArtisanCommand($this);
		$modelClass             = 'TestModels';
		$command                = 'make:controller';
		$arguments              = ['name' => 'TestController2', '--model' => $modelClass];
		$expected_relative_path = 'src/Http/Controllers/TestController2.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestController2',
			// in laravel 11 has changed. perhaps check old controller stubs
			'use Illuminate\Http\Request;',
		];

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$absoluteModelClass = 'MyTestVendor\TheTestPackage\Models\TestModels';
		$this->artisan(
			$command,
			[
				'--package' => $packageName,
			] + [...$arguments],
		)
			 ->expectsQuestion("A {$absoluteModelClass} model does not exist. Do you want to generate it?", 1)
		;

		//     makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'create Controller with Model and Request',
	function() {
		makePackageByArtisanCommand($this);
		$modelClass             = 'TestModels';
		$command                = 'make:controller';
		$arguments              = ['name' => 'TestControllerWithModelAndRequest', '--model' => $modelClass, '-R' => true];
		$expected_relative_path = 'src/Http/Controllers/TestControllerWithModelAndRequest.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestControllerWithModelAndRequest',

		];

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$absoluteModelClass = 'MyTestVendor\TheTestPackage\Models\TestModels';
		$this->artisan(
			$command,
			[
				'--package' => $packageName,
			] + [...$arguments],
		)
			 ->expectsQuestion("A {$absoluteModelClass} model does not exist. Do you want to generate it?", 1)
		;

		//     makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
