<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Paxsy\Support\Str\CaseConverters;
use UnexpectedValueException;

/**
 * All creation configs for a new Module to transport it into stubs ...or somewhere
 * understand it as a kind of facade
 *
 * @note All generated Values are theoretical Values
 * todo make sure vendor name an package name are present
 * @phpstan-import-type StringularityArray from Stringularity
 */
class VendorPackageNames
{
	/**
	 * From config('stack_name') /app-paxsy/
	 *
	 * @var string|null
	 */
	private ?string $stack_name = null;

	/**
	 * @var Stringularity
	 */
	private Stringularity $vendor;

	/**
	 * @var Stringularity
	 */
	private Stringularity $package;

	/**
	 * During write/create a vendor/package it can be a string.
	 * During readin of a composer.json
	 * or create a vendor/package with divergent my-vendor-name and MyCompanyVendorNamespace or/and the-package-name vs. AnOtherPackageNamespace
	 * please use the Stringularity as parameter
	 *
	 * @param string|Stringularity $vendor
	 * @param string|Stringularity $package
	 */
	public function __construct(
		string|Stringularity $vendor,
		string|Stringularity $package,
	) {
		$this->vendor  = is_string($vendor) ? new Stringularity(name: $vendor) : $vendor;
		$this->package = is_string($package) ? new Stringularity(name: $package) : $package;
	}

	/**
	 * Instance from vendor/package name
	 *
	 * @param string $vendor_package_name
	 *
	 * @return self
	 */
	public static function fromVendorPackage(string $vendor_package_name): VendorPackageNames
	{
		return new self(...CaseConverters::fromVendorPackageName($vendor_package_name));
	}

	/**
	 * Path-Segment From config('stack_name','app-modules')
	 * may be deprecated normally the Package should have the locations
	 *
	 * @param string $packageStackName Relative to Laravel Host
	 *
	 * @return static
	 */
	public function setStackName(string $packageStackName): static
	{
		// todo validate name
		$this->stack_name = Pathering::trim($packageStackName);

		return $this;
	}

	/**
	 * Path-Segment From config('stack_name','app-modules')
	 *
	 * @return string
	 */
	public function getStackName(): string
	{
		return $this->stack_name ?? throw new UnexpectedValueException('Missing stack_name');
	}

	/**
	 * Full Path to name package_name
	 *
	 * @return string
	 */
	public function packageBasePath(): string
	{
		return base_path($this->relPackageDir());
	}

	/**
	 * Relative
	 * /app-modules/{package_name}
	 * todo ...move this method to create because VendorPackages located relative in a Stack
	 *
	 * @return string
	 */
	public function relPackageDir(): string
	{
		return Pathering::concat($this->getStackName(), $this->package->toName());
	}

	/**
	 * proxy/alias
	 *
	 * @return string
	 */
	public function vendorPackageName(): string
	{
		return $this->toComposerName();
	}

	/**
	 * compile the typical composer.json {"name":"your-company/your-package"}
	 *
	 * @return string
	 */
	public function toComposerName(): string
	{
		return CaseConverters::composerName($this->vendor->toName(), $this->package->toName());
	}

	/**
	 * MyVendor\\MyPackage
	 *
	 * @return string
	 */
	public function toNamespace(): string
	{
		return Namespacering::concat($this->vendor->toClass(), $this->package->toClass());
	}

	/**
	 * @return string my-company
	 */
	public function getVendorName(): string
	{
		return $this->getVendor()
					->toName()
		;
	}

	/**
	 * The Vendor Names
	 *
	 * @return Stringularity
	 */
	public function getVendor(): Stringularity
	{
		return $this->vendor;
	}

	/**
	 * @return string the-package
	 */
	public function getPackageName(): string
	{
		return $this->getPackage()
					->toName()
		;
	}

	/**
	 * The Software-Package Names
	 *
	 * @return Stringularity
	 */
	public function getPackage(): Stringularity
	{
		return $this->package;
	}
}
