<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;
use Filefabrik\Paxsy\Tests\Support\LivewireComponentNames;

beforeEach(function() {
	removePackageStack();
});
it(
	'original command is working create without paxsy --package flag',
	function() {
		$command = 'livewire:make';

		$this->artisan(
			$command,
			[...$createArguments ?? []],
		)
			 ->expectsQuestion('What is the name?', 'my-lv-Compo')
			 ->expectsQuestion('Do you want to make this an inline component?', 0)
			 ->expectsQuestion('Do you want to create a test file?', 1)
			 ->assertExitCode(0)
		;
	},
);
it(
	'create Paxsy Livewire Component',
	function() {
		// Todo at first, livewire has to be installed into testbench-core/laravel
		$defaultPackage = defaultTestPackage();

		makePackageByArtisanCommand($this);

		makeComponentInPackage(
			$this,
			'make:livewire',
			['name'      => 'my-lv-Compo',
				'--package' => $defaultPackage->getPackageName()],
		);

		// preparations
		$expected_class_path = DefaultPackageNames::VendorPackageComponentPath('src/'.LivewireComponentNames::default_location.'/'.'MyLvCompo'.'.php');

		$expected_view_path = DefaultPackageNames::VendorPackageComponentPath(LivewireComponentNames::defaultResourceDir());

		$livewireComponentNamespace = DefaultPackageNames::namespacyfy(LivewireComponentNames::default_namespace_prefix);
		/**
		 * Testing Class File-Content!
		 */
		$expected_substrings = [
			'namespace '.$livewireComponentNamespace.';',
			'class '.LivewireComponentNames::default_component_class.' extends Component',
			'use Livewire\Component;',
			'public function render()',
			//  return view('the-test-package::livewire.my-lv-Compo');
			'return view(\''.DefaultPackageNames::package_name.'::livewire.'.LivewireComponentNames::default_blade_prefix.'\');',
		];

		expect($expected_class_path)
			->toBeReadableFile()
			// livewire class
			->and(file_get_contents($expected_class_path))
			->toContain(...$expected_substrings)
			// blade
			->and($expected_view_path)
			->toBeReadableFile()
		;
	},
);

it(
	'invalid class name',
	function() {
		$defaultPackage = defaultTestPackage();

		makePackageByArtisanCommand($this);
		$command = 'make:livewire';

		$buggyClassName = 'TestIngClassname~#';
		$this->artisan(
			$command,
			['name'      => 'my \\ # ~ lv // ..testIngClassname~ #',
				'--package' => $defaultPackage->getPackageName()],
		)
			 ->expectsOutputToContain("Class is invalid: {$buggyClassName}")
			 ->assertExitCode(0)
		;
	},
);

it(
	'reserved class name',
	function() {
		$defaultPackage = defaultTestPackage();

		makePackageByArtisanCommand($this);
		$command = 'make:livewire';

		$buggyClassName = 'Protected';
		$this->artisan(
			$command,
			['name'      => 'protected',
				'--package' => $defaultPackage->getPackageName()],
		)
			 ->expectsOutputToContain("Class is reserved: {$buggyClassName}")
			 ->assertExitCode(0)
		;
	},
);
// todo expectation
it(
	'create with test',
	function() {
		$defaultPackage = defaultTestPackage();

		makePackageByArtisanCommand($this);
		$command = 'make:livewire';
		$this->artisan(
			$command,
			['name'      => 'protected',
				'--package' => $defaultPackage->getPackageName()],
		);
	},
);
// todo expectation
it(
	'create same livewire component again',
	function() {
		$defaultPackage = defaultTestPackage();

		makePackageByArtisanCommand($this);

		$this->artisan(
			'make:livewire',
			['name'      => 'my-lv-Compo',
				'--package' => $defaultPackage->getPackageName(),
				'--test'    => true],
		);
	},
);
