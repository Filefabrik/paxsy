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

    protected array $stageVars = ['vendor'           => null,
                                  'package'          => null,
                                  'selectedStubsSet' => null,
                                  'stubs'            => null,
                                  'stubsConfig'      => null,
                                  'stubsDirectory'   => null,
                                  'replaceableVars'  => null];

    /**
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(): int
    {
        foreach (['stageArguments',
                  'stageVendorPackage',
                  'stageStubsConfig',
                  'stageStubs',
                  'stageStubsDirectory',
                  'stageReplaceableVars'] as $method) {
            if (!$this->{$method}()) {
                return self::FAILURE;
            }
        }

        /*
         * directories and files
         */

        $stubsHelper = Helper::createStubs(
            packageBasePath: $this->stageVars['newPackage']->packageBasePath(),
            stubsMap       : $this->stageVars['stubs'],
            stubsDirectory : $this->stageVars['stubsDirectory'],
            variables      : $this->stageVars['replaceableVars'],
        );
        $stubsHelper->writeStubs();
        $stubsHelper->mapLinesInto($this);

        // force to reread packages
        StackApp::get()
                ->reset()
        ;

        return self::SUCCESS;
    }

    protected function stageArguments()
    {
        [$vendor, $package, $selectedStubsSet] = $this->inputArguments();
        if (!$vendor || !$package || !$selectedStubsSet) {
            $this->error(sprintf(
                             'missing a part vendor:"%s" or package:"%s" or stubs:"%s"',
                             $vendor,
                             $package,
                             $selectedStubsSet,
                         ));

            return null;
        }

        $this->stageVars['vendor']           = $vendor;
        $this->stageVars['package']          = $package;
        $this->stageVars['selectedStubsSet'] = $selectedStubsSet;

        return true;
    }

    protected function stageVendorPackage()
    {
        // todo must have the Package context, which has the Package Stack
        $newPackage = new VendorPackageNames(
            vendor : $this->stageVars['vendor'],
            package: $this->stageVars['package'],
        );

        // ATM take the default app-packages
        $newPackage->setStackName(StackApp::get()
                                          ->getStackName());
        // check package exists
        if (is_dir($newPackage->packageBasePath())) {
            $this->error(sprintf(
                             'Package:"%s" already exists under:"%s"!',
                             $newPackage->getPackageName(),
                             $newPackage->packageBasePath(),
                         ));

            return false;
        }
        // check package exists
        // at this point, all requirements
        $this->createStackNotExists();

        $this->stageVars['newPackage'] = $newPackage;

        return true;
    }

    protected function stageStubsConfig()
    {
        /**
         * Semi-Validation
         */
        return $this->stageVars['stubsConfig'] = new FromConfig($this->stageVars['selectedStubsSet']);
    }

    protected function stageStubs()
    {
        $stubs = $this->stageVars['stubsConfig']->stubs();

        if (!$stubs) {
            $message = sprintf(
                'Missing Stubs in /config/paxsy.php on selected stubs: "%s" in: %s',
                $this->stageVars['stubsConfig']->getSelectedStubs(),
                $this->stageVars['stubsConfig']->stubsLocator(),
            );

            Log::error($message);
            $this->error($message);

            return null;
        }
        $this->stageVars['stubs'] = $stubs;

        return true;
    }

    protected function stageStubsDirectory()
    {
        $stubsDirectory = $this->stageVars['stubsConfig']->directory();

        if (!$stubsDirectory) {
            $message = sprintf('Missing Stub-Directory in /config/paxsy.php %s',
                               $this->stageVars['stubsConfig']->directoryLocator());
            Log::error($message);
            $this->error($message);

            return null;
        }

        $this->stageVars['stubsDirectory'] = $stubsDirectory;

        return true;
    }

    protected function stageReplaceableVars()
    {
        return $this->stageVars['replaceableVars'] = Facade::variables(
            vendorPackageNames: $this->stageVars['newPackage'],
            config            : $this->stageVars ['stubsConfig'],
        )
                                                           ->renderVariables()
        ;
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
