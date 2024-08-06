<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Admin\Output;

beforeEach(function() {
	currentStackName();
	removePackageStack();
	rerouteStubsDirectory();
});
it(
	'output helper configured Commands',
	function() {
		// translated commands
		$components = ['make:route', 'make:livewire'];

		expect(Output::configuredCommands())->toBe($components);
	},
);

it(
	'get packages',
	function() {
		expect(Output::getPackages())->toBe([]);

		// create to packages

		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default']);
		$this->artisan(
			'paxsy:package',
			['vendor' => 'test vendor 2', 'package' => 'pgk testing 2', 'stubs' => 'default']
		);

		expect(Output::getPackages())->toBe([
			'pgk-testing2' => ['name' => 'pgk-testing2',
				'path'                   => 'test-vendor2/pgk-testing2'],
			'pgk-testing' => ['name' => 'pgk-testing',
				'path'                  => 'test-vendor/pgk-testing'],
		]);
	}
);

it(
	'get package list',
	function() {
		expect(Output::getPackageList())->toBe([]);

		// create to packages

		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default']);
		$this->artisan(
			'paxsy:package',
			['vendor' => 'test vendor 2', 'package' => 'pgk testing 2', 'stubs' => 'default']
		);

		expect(Output::getPackageList())->toBe([
			'test-vendor2/pgk-testing2' => 'test-vendor2/pgk-testing2',
			'test-vendor/pgk-testing'   => 'test-vendor/pgk-testing',
		]);
	}
);

it(
	'available make Commands',
	function() {
		expect(Output::availableMakeCommands())->toBe([
			0  => 'make:cast',
			1  => 'make:controller',
			2  => 'make:command',
			3  => 'make:channel',
			4  => 'make:event',
			5  => 'make:exception',
			6  => 'make:factory',
			7  => 'make:job',
			8  => 'make:listener',
			9  => 'make:mail',
			10 => 'make:middleware',
			11 => 'make:model',
			12 => 'make:notification',
			13 => 'make:observer',
			14 => 'make:policy',
			15 => 'make:provider',
			16 => 'make:request',
			17 => 'make:resource',
			18 => 'make:rule',
			19 => 'make:seeder',
			20 => 'make:test',
			21 => 'make:component',
			22 => 'make:migration',
			// components
			23 => 'make:route',
			24 => 'make:livewire',
		]);
	}
);
