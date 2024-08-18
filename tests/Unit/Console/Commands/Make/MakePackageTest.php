<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */
beforeEach(function() {
	currentStackName();
	removePackageStack();
	rerouteStubsDirectory();
});

it(
	'stack created',
	function() {
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default'])
			 ->assertExitCode(0)
			 ->expectsOutputToContain('Stack does not exist!')
		;
	},
);
it(
	'missing vendor package stub',
	function() {
		$this->artisan('paxsy:package', ['vendor' => '', 'package' => '', 'stubs' => ''])
			 ->assertExitCode(1)
			 ->expectsOutputToContain('missing a part vendor:"" or package:"" or stubs:""')
		;
	},
);
it(
	'missing stubs directory',
	function() {
		$msg = 'Missing Stubs in /config/paxsy.php on selected stubs: "defailed" in: paxsy.stub_sets.defailed.stubs';

		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'defailed'])
			 ->assertExitCode(1)
			 ->expectsOutputToContain($msg)
		;
	},
);
it(
	'Make Package with stub',
	function() {
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default'])
			 ->assertExitCode(0)
		;
	},
);
it(
	'Make Package without stub',
	function() {
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing'])
			 ->assertExitCode(0)
		;
	},
);
it(
	'Make Package with not existing stubs set "creepy"',
	function() {
		$cp = 'paxsy';
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'creepy'])
			 ->assertExitCode(1)
			 ->expectsOutput('Missing Stubs in /config/'.$cp.'.php on selected stubs: "creepy" in: '.$cp.'.stub_sets.creepy.stubs')
		;
	},
);
it(
	'write default package with default stubs',
	function() {
		$testPath = base_path().'/'.currentStackName();
		// make sure app-modules are empty
		expect($testPath)
			->not()
			->toBeReadableDirectory()
		;
		$this->artisan(
			'paxsy:package',
			['vendor' => 'test vendor', 'package' => 'the test package', 'stubs' => 'default'],
		)
			 ->assertExitCode(0)
		;

		$defaultPackage = defaultTestPackage();

		// Default Package Structure has all
		expect($testPath)
			->toBeReadableDirectory()
			->and($defaultPackage->packageBasePath().'/composer.json')
			->toBeReadableFile()
			->and($defaultPackage->packageBasePath().'/.gitignore')
			->toBeReadableFile()
			->and($defaultPackage->packageBasePath().'/src/Providers/TheTestPackageServiceProvider.php')
			->toBeReadableFile()
		;
	},
);

it(
	'package composer.json filled correctly',
	function() {
		$testPath = base_path().'/'.currentStackName();
		// make sure app-modules are empty
		expect($testPath)
			->not()
			->toBeReadableDirectory()
		;
		$this->artisan(
			'paxsy:package',
			['vendor' => 'test vendor', 'package' => 'the test package', 'stubs' => 'default'],
		)
			 ->assertExitCode(0)
		;

		$defaultPackage = defaultTestPackage();

		// after written
		$content  = file_get_contents($defaultPackage->packageBasePath().'/composer.json');
		$contains = ['"name": "test-vendor/the-test-package",'];
		expect($content)->toContain(...$contains);
	},
);
it(
	'package already exist ',
	function() {
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default'])
			 ->assertExitCode(0)
		;

		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default'])
			 ->assertExitCode(1)
			 ->expectsOutputToContain('Package:"'.'pgk-testing'.'" already exists under:')
		;// Package:"pgk-testing" already exists under:"/var/www/html/app-paxsy-testing/pgk-testing"!
	}
);
it(
	'stubs directory is missing',
	function() {
		config()->set('paxsy.stub_sets.default.directory', '');
		$cp = 'paxsy';
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default'])
			->expectsOutput('Missing Stub-Directory in /config/paxsy.php paxsy.stub_sets.default.directory')
			 ->assertExitCode(1)
		;
	}
);
