<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Make\MakePackage;
use Filefabrik\Paxsy\Support\VendorPackageNames;

beforeEach(function() {
	removePackageStack();
});
it(
	'new package ',
	function() {
		$defaultPackage = defaultTestPackage();

		makePackageByArtisanCommand($this);

		$vendorPackageName = 'z-vendor/z-package';

		$zPackage = VendorPackageNames::fromVendorPackage($vendorPackageName)
									   ->setStackName(currentStackName())
		;

		$vendorName  = $zPackage->getVendorName();
		$packageName = $zPackage->getPackageName();

		$this->artisan(MakePackage::class, [
			'vendor'  => $vendorName,
			'package' => $packageName,
			'stubs'   => 'default',

		])
			 ->assertExitCode(0)
		;
		$this->artisan(
			'make:livewire',
			['name'      => 'my-lv-CompoZpackage',
				'--package' => $zPackage->getPackageName(),
			],
		);
		$this->artisan(
			'make:livewire',
			['name'      => 'my-lv-Compo',
				'--package' => $defaultPackage->getPackageName(),
			],
		);
	}
);
