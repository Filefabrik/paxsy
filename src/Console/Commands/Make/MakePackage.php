<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Support\StackApp;
use Filefabrik\Paxsy\Support\Stubs\Facade;
use Filefabrik\Paxsy\Support\Stubs\FromConfig;
use Filefabrik\Paxsy\Support\Stubs\Helper;
use Filefabrik\Paxsy\Support\VendorPackageNames;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MakePackage extends Command
{
	/**
	 * @var string
	 */
	protected $signature = 'paxsy:package
	    {vendor? : Your vendor name}
		{package? : The name of the package}
		{stubs=default : Select a Stubs Set}';

	/**
	 * @var string
	 */
	protected $description = 'Create a new composer-package in /app-paxsy  "php artisan paxsy:package MyCompanyVendor ThPackageName"';

	/**
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function handle(): int
	{
		$packageStack = StackApp::get();

        [$vendor, $package, $selectedStubsSet] = $this->inputArguments();
        if (!$vendor || !$package || !$selectedStubsSet) {
            $this->error(sprintf('missing a part vendor:"%s" or package:"%s" or stubs:"%s"',
                                 $vendor,
                                 $package,
                                 $selectedStubsSet));

			return self::FAILURE;
		}

		// todo must have the Package context, which has the Package Stack
		$newPackage = new VendorPackageNames(
			vendor : $vendor,
			package: $package,
		);

        // ATM take the default app-packages
        $newPackage->setStackName($packageStack->getStackName());
        // check package exists
        if (is_dir($newPackage->packageBasePath())) {
            $this->error(sprintf('Package:"%s" already exists under:"%s"!',
                                 $newPackage->getPackageName(),
                                 $newPackage->packageBasePath()));

			return self::FAILURE;
		}
		// check package exists
		// at this point, all requirements
		$this->createStackNotExists();

		/**
		 * Semi-Validation
		 */
		$stubsConfig = new FromConfig($selectedStubsSet);

		/*
		 * directories and files
		 */
		$stubs = $stubsConfig->stubs();

        if (!$stubs) {
            $message = sprintf('Missing Stubs in /config/paxsy.php on selected stubs: "%s" in: %s',
                               $stubsConfig->getSelectedStubs(),
                               $stubsConfig->stubsLocator());

            Log::error($message);
            $this->error($message);

			return self::FAILURE;
		}

		$stubsDirectory = $stubsConfig->directory();

        if (!$stubsDirectory) {
            $message = sprintf('Missing Stub-Directory in /config/paxsy.php %s', $stubsConfig->directoryLocator());
            Log::error($message);
            $this->error($message);

			return self::FAILURE;
		}

		$replaceableVars = Facade::variables(
			vendorPackageNames: $newPackage,
			config            : $stubsConfig,
		)
								 ->renderVariables()
		;

		$stubsHelper = Helper::createStubs(
			packageBasePath: $newPackage->packageBasePath(),
			stubsMap       : $stubs,
			stubsDirectory : $stubsDirectory,
			variables      : $replaceableVars,
		);
		$stubsHelper->writeStubs();
		$stubsHelper->mapLinesInto($this);

		// force to reread packages
		StackApp::get()
				->reset()
		;

		return self::SUCCESS;
	}

	/**
	 * Auto-Create the Directory
	 *
	 * @return void
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function createStackNotExists(): void
	{
		$packageStack = StackApp::get();

        if (!$packageStack->exists()) {
            $this->info('Stack does not exist!');
            $packageStack->ensureStackDirectoryExists();
            $this->info(sprintf('And was created under:"%s"', $packageStack->getStackName()));
        }
    }

	protected function inputArguments(): array
	{
		return array_map(fn($str) => $this->argument($str), ['vendor', 'package', 'stubs']);
	}
}
