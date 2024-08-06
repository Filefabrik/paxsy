<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands;

use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Paxsy\Support\Package;
use Filefabrik\Paxsy\Support\StackApp;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputOption;

/**
 * @method Application getLaravel()
 */
trait TraitPackageSupport
{
	/**
	 * Null on not set
	 * false on package not set or not found
	 * Package on found a package
	 *
	 * @var bool|Package|null
	 */
	private null|bool|Package $ownPackage = null;

	/**
	 * Getting the VendorPackage which is selected for the current Console Command
	 *
	 * @return Package|null
	 */
	protected function package(): ?Package
	{
		// load once in a class instance
		$this->ownPackage ??= $this->getPackageByOption();

		// working with laravel default commands
		return $this->ownPackage ?: null;
	}

	protected function resetPackage(): static
	{
		$this->ownPackage = null;

		return $this;
	}

	protected function toPackageNamespace(...$segments): string
	{
		return $this->package()
					->joinPackageNamespace(...$segments)
		;
	}

	protected function intoPackagePath(string $pathSegment): string
	{
		return $this->package()
					->intoPackagePath($pathSegment)
		;
	}

	private function getPackageByOption(): false|Package
	{
		if ($name = $this->option('package')) {
			return StackApp::get()
						   ->package($name) ??
				throw new InvalidOptionException(sprintf('The "%s" package does not exist.', $name))
			;
		}

		return false;
	}

	protected function toVendorPackageDirectory(string $laravelComponentAppPath): string
	{
		$package = $this->package();
		// Set up our replacements as a [find -> replace] array
		// all to relative
		$replacements = [
			$this->getLaravel()
				 ->path() => $package->intoRelativePackagePath($package->getSrcDirName()),
			// todo tests has to be his own mechanism
			$this->getLaravel()
				 ->basePath('tests') => $package->intoRelativePackagePath('tests'),
			$this->getLaravel()
				 ->databasePath() => $package->intoRelativePackagePath('database'),
		];

		// Normalize all our paths for compatibility's sake
		// todo make static function on right trim paths
		$normalize = fn($path) => Pathering::withEnd($path);

		$find    = array_map($normalize, array_keys($replacements));
		$replace = array_map($normalize, array_values($replacements));

		// make naked relative directory
		$relativeDirectory = str_replace($find, $replace, $laravelComponentAppPath);

		// prepend the current package location, so we have the my-package base_path where the laravel component will be written to

		return base_path(Pathering::ltrim($relativeDirectory));
	}

	protected function configure(): void
	{
		parent::configure();

		$def = $this->getDefinition();
		$def->addOption(
			new InputOption(
				'--package',
				null,
				InputOption::VALUE_REQUIRED,
				'Run inside an application package',
			),
		);
		/*$def->addOption(
			new InputOption(
				'--internalPaxsyFrom',
				null,
				InputOption::VALUE_REQUIRED,
				'internal call from component to another component',
			),
		);*/
	}
}
