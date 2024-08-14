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
	'create View-Component Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);
		$defaultPackage         = defaultTestPackage();
		$command                = 'make:component';
		$arguments              = ['name' => 'TestComponent'];
		$expected_relative_path = 'src/View/Components/TestComponent.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('View\Components'),
			'class TestComponent',
			'use Closure;',
			'use Illuminate\Contracts\View\View;',
			'use Illuminate\View\Component;',
			'return view(\''.$defaultPackage->getPackageName().'::components.test-component\');',
		];
		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
it(
	'with inline ',
	function() {
		makePackageByArtisanCommand($this);
		$command                = 'make:component';
		$arguments              = ['name' => 'TestInlineComponent'];
		$expected_relative_path = 'src/View/Components/TestInlineComponent.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('View\Components'),
			'class TestInlineComponent extends Component',
			'use Closure;',
			'use Illuminate\Contracts\View\View;',
			'use Illuminate\View\Component;',
			"<<<'blade'\n<div>\n    <!-- ",
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
					'--inline'  => true,
				],
				$arguments,
			),
		)
			 ->assertExitCode(0)
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);

it(
	'gui with test ',
	function() {
		makePackageByArtisanCommand($this);
		withGuiInteractions();

		$defaultPackage         = defaultTestPackage();
		$command                = 'make:component';
		$arguments              = [/*'name' => 'TestComponent'*/];
		$expected_relative_path = 'src/View/Components/FlightComponent.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('View\Components'),
			'class FlightComponent',
			'use Closure;',
			'use Illuminate\Contracts\View\View;',
			'use Illuminate\View\Component;',
			'return view(\''.$defaultPackage->getPackageName().'::components.flight-component\');',
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
			 ->expectsQuestion('Options for MakeComponent?', ['test'])
			 ->expectsQuestion('What should the component be named?', 'FlightComponent')
			// todo check class content
			 ->expectsQuestion('Options for MakeTest?', ['phpunit'])
		;

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);

		// test output
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath('tests/Feature/View/Components/FlightComponentTest.php');

		checkComponentFilesAndDirectories($expected_full_path);
	},
);
