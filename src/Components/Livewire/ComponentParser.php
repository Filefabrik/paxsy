<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Components\Livewire;

use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Bootraiser\Support\Str\PathsNamespaces;
use Filefabrik\Paxsy\Support\Package;
use UnexpectedValueException;

/**
 * Override the original ComponentParser at some points to work properly with paxsy
 */
class ComponentParser extends \Livewire\Features\SupportConsoleCommands\Commands\ComponentParser
{
	/**
	 * @var string|null
	 */
	private ?string $viewName = null;

	/**
	 * @var Package|null
	 */
	private ?Package $package = null;

	/**
	 * Name from Command
	 *
	 * @var string|null
	 */
	private ?string $inputName = null;

	/**
	 * @param Package $package
	 *
	 * @return static
	 */
	public function setPackage(Package $package): static
	{
		$this->package = $package;

		return $this;
	}

	public function getInputName(): string
	{
		return $this->inputName;
	}

	public function setInputName(string $inputName): static
	{
		$this->inputName = $inputName;

		return $this;
	}

	public function remapClasses(): void
	{
		if ($this->package) {
			$this->baseClassPath = $this->generatePathFromNamespace_package();
			$this->baseTestPath  = $this->generateTestPathFromNamespace_package($this->baseTestNamespace);

			$className      = StringHelper::inputNameToClassName($this->inputName);
			$this->viewName = StringHelper::viewNameFromClass($this->package->getName(), $className);
		}
	}

	public function generatePathFromNamespace_package(): string
	{
		return $this->package->intoPackagePath('Livewire');
	}

	// override

	public function generateTestPathFromNamespace_package($namespace): string
	{
		$originalPath = parent::generateTestPathFromNamespace($namespace);

		$relativePath = PathsNamespaces::fromNamespaceToPath((string) Pathering::stripPathFromStart(base_path(), $originalPath));

		return $this->package->intoPackagePath($relativePath);
	}

	// override for paxsy package

	/**
	 * @return string
	 */
	public function viewName(): string
	{
		// refer to https://github.com/InterNACHI/modular/issues/85
		if (null === $this->package) {
			return parent::viewName();
		}

		// otherwise make own component name
		// todo, has to be testet
		return $this->viewName ?? throw new UnexpectedValueException('View name cannot be null');
	}

	// override for paxsy package

	public function classPath(): string
	{
		// class and filename
		$className = StringHelper::inputNameToClassName($this->inputName);

		// todo src
		return $this->package->intoPackagePath("src/Livewire/$className.php");
	}
}
