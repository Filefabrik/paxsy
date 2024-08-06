<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Admin\TraitInputs;
use Filefabrik\Paxsy\Support\Stack;
use Filefabrik\Paxsy\Support\VendorPackageNames;
use Illuminate\Filesystem\Filesystem;

beforeEach(function() {
	currentStackName();
	removePackageStack();
	rerouteStubsDirectory();
});
it(
	'selected package null',
	function() {
		$cls = new class() {
			use TraitInputs;

			public function selectedPackageTest(?string $vendor_package_name = null): ?VendorPackageNames
			{
				return $this->selectedPackage($vendor_package_name);
			}
		};
		expect($cls->selectedPackageTest())->toBeNull();
	},
);

it(
	'valid package',
	function() {
		$this->artisan('paxsy:package', ['vendor' => 'test vendor', 'package' => 'pgk testing', 'stubs' => 'default']);
		$cls = new class() {
			use TraitInputs;

			private Stack $stack;

			public function __construct()
			{
				$this->stack = new Stack('app-paxsy-testing', app(), new Filesystem());
			}

			public function selectedPackageTest(?string $vendor_package_name = null): ?VendorPackageNames
			{
				return $this->selectedPackage($vendor_package_name);
			}
		};

		$expectedPackage = (new VendorPackageNames('testVendor', 'pgk testing'))->setStackName('app-paxsy-testing');

		$createdPackage = $cls->selectedPackageTest('test-vendor/pgk-testing');
		expect($createdPackage->vendorPackageName())->toBe($expectedPackage->vendorPackageName());
	},
);

// todo select option
