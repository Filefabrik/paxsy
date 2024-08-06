<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

beforeEach(function() {
	removePackageStack();
});

// todo there is a test-bug
it(
	'Call original make:route',
	function() {
		makePackageByArtisanCommand($this);
		$this->artisan('make:route', ['--help' => true])
			 ->assertExitCode(0)
		;
	},
);
it(
	'No package',
	function() {
		$command = 'make:route';

		$this->artisan(
			$command,
			array_merge(
				[

				],
			),
		)
			 ->assertExitCode(1)
			 ->expectsOutput('No packages found!')
		;
	}
);
it(
	'Select existing Package by suggestion',
	function() {
		$command = 'make:route';
		makePackageByArtisanCommand($this);
		$this->artisan(
			$command,
		)
			 ->assertExitCode(0)
			 ->expectsQuestion('Select Package where to apply the make:route', defaultTestPackage()->getPackageName())
		;
		$expected_relative_path = 'routes/web.php';

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
	}
);

it(
	'Make route via command in Default-Vendor-Package',
	function() {
		makePackageByArtisanCommand($this);

		$command = 'make:route';

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => $packageName,
				],
			),
		)
			 ->assertExitCode(0)
		;
		$expected_relative_path = 'routes/web.php';

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		checkComponentFilesAndDirectories($expected_full_path);
	},
);

it(
	'Make route twice',
	function() {
		makePackageByArtisanCommand($this);

		$command = 'make:route';

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => $packageName,
				],
			),
		)
			 ->assertExitCode(0)
		;
		$expected_relative_path = 'routes/web.php';

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);

		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => $packageName,
				],
			),
		)
			 ->assertExitCode(1)
			 ->expectsOutput('Route "'.$expected_full_path.'" already exists!')
			 ->expectsOutput('Set the -f flag to force create the route file again.')
		;
	},
);

it(
	'Make route twice with flag',
	function() {
		makePackageByArtisanCommand($this);
		$expected_relative_path = 'routes/web.php';

		$expected_full_path = DefaultPackageNames::VendorPackageComponentPath($expected_relative_path);
		$command            = 'make:route';

		$packageName = defaultTestPackage()
			->getPackageName()
		;
		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => $packageName,
				],
			),
		)
			 ->assertExitCode(0)
		;
		checkComponentFilesAndDirectories($expected_full_path);

		$this->artisan(
			$command,
			array_merge(
				[
					'--package' => $packageName,
					'-f'        => true,
				],
			),
		)
			 ->assertExitCode(0)
		;
		checkComponentFilesAndDirectories($expected_full_path);
	},
);
