<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Composer;

use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Paxsy\Components\ComposerRepository\Repository;
use Filefabrik\Paxsy\Console\Commands\Admin\TraitArgumentOption;
use Filefabrik\Paxsy\Paxsy;
use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Filefabrik\Paxsy\Support\VendorPackageNames;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * SubCommand
 *
 * @see https://getcomposer.org/doc/03-cli.md#modifying-repositories
 */
class RepositoryCommand extends Command
{
	use TraitArgumentOption;
	use TraitFlags;

	/**
	 * @var string
	 */
	protected $signature = 'paxsy:repository';

	/**
	 * @var string
	 */
	protected $description = 'adds the repository to Laravel Host composer.json which discovering your vendor/package/composer.json';

	/**
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function handle(): int
	{
		$names = VendorPackageNames::fromVendorPackage($this->argument(VendorPackageCommand::VendorPackageIdent));

		$url = Pathering::concat(Paxsy::currentStackName(), $names->getPackageName());

		// vendor-package name
		$repositoryKey = $names->vendorPackageName();

		/** @var WithInterface $composerInterface */
		$composerInterface = app()->get(WithInterface::class);
		$composerInterface->singleMode();
		$flags = $this->getFlags();

		if (true === $this->option('remove')) {
			$composerInterface->removeRepository($repositoryKey, $flags);
		} else {
			$composerInterface->addRepository($repositoryKey, Repository::body($url), $flags);
		}
		$composerInterface->lastTransactionToConsole($this);

		// default command for all app-modules repositories

		return self::SUCCESS;
	}

	protected function configure(): void
	{
		$definitions = [...$this->configureArguments(), ...$this->configureOptions()];
		$this->setDefinition($definitions);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments(): array
	{
		return
			[[VendorPackageCommand::VendorPackageIdent,
				InputArgument::REQUIRED,
				'The "vendor/package" under /'.Paxsy::currentStackName().'/{package}'],
			];
	}

	protected function getOptions(): array
	{
		return [
			['remove', null, InputOption::VALUE_OPTIONAL, 'Remove the Repository for the package'],
			['flags', null, InputOption::VALUE_OPTIONAL, 'Additional flags'],
		];
	}
}
