<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Support\Package;
use Filefabrik\Paxsy\Support\Stack;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

beforeEach(function() {
	removePackageStack();

	app()->make(Stack::class, ['paxsy', app(), new Filesystem()]);
});

it(
	'simple presence ',
	function() {
		$ps = packageStack();

		expect($ps)
			->toBeInstanceOf(Stack::class)
			->and($ps->getStackName())
			->toBe(currentStackName())
		;
	},
);
it(
	'get named package',
	function() {
		$ps = packageStack();
		$ps->ensureStackDirectoryExists();

		expect($ps->package('the-test-package'))->toBeNull();
		makePackageByArtisanCommand($this);
		$pkg = $ps->package('the-test-package');

		expect($pkg)
			->toBeInstanceOf(Package::class)
			->and($pkg->getVendorPackageNames()
					  ->toComposerName())
			->toBe('my-test-vendor/the-test-package')
		;
	},
);

/**
 * @covers Stack::packages
 */
it(
	'package-create not exists(packageStackBasePath/directory was created before)',
	function() {
		$ps       = packageStack();
		$packages = $ps->packages();
		expect($packages)
			->toBeInstanceOf(Collection::class)
			->and($packages->count())
			->toBe(0)
		;
		// no change, same result, takes the last return
		$withForce = $ps->packages(true);
		expect($withForce)
			->toBeInstanceOf(Collection::class)
			->and($withForce->count())
			->toBe(0)
		;
	},
);

/**
 * @covers Stack::packages
 */
it(
	'package-create exists and empty',
	function() {
		$ps = packageStack();
		$ps->ensureStackDirectoryExists();
		$packages = $ps->packages();

		// take the exists-way. and try to load packages
		expect($packages)
			->toBeInstanceOf(Collection::class)
			->and($packages->count())
			->toBe(0)
		;
		// take the exists-way. and try to load packages
		$withForce = $ps->packages(true);
		expect($withForce)
			->toBeInstanceOf(Collection::class)
			->and($withForce->count())
			->toBe(0)
		;
	},
);

it(
	'reset',
	function() {
		expect(packageStack()->reset())->toBeInstanceOf(Stack::class);
	},
);
it(
	'reload',
	function() {
		$ps = packageStack();
		$ps->ensureStackDirectoryExists();

		expect(count($ps->packages()))->toBe(0);

		makePackageByArtisanCommand($this);

		expect(count($ps->packages()))
			->toBe(1)
		;
		$ps->reload();

		expect(count($ps->packages()))
			->toBe(1)
		;
	},
);
it(
	'get filesystem',
	function() {
		expect(packageStack()
				   ->getFilesystem())->toBeInstanceOf(Filesystem::class);
	},
);

it(
	'get package create name',
	function() {
		expect(packageStack()->getStackName())
			->toBe(currentStackName())
			->and((new Stack('packages', app(), new Filesystem()))->getStackName())
			->toBe('packages')
		;
	},
);
it(
	'get packages base path',
	function() {
		expect(packageStack()->getStackBasePath())
			->toBe(base_path().'/'.currentStackName())
			->and((new Stack('packages', app(), new Filesystem()))->getStackBasePath())
			->toBe(base_path().'/packages')
		;
	},
);
it(
	'exists and ensure package create directory exists',
	function() {
		$ps = packageStack();

		expect($ps->exists())
			->toBeFalse()
			->and($ps->ensureStackDirectoryExists())
			->toBeTrue()
			->and($ps->ensureStackDirectoryExists())
			->toBeTrue()
		;
	},
);

it(
	'check Stack class ',
	function() {
		//	app()->make(Stack::class, ['paxsy', app(), new Filesystem()]);
		$result = packageStack()->reload();
		expect($result)
			->toBeInstanceOf(Collection::class)
			->and($result->count())
			->toBe(0)
		;
	},
);
it('get vendor list', function() {
	currentStackName();
	removePackageStack();
	rerouteStubsDirectory();
	$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default']);
	$this->artisan(
		'paxsy:package',
		['vendor' => 'test vendor 2', 'package' => 'pgk testing 2', 'stubs' => 'default']
	);

	expect(packageStack()->getVendorList())->toBe(['TestVendor2' => 'TestVendor2',
		'TestVendor'                                                => 'TestVendor', ]);
});
