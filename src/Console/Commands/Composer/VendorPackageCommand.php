<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Composer;

use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * SubCommand
 */
class VendorPackageCommand extends Command
{
	use TraitFlags;

	public const VendorPackageIdent = 'vendor/package';

	/**
	 * @var string
	 */
	protected $signature = 'paxsy:vendor-package {'.self::VendorPackageIdent.' : my-company/my-package-name}
    {--remove=false : remove vendor/package from laravel host composer.json}';

	/**
	 * @var string
	 */
	protected $description = 'adds a vendor/package (my-company/my-package-name) to Laravel Host composer.json';

	/**
	 * @see https://getcomposer.org/doc/03-cli.md#remove-rm
	 * @see https://getcomposer.org/doc/03-cli.md#require-r
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function handle(): int
	{
		$vendor_package_name = $this->argument(self::VendorPackageIdent);
		/** @var WithInterface $composerInterface */
		$composerInterface = app()->get(WithInterface::class);

		if (true === $this->option('remove')) {
			$composerInterface->removeVendorPackage($vendor_package_name, $this->getFlags());
		} else {
			$composerInterface->addVendorPackage($vendor_package_name, $this->getFlags());
		}
		$composerInterface->lastTransactionToConsole($this);

		return self::SUCCESS;
	}
}
