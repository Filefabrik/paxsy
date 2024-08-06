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
	'create Controller with --model',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$command                = 'make:controller';
		$arguments              = ['name' => 'TestControllerWithModelAndRequest', '--model' => true, '-R' => true];
		$expected_relative_path = 'src/Http/Controllers/TestControllerWithModelAndRequest.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestControllerWithModelAndRequest',

			'use '.DefaultPackageNames::namespacyfy('Http\Requests\StoreTestControllerWithModelAndRequestRequest'),
			'use '.DefaultPackageNames::namespacyfy('Http\Requests\UpdateTestControllerWithModelAndRequestRequest'),
		];

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$absoluteModelClass = 'MyTestVendor\TheTestPackage\Models\TestControllerWithModelAndRequest';

		$this->artisan(
			$command,
			[
				'--package' => $packageName,
			] + [...$arguments],
		)
			 ->expectsQuestion('Options for MakeController?', [])
			 ->expectsQuestion('A '.$absoluteModelClass.' model does not exist. Do you want to generate it?', 1)
			 ->expectsQuestion('Options for MakeModel?', [])
			// store
			 ->expectsQuestion('Options for MakeRequest?', [])
			// update
			 ->expectsQuestion('Options for MakeRequest?', [])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'create Controller with Model and Request',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$modelClass             = 'TestControllerWithModelAndRequest';
		$command                = 'make:controller';
		$arguments              = ['name' => 'TestControllerWithModelAndRequest', '--model' => $modelClass, '-R' => true];
		$expected_relative_path = 'src/Http/Controllers/TestControllerWithModelAndRequest.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestControllerWithModelAndRequest',

			'use '.DefaultPackageNames::namespacyfy('Http\Requests\StoreTestControllerWithModelAndRequestRequest'),
			'use '.DefaultPackageNames::namespacyfy('Http\Requests\UpdateTestControllerWithModelAndRequestRequest'),
		];

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$absoluteModelClass = 'MyTestVendor\TheTestPackage\Models\\'.$modelClass;

		$this->artisan(
			$command,
			[
				'--package' => $packageName,
			] + [...$arguments],
		)
			 ->expectsQuestion('Options for MakeController?', [])
			 ->expectsQuestion('A '.$absoluteModelClass.' model does not exist. Do you want to generate it?', 1)
			 ->expectsQuestion('Options for MakeModel?', [])
			// store
			 ->expectsQuestion('Options for MakeRequest?', [])
			// update
			 ->expectsQuestion('Options for MakeRequest?', [])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'create extended Controller',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();
		$defaultPackage = defaultTestPackage();

		$dir = $defaultPackage->packageBasePath().'/src/Http/Controllers';

		(new Filesystem())->ensureDirectoryExists($dir);

		file_put_contents(
			$dir.'/Controller.php',
			<<<PHP
namespace MyTestVendor\TheTestPackage\Http\Controllers;
class Controller{}
PHP,
		);

		$command                = 'make:controller';
		$arguments              = ['name' => 'TestControllerExtending', '--model' => null];
		$expected_relative_path = 'src/Http/Controllers/TestControllerExtending.php';

		$expected_substrings = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestControllerExtending',

			'use '.DefaultPackageNames::namespacyfy('Http\Controllers\Controller'),

		];

		$packageName = $defaultPackage->getPackageName();

		$this->artisan(
			$command,
			[
				'--package' => $packageName,
			] + [...$arguments],
		)
			 ->expectsQuestion('Options for MakeController?', [])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
