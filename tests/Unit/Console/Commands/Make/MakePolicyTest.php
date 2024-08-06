<?php declare(strict_types=1);

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

beforeEach(function() {
	removePackageStack();
});

it(
	'create Policy Component via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command                = 'make:policy';
		$arguments              = ['name' => 'TestPolicy'];
		$expected_relative_path = 'src/Policies/TestPolicy.php';
		$expected_substrings    = [
			'namespace '.DefaultPackageNames::namespacyfy('Policies'),
			'class TestPolicy',

		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
