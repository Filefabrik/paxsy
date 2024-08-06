<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Composer\Autoload\ClassLoader;
use Filefabrik\Paxsy\Console\Commands\Make\MakePackage;
use Filefabrik\Paxsy\Support\Composer\WithDisabled;
use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Filefabrik\Paxsy\Support\Stack;
use Filefabrik\Paxsy\Support\VendorPackageNames;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

function currentStackName()
{
	config()->set('paxsy.stack_name', 'app-paxsy-testing');

	return config('paxsy.stack_name');
}

function useShellDisabled(): void
{
	config()->set('paxsy.composer_execution', 'disabled');
	app()->singleton(WithInterface::class, fn() => new WithDisabled());
}

/**
 * Removes the whole stack-directory where the packages were created in
 *
 * @param string|null $name
 *
 * @return void
 */
function removePackageStack(?string $name = null): void
{
	$dir = base_path($name ?? currentStackName());
	if (is_dir($dir)) {
		(new Filesystem())->deleteDirectory($dir);
	}
}

/**
 * Make Object which is workable
 *
 * @return VendorPackageNames
 */
function defaultTestPackage(): VendorPackageNames
{
	$vendorPackageName = 'my-test-vendor/the-test-package';

	return VendorPackageNames::fromVendorPackage($vendorPackageName)
							 ->setStackName(currentStackName())
	;
}

function ensureDirectoryExists(string $dir): void
{
	(new Filesystem())->ensureDirectoryExists($dir);
}

function clearLaravelFiles()
{
	$files = ['tests/Unit/PHPUnit_Unit_Laravel.php',
		'tests/Feature/MyPESTFeatureTestingIntoLaravel.php',
		'database/factories/MyTestFactoryInLaravelFactory.php',
		'database/seeders/TestingSeederPure.php',
		'app/Models/TestModelWithoutPackage.php',
		'app/Models/TestingControllerModels.php', 'app/Http/Controllers/TestController2.php'];
	foreach ($files as $file) {
		$testFile = base_path($file);
		if (is_file($testFile)) {
			unlink($testFile);
		}
	}
}

/**
 * Step 1: Create the VendorPackage
 * Creates only a VendorPackage by paxsy:package
 *
 * @param $testCase
 */
function makePackageByArtisanCommand($testCase): void
{
	rerouteStubsDirectory();

	$defaultPackage = defaultTestPackage();
	$vendorName     = $defaultPackage->getVendorName();
	$packageName    = $defaultPackage->getPackageName();
	$params         = [
		'vendor'  => $vendorName,
		'package' => $packageName,
		'stubs'   => 'default',

	];
	$testCase->artisan(MakePackage::class, $params)
			 ->assertExitCode(0)
	;

	//$testCase->artisan('paxsy', $defaultPackage);
	// during composer-package creation without dump-autoload or update, the namespace for the package has to be load
	autoloadNamespace(app(), $defaultPackage);
}

/**
 * Step 2: Create a Component in a VendorPackage
 *
 * @param                              $testCase
 * @param                              $command
 * @param                              $createArguments
 */
function makeComponentInPackage($testCase, $command, $createArguments): void
{
	$packageName = defaultTestPackage()
		->getPackageName()
	;
	$testCase->artisan(
		$command,
		array_merge(
			[
				'--package' => $packageName,
			],
			$createArguments,
		),
	)
			 ->assertExitCode(0)
	;
}

/**
 * Step 3: Check files and directories exists (todo check, that we have all files and directories checked that created)
 *
 * @param          $full_path
 *
 * @return void
 */
function checkComponentFilesAndDirectories($full_path): void
{
	$directory = dirname($full_path);
	$files     = implode(', ', glob($directory.'/*') ?? []);

	$directory     = dirname($directory);
	$sibling_paths = implode(', ', glob($directory.'/*') ?? []);

	expect($full_path)->toBeReadableFile("Could not find file. Files in directory: '{$files}'. Siblings to parent directory: '{$sibling_paths}'");
}

function forcePaxsyConfig($withTestingStackName = true): void
{
	config()->set('paxsy', require dirname(__DIR__).'/config/paxsy.php');
	! $withTestingStackName ?: config()->set('paxsy.stack_name', 'app-paxsy-testing');
}

/**
 * Set usage with interactions
 *
 * @return void
 */
function withGuiInteractions()
{
	config()->set('paxsy.gui_interactions', true);
}

/**
 * Modify Stubs Directory in Testing Context
 *
 * @return void
 */
function rerouteStubsDirectory(): void
{
	$testingStubsDir = dirname(__DIR__).'/stubs';
	forcePaxsyConfig();

	if (is_dir($testingStubsDir)) {
		config()
			->set('paxsy.stub_sets.default.directory', $testingStubsDir)
		;
	}
}

/**
 * Composer dump-autoload cannot be testet by Console/Automatic-Tests.
 * So force adds autoload namespaces for the vendor package
 *
 *
 * @param Application             $application
 * @param VendorPackageNames|null $vendorPackageNames
 *
 * @return void
 */
function autoloadNamespace(Application $application, ?VendorPackageNames $vendorPackageNames = null): void
{
	/** @var ClassLoader $autoloader */
	$autoloader = require $application->basePath('vendor/autoload.php');
	$vendorPackageNames ??= defaultTestPackage();

	$reflectAutoload = new ReflectionClass($autoloader);
	$reflectAutoload->getProperty('missingClasses')
					->setValue($autoloader, [])
	;

	$coI                   = $autoloader;
	$autoloadNamespace     = $vendorPackageNames->toNamespace().'\\';
	$vendorPackageBasePath = $vendorPackageNames->packageBasePath().'/src';
	$coI->setClassMapAuthoritative(false);

	$coI->addPsr4($autoloadNamespace, $vendorPackageBasePath);
}

/**
 * Current app package stack
 *
 * @return Stack
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function packageStack(): Stack
{
	return app()->get(Stack::class);
}
