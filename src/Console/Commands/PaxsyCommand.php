<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands;

use Exception;
use Filefabrik\Paxsy\Console\Commands\Admin\Inputs;
use Filefabrik\Paxsy\Console\Commands\Admin\InputVendorName;
use Filefabrik\Paxsy\Console\Commands\Admin\Menus;
use Filefabrik\Paxsy\Console\Commands\Admin\TraitInputs;
use Filefabrik\Paxsy\Console\Commands\Composer\VendorPackageCommand;
use Filefabrik\Paxsy\Console\Commands\Make\MakePackage;
use Filefabrik\Paxsy\Paxsy;
use Filefabrik\Paxsy\Support\Stack;
use Filefabrik\Paxsy\Support\Stringularity;
use Filefabrik\Paxsy\Support\VendorPackageNames;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaxsyCommand extends Command
{
	use TraitInputs;

	/**
	 * @var string
	 */
	protected $signature = 'paxsy';

	public const QUIT = 'q';

	public const STOP_DEEPER = 23;

	/**
	 * @var string
	 */
	protected $description = 'administrate packages';

	/**
	 * Create a new console command instance.
	 *
	 * @return void
	 */
	public function __construct(private readonly Stack $stack)
	{
		parent::__construct();
	}

	/**
	 * @return int
	 */
	public function handle(): int
	{
		return $this->recursiveMainMenu();
	}

	/**
	 * Keeps the main Menu open
	 * @return int
	 */
	private function recursiveMainMenu(): int
	{
		$method = Inputs::suggestMainMenu(Menus::selectableMenu(Menus::$mainMenu));

		if ($method === self::QUIT) {
			return self::SUCCESS;
		}
		$executionResult = $this->{'task_'.$method}();
		if ($executionResult !== self::QUIT && $executionResult !== self::FAILURE) {
			$this->recursiveMainMenu();
		}

		return $executionResult === self::FAILURE ? self::FAILURE : self::SUCCESS;
	}

	/**
	 * Todo Move out
	 *
	 * @TODO automatic command chain for creation
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @todo with seeder and factory in composer.json
	 */
	protected function task_create_package(): int
	{
		// collect a new Module Config
		$this->line('Create a new Package');

		// after this point, everything (vendor/package) is configured for creating the package with stubs
		$vendorPackageNames = $this->inputVendorPackage();
		if (! $vendorPackageNames) {
			return self::FAILURE;
		}
		// todo make callable by a kind of singleton
		// todo describe internally

		// now we are ready with a new package
		// todo write the package structure
		// todo write in batch mode some basic stuff such as controller routes or something else

		$selectedStubsSet = Inputs::selectStubsSet();

		$hasErrors = $this->call(
			MakePackage::class,
			['vendor'  => $vendorPackageNames->getVendorName(),
				'package' => $vendorPackageNames->getPackageName(),
				'stubs'   => $selectedStubsSet],
		);
		// @codeCoverageIgnoreStart
		if ($hasErrors) {
			$this->error('Vendor-Package not created');

			return self::FAILURE;
		}
		// @codeCoverageIgnoreEnd
		$this->line('Package created under /'.$vendorPackageNames->getStackName().'/'.$vendorPackageNames->getPackageName());

		$vendor_namespace_input = $vendorPackageNames->toComposerName();

		// put the repository vendor-package into the laravel host composer.json and run composer installation
		$this->task_composer_add_repository_vendor_package($vendorPackageNames->vendorPackageName());

		// open command tools to create vendor package components
		return	$this->in_package_tasks($vendor_namespace_input);
	}

	/**
	 * @return int|string
	 */
	protected function task_list_packages(): int|string
	{
		return $this->call('paxsy:list');
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function task_handle_package(): int|string
	{
		$this->line('Handle a Package');
		$vendor_package_name = Inputs::suggestExistingPackages();

		if ($vendor_package_name === PaxsyCommand::QUIT) {
			return PaxsyCommand::QUIT;
		}
		// Is empty. No packages were found

		if (! $vendor_package_name) {
			$this->error('There are no packages inside '.$this->stack->getStackName());
			return self::FAILURE;
		}
		$this->line('Jumped into Package: '.$vendor_package_name, 'info');
		return	$this->in_package_tasks($vendor_package_name);
	}

	/**
	 * The Package is selected, make commands
	 *
	 * @param $vendor_package_name
	 *
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function in_package_tasks($vendor_package_name): int
	{
		$res = $this->nest_in_package($vendor_package_name);

		$res !== self::SUCCESS ?: $this->askPackageAgainTask($vendor_package_name);

		return $res === self::QUIT ? self::SUCCESS : $res;
	}

	/**
	 * Try Composer update from console
	 *
	 * @return int
	 */
	protected function task_composer_update(): int
	{
		return $this->call('paxsy:composer-update');
	}

	/**
	 * @param string|null $vendor_package_name
	 *
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function task_composer_add_repository_vendor_package(?string $vendor_package_name = null): int
	{
		$vendorPackageNames = $this->selectedPackage($vendor_package_name);

		$this->task_composer_add_repository($vendorPackageNames->vendorPackageName());
		$this->task_composer_add_vendor_package($vendorPackageNames->vendorPackageName());

		return self::SUCCESS;
	}

	/**
	 * @param string|null $vendor_package_name
	 *
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws Exception
	 */
	protected function task_composer_remove_repository_vendor_package(?string $vendor_package_name = null): int
	{
		$vendorPackageNames = $this->selectedPackage($vendor_package_name);

		$this->task_composer_remove_repository($vendorPackageNames->vendorPackageName());
		$this->task_composer_remove_vendor_package($vendorPackageNames->vendorPackageName());
		return self::SUCCESS;
	}

	/**
	 * @throws Exception
	 */
	protected function task_composer_add_repository(?string $vendor_package_name = null): int
	{
		return $this->call(
			'paxsy:repository',
			[VendorPackageCommand::VendorPackageIdent => $vendor_package_name],
		);
	}

	/**
	 * @throws Exception
	 * @todo update composer after this command
	 */
	protected function task_composer_remove_repository(?string $vendor_package_name = null): int
	{
		return   $this->call(
			'paxsy:repository',
			[VendorPackageCommand::VendorPackageIdent => $vendor_package_name, '--remove' => true],
		);
	}

	/**
	 * @param string|null $vendor_package_name
	 *
	 * @return int
	 */
	protected function task_composer_add_vendor_package(?string $vendor_package_name = null): int
	{
		return $this->call(
			'paxsy:vendor-package',
			[VendorPackageCommand::VendorPackageIdent => $vendor_package_name],
		);
	}

	/**
	 * @param string|null $vendor_package_name
	 *
	 * @return int
	 */
	protected function task_composer_remove_vendor_package(?string $vendor_package_name = null): int
	{
		return	$this->call(
			'paxsy:vendor-package',
			[VendorPackageCommand::VendorPackageIdent => $vendor_package_name, '--remove' => true],
		);
	}

	/**
	 * @param string $vendor_package_name
	 *
	 * @return int|string
	 */
	protected function nest_in_package(string $vendor_package_name): int|string
	{
		$package_name = VendorPackageNames::fromVendorPackage($vendor_package_name)
										  ->getPackage()
										  ->toName()
		;
		$label = sprintf('Choose the "make" command for your "%s" package?', $vendor_package_name);

		$command = Inputs::suggestMakeCommands($label);

		if ($command === self::QUIT) {
			return self::QUIT;
		}

		$this->line('in background the following command will be executed');
		$this->line('php artisan '.$command.' --package='.$package_name);

		// todo optional ask that all inputs correctly

		return $this->call($command, ['--package' => $package_name]);
	}

	/**
	 * Do another task at the same package?
	 *
	 * @param $selected
	 *
	 * @return void
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function askPackageAgainTask($selected): void
	{
		$this->in_package_tasks($selected);
	}

	/**
	 * @return VendorPackageNames|null
	 */
	private function inputVendorPackage(): ?VendorPackageNames
	{
		// todo allow q
		$vendor = InputVendorName::handle($this, $this->stack);

		if (! $vendor) {
			return null;
		}
		$this->line('2. enter the package-name');
		$packageName = Inputs::packageName($vendor->toClass());
		if (! $packageName) {
			return null;
		}

		// your company in composer.json "name": "$vendor/$package",

		// todo check the package-name already exists

		$vendorPackageNames = new VendorPackageNames(
			vendor : $vendor,
			package:new Stringularity($packageName),
		);

		// relative segment from laravel host application Most important stack_name setting
		return $vendorPackageNames->setStackName(Paxsy::currentStackName());
	}
}
