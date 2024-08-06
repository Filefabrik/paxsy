<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Support;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Bootraiser\Support\Str\Pathering;
use Illuminate\Support\Str;

/**
 * Package prepares all base package paths from a reading and writing process
 */
class Package
{
	/**
	 * MyVendor\\ThePackageName
	 * by readin a package composer.json or directly set
	 *
	 * @var string|null
	 */
	private ?string $tmp_vendorPackageNamespace = null;

	/**
	 * todo make private, prevent from manipulating this high risk object
	 *
	 * @param string             $name // organized name in the Stack
	 * @param VendorPackageNames $vendorPackageNames
	 */
	public function __construct(
		private readonly string $name,
		private readonly VendorPackageNames $vendorPackageNames
	) {
	}

	/**
	 * Relative name in the Stack. Almost the package name 'the-package'
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * my-vendor/the-package-name
	 * shorter internal getter :)
	 *
	 * @return string
	 */
	public function getComposerName(): string
	{
		return $this->getVendorPackageNames()
					->toComposerName()
		;
	}

	/**
	 * @return VendorPackageNames
	 */
	public function getVendorPackageNames(): VendorPackageNames
	{
		return $this->vendorPackageNames;
	}

	/**
	 * Make Relative look from the vendor/package root
	 *
	 * @param string $to
	 * todo allow spreed in $to
	 *
	 * @return string
	 */
	public function intoRelativePackagePath(string $to = ''): string
	{
		return Pathering::concat(
			$this->getVendorPackageNames()
				 ->relPackageDir(),
			$to,
		);
	}

	/**
	 * The Full package path is inside the Stack and the Package-Name
	 * todo allow spreed in $to
	 * @param string $to
	 *
	 * @return string
	 */
	public function intoPackagePath(string $to = ''): string
	{
		return Pathering::concat($this->packageBasePath(), $to);
	}

	/**
	 * Absolute Path on Server
	 *
	 * @return string i.e /var/www/html/app-modules/my-package
	 */
	public function packageBasePath(): string
	{
		return $this->getVendorPackageNames()
					->packageBasePath()
		;
	}

	/**
	 * Where the Vendor Package Namespace started
	 *
	 * @return string|null namespaces are trimmed
	 */
	public function srcPackageNamespace(): ?string
	{
		// package search should be read in initially
		return $this->tmp_vendorPackageNamespace ??= Namespacering::trim($this->vendorPackageNames->toNamespace());
	}

	/**
	 * name:    MyCompanyVendor\TheTestPackage\Policies\TestPolicy
	 * return:  Policies\TestPolicy
	 *
	 * @param string $subject
	 *
	 * @return string
	 */
	public function subtractsPackageNamespace(string $subject): string
	{
		return $this->subtractsNamespace($this->srcPackageNamespace(), $subject);
	}

	/**
	 * Remove a part from search
	 *
	 * @param $search
	 * @param $subject
	 *
	 * @return string
	 */
	public function subtractsNamespace($search, $subject): string
	{
		$subject = Str::replaceFirst($search, '', $subject);

		return Namespacering::trim($subject);
	}

	/**
	 * Todo can be customized from ready the package composer.json ...think not need
	 *
	 * @return string /my-package/src
	 */
	public function getSrcDirName(): string
	{
		// mainly for all Packages, the typical src directory segment in /my-package/src
		return 'src';
	}

	/**
	 * Full Class Namespace to a Component inside the VendorPackage
	 * MyVendor\\ThePackageName\\LaravelComponent
	 *
	 * @param string $namespace
	 *
	 * @return string
	 */
	public function joinPackageNamespace(...$namespace): string
	{
		return Namespacering::concat($this->srcPackageNamespace(), ...$namespace);
	}

	/**
	 * Check the ServiceProvider Class for the Package is loadable via autoloading.
	 * That indicates that the Package is correctly installed
	 *
	 * @note    This Namespace is calculated and not synchron with the vendor-package composer.json
	 * @return string  "MyTestVendor\\DemoPackage\\Providers\\DemoPackageServiceProvider"
	 * @example class_exists($package->serviceProviderNamespace()),
	 */
	public function serviceProviderNamespace(): string
	{
		return $this->joinPackageNamespace(
			'Providers',
			$this->getVendorPackageNames()
				 ->getPackage()
				 ->toClass().'ServiceProvider',
		);
	}
}
