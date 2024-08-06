<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Filefabrik\Paxsy\Support\VendorPackageNames;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait TraitInputs
{
	/**
	 * @param string|null $vendor_package_name
	 *
	 * @return VendorPackageNames|null
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function selectedPackage(?string $vendor_package_name = null): ?VendorPackageNames
	{
		$vendor_package_name = $this->selectPackageIfNeed($vendor_package_name);
		if (! $vendor_package_name) {
			return null;
		}
		return VendorPackageNames::fromVendorPackage($vendor_package_name)->setStackName($this->stack->getStackName());
	}

	/**
	 * Select existing VendorPackage if need
	 *
	 * @param string|null $vendor_package_name
	 *
	 * @return string
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	protected function selectPackageIfNeed(?string $vendor_package_name = null): string
	{
		return $vendor_package_name ?? Inputs::suggestExistingPackages();
	}
}
