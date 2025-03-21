<?php declare(strict_types=1);

use Filefabrik\Paxsy\Console\Commands\Admin\Output;

beforeEach(function () {
	currentStackName();
	removePackageStack();
	rerouteStubsDirectory();
});
it(
	'output helper configured Commands',
	function () {
		// translated commands
		$components = ['make:route', 'make:livewire'];

		expect(Output::configuredCommands())->toBe($components);
	},
);

it(
	'get packages',
	function () {
		expect(Output::getPackages())->toBe([]);

		// create to packages

		$this->artisan(
			'paxsy:package',
			[
				'vendor'  => 'test vendor',
				'package' => 'pgk testing',
				'stubs'   => 'default',
			],
		);
		$this->artisan(
			'paxsy:package',
			[
				'vendor'  => 'test vendor 2',
				'package' => 'pgk testing 2',
				'stubs'   => 'default',
			],
		);

		expect(Output::getPackages())->toMatchArray([
			'pgk-testing'  => [
				'name' => 'pgk-testing',
				'path' => 'test-vendor/pgk-testing',
			],
			'pgk-testing2' => [
				'name' => 'pgk-testing2',
				'path' => 'test-vendor2/pgk-testing2',
			],

		]);
	},
);

it(
	'get package list',
	function () {
		expect(Output::getPackageList())->toBe([]);

		// create to packages

		$this->artisan('paxsy:package', [
			'vendor'  => 'test vendor',
			'package' => 'pgk testing',
			'stubs'   => 'default',
		]);
		$this->artisan(
			'paxsy:package',
			[
				'vendor'  => 'test vendor 2',
				'package' => 'pgk testing 2',
				'stubs'   => 'default',
			],
		);

		expect(Output::getPackageList())->toMatchArray([
			'test-vendor2/pgk-testing2' => 'test-vendor2/pgk-testing2',
			'test-vendor/pgk-testing'   => 'test-vendor/pgk-testing',
		]);
	},
);

it(
	'available make Commands',
	function () {
		expect(Output::availableMakeCommands())->toBe([
			'make:cast',
			'make:controller',
			'make:command',
			'make:channel',
			'make:event',
			'make:exception',
			'make:factory',
			'make:job',
			'make:listener',
			'make:mail',
			'make:middleware',
			'make:model',
			'make:notification',
			'make:observer',
			'make:policy',
			'make:provider',
			'make:request',
			'make:resource',
			'make:rule',
			'make:seeder',
			'make:test',
			'make:component',
			'make:view',
			'make:migration',
			// components
			'make:route',
			'make:livewire',
		]);
	},
);
