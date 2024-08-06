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
	'Gui interactions Options correctly',
	function(string $selected) {
		makePackageByArtisanCommand($this);

		withGuiInteractions();

		$command   = 'make:controller';
		$arguments = ['name' => 'TestController'];

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
			 ->expectsQuestion('Options for MakeController?', [$selected])
		;
	},
)->with('Controller Make Options');

it(
	'Gui interactions with Parent and Model',
	function() {
		makePackageByArtisanCommand($this);

		withGuiInteractions();

		$command   = 'make:controller';
		$arguments = ['name' => 'TestController', ['--parent' => 'parents']];

		$packageName = defaultTestPackage()
			->getPackageName()
		;

		//	$selected = ['parent'];
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
			 ->expectsQuestion('Options for MakeController?', ['model' => 'Something', 'parent' => 'ArticleModel'])
			 ->expectsQuestion(
			 	'A MyTestVendor\TheTestPackage\Models\ArticleModel model does not exist. Do you want to generate it?',
			 	true
			 )->expectsQuestion('Options for MakeModel?', [])
			 ->expectsQuestion(
			 	'A MyTestVendor\TheTestPackage\Models\Something model does not exist. Do you want to generate it?',
			 	true
			 )
			 ->expectsQuestion('Options for MakeModel?', [])
		;

		// check outputs
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('src/Models/Something.php');
		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...['class Something extends Model']);

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('src/Models/ArticleModel.php');

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...['class ArticleModel extends Model']);
	},
);

it(
	'create Controller Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		withGuiInteractions();

		$command                = 'make:controller';
		$arguments              = ['name' => 'TestController'];
		$expected_relative_path = 'src/Http/Controllers/TestController.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Http\Controllers'),
			'class TestController',
			// in laravel 11 has changed. perhaps check old controller stubs
			'use Illuminate\Http\Request;',
		];

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
			 ->expectsQuestion('Options for MakeController?', ['api'])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
