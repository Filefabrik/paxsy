<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\PaxsyCommand;

/** @copyright-header * */
beforeEach(function() {
	// todo all reset for make:package into function ...

	currentStackName();
	removePackageStack();
	rerouteStubsDirectory();
});

it(
	'Paxsy command',
	function() {
		expect(class_exists(PaxsyCommand::class))->toBeTrue('Paxsy Admin Command exists');
	},
);
it(
	'handle package with no packages',
	function() {
		// prevent from composer update
		useShellDisabled();

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'handle_package')
			 ->assertExitCode(1)
		;
	},
);
it(
	'task create package missing vendor',
	function() {
		// prevent from composer update
		useShellDisabled();

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'create_package')
			 ->expectsQuestion('"your-vendor-name" of the package', '')
			 ->assertExitCode(1)
		;
	},
);
it(
	'task create package missing package',
	function() {
		// prevent from composer update
		useShellDisabled();

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'create_package')
			 ->expectsQuestion('"your-vendor-name" of the package', 'testVendor')
			 ->expectsQuestion('Name of your package?', '')
			 ->assertExitCode(1)
		;
	},
);

it(
	'list vendor package',
	function() {
		// prevent from composer update
		useShellDisabled();

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'list_packages')
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
			 ->assertExitCode(0)
		;
	},
);
it(
	'create Vendor Package',
	function() {
		// prevent from composer update
		useShellDisabled();

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'new laravel composer package')
			 ->expectsQuestion('"your-vendor-name" of the package', 'PaxsyVendorName')
			 ->expectsQuestion('Name of your package?', 'PackEdgeName')
			 ->expectsQuestion(
			 	'Choose the "make" command for your "paxsy-vendor-name/pack-edge-name" package?',
			 	'make:model',
			 )
			 ->expectsQuestion('What should the model be named?', 'EdgyMododoodel')
			 ->expectsQuestion(
			 	'Choose the "make" command for your "paxsy-vendor-name/pack-edge-name" package?',
			 	PaxsyCommand::QUIT,
			 )
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
		;
	},
);

it(
	'enable package',
	function() {
		// prevent from composer update
		useShellDisabled();
		makePackageByArtisanCommand($this);
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'composer_add_repository_vendor_package')
			 ->expectsQuestion('package to call a make: command', 'paxsy-vendor-name/pack-edge-name')
		;
	},
);
it(
	'jump into but quit',
	function() {
		// prevent from composer update
		useShellDisabled();
		makePackageByArtisanCommand($this);

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'handle_package')
			 ->expectsQuestion('package to call a make: command', PaxsyCommand::QUIT)

			//->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
			 ->assertExitCode(0)
		;
	},
);
it(
	'handle selected task and quit',
	function() {
		// prevent from composer update
		useShellDisabled();
		makePackageByArtisanCommand($this);
		$defaultPackage = defaultTestPackage();
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'handle_package')
			 ->expectsQuestion('package to call a make: command', $defaultPackage->vendorPackageName())
			 ->expectsQuestion(
			 	'Choose the "make" command for your "'.$defaultPackage->vendorPackageName().'" package?',
			 	PaxsyCommand::QUIT,
			 )
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
			 ->assertExitCode(0)
		;
	},
);
it(
	'simply composer update',
	function() {
		// prevent from composer update
		useShellDisabled();
		makePackageByArtisanCommand($this);
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'composer_update')
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
			 ->assertExitCode(0)
		;
	},
);
it(
	'composer remove repository vendor package',
	function() {
		// prevent from composer update
		useShellDisabled();
		makePackageByArtisanCommand($this);
		$defaultPackage = defaultTestPackage();
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'composer_remove_repository_vendor_package')
			 ->expectsQuestion('package to call a make: command', $defaultPackage->vendorPackageName())
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
			 ->assertExitCode(0)
		;
	},
);

it(
	'handle selected task with quit',
	function() {
		useShellDisabled();
		makePackageByArtisanCommand($this);
		//	$defaultPackage = defaultTestPackage();
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'handle_package')
			 ->expectsQuestion('package to call a make: command', PaxsyCommand::QUIT)
		;
	},
);
it(
	'input vendor without ui',
	function() {
		useShellDisabled();

		config()->set('paxsy.ui_vendor_select', '');
		config()->set('paxsy.ui_default_vendor', 'playvendor');
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'create_package')
			 ->expectsQuestion('Name of your package?', '')
			 ->assertExitCode(1)
		;
	},
);
it(
	'input vendor without ui and vendor not set',
	function() {
		useShellDisabled();

		config()->set('paxsy.ui_vendor_select');
		config()->set('paxsy.ui_default_vendor');
		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'create_package')
			 ->expectsQuestion('"your-vendor-name" of the package', 't')
			 ->expectsQuestion('Name of your package?', 't')
			 ->expectsQuestion('Choose the "make" command for your "t/t" package?', PaxsyCommand::QUIT)
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
			 ->assertExitCode(0)
		;
	},
);
it(
	'multiple stubs sets',
	function() {
		$defStubsSet = config('paxsy.stub_sets.default');

		config()->set(
			'paxsy.stub_sets',
			['set1' => $defStubsSet, 'set2' => $defStubsSet, 'set3' => $defStubsSet],
		);
		// prevent from composer update
		useShellDisabled();

		$this->artisan('paxsy')
			 ->expectsQuestion('Paxsy Menu', 'new laravel composer package')
			 ->expectsQuestion('"your-vendor-name" of the package', 'PaxsyVendorName')
			 ->expectsQuestion('Name of your package?', 'PackEdgeName')
			 ->expectsQuestion('Which preconfigured Stub-Set?', 'set2')
			 ->expectsQuestion(
			 	'Choose the "make" command for your "paxsy-vendor-name/pack-edge-name" package?',
			 	PaxsyCommand::QUIT,
			 )
			 ->expectsQuestion('Paxsy Menu', PaxsyCommand::QUIT)
		;
	},
);
