<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;
use Illuminate\Database\Migrations\MigrationCreator;

beforeEach(function() {
	removePackageStack();
});

it(
	'create Migration Component via command in Default-Vendor-Package',
	function() {
		// not ensured that this works but should because run at the same second;
		$date = date('Y_m_d_His');

		$_ENV['TESTDATESETTTR'] = $date;

		$this->app->singleton(
			'migration.creator',
			function($app) {
				return new class($app['files'], $app->basePath('stubs')) extends MigrationCreator {
					protected function getDatePrefix()
					{
						return $_ENV['TESTDATESETTTR'];
					}
				};
			},
		);

		makePackageByArtisanCommand($this);

		$command                = 'make:migration';
		$arguments              = ['name' => 'test_migration'];
		$expected_relative_path = 'database/migrations/'.$date.'_test_migration.php';
		$expected_substrings    = [
			'Illuminate\Database\Migrations\Migration',
			'extends Migration',
			'function up',
		];

		makeComponentInPackage($this, $command, $arguments);
		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);

		expect(file_get_contents($expected_full_path))->toContain(...$expected_substrings);
	},
);
