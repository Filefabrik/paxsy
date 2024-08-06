<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Support;

use Filefabrik\Paxsy\Support\Finders\StackComposers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

/**
 * Stacks all packages inside an app-packages directory or app-vendor_name packages directory
 * understand packages (they have composer.json inside as a child package without technical binding. only organizing)
 */
class Stack
{
	/**
	 * @var Collection<string,Package>|null
	 */
	protected ?Collection $packages = null;

	/**
	 * Todo Replace with dir-segment and base_path() laravel
	 *
	 * @param string $packageStackName relative segment i.e app-packages app-my-custom-vendor-packages
	 */
	public function __construct(
		protected string $packageStackName,
		private readonly Application $application,
		// Pure Filesystem otherwise creation stuff will be under /storage/app...
		private readonly Filesystem $filesystem,
	) {
	}

	/**
	 * Todo Replace with dir-segment and base_path() laravel
	 *
	 * @return string
	 */
	public function getStackName(): string
	{
		return $this->packageStackName;
	}

	/**
	 * Full Server-Path where the Stack is located
	 *
	 * @return string
	 */
	public function getStackBasePath(): string
	{
		return $this->application->basePath($this->getStackName());
	}

	/**
	 * Todo ..only on install the Packages-Software or/and create a new package-create (app-modules or/and app-my-vendor-namespace) creating the directory for the create
	 */
	public function ensureStackDirectoryExists(): bool
	{
		return $this->exists() || $this->filesystem->makeDirectory($this->getStackBasePath());
	}

	/**
	 * Check the get directory already exists.
	 * If not exists, this Stack was not created by the developers wish
	 *
	 * @return bool
	 */
	public function exists(): bool
	{
		return is_dir($this->getStackBasePath());
	}

	/**
	 * Getting a Package Definition from an already load Registry
	 *
	 * @param string $name
	 *
	 * @return Package|null
	 */
	public function package(string $name): ?Package
	{
		// todo make searchable by vendor/package.
		// ATM only "package-name"
		// We want to allow for gracefully handling empty/null names
		return $this->packages()
					->get($name)
		;
	}

	public function reset(): static
	{
		$this->packages = null;

		return $this;
	}

	/**
	 * @return Collection<string,Package>
	 */
	public function packages(?bool $forceNew = false): Collection
	{
		if ($this->exists()) {
			if ($forceNew) {
				$this->reset();
			}

			return $this->packages ??= $this->loadPackages();
		}

		// spin back a tmp collection, while packages=null the load mechanism has a chance to read in the packages if they later exist.
		return new Collection();
	}

	/**
	 * @return Collection<string,Package>
	 */
	public function reload(): Collection
	{
		$this->reset();

		return $this->loadPackages();
	}

	/**
	 * From Cache or Live
	 *
	 * @return Collection<string,Package>
	 */
	protected function loadPackages(): Collection
	{
		// todo ensure base path exists during a creation / installing process
		if (! $this->exists()) {
			return new Collection();
		}

		// magic
		return StackComposers::findPackages($this);
	}

	/**
	 * @return Filesystem
	 */
	public function getFilesystem(): Filesystem
	{
		return $this->filesystem;
	}

	public function getVendorList(): array
	{
		$vendors = [];
		/** @var Package $item */
		foreach ($this->packages() as $item) {
			$class = $item->getVendorPackageNames()
						  ->getVendor()
						  ->toClass()
			;
			$vendors[$class] = $class;
		}

		return $vendors;
	}
}
