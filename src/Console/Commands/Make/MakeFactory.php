<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Paxsy\Support\Str\ReplaceArray;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Str;

class MakeFactory extends FactoryMakeCommand
{
	use TraitModularize {
		TraitModularize::getPath as getPackagePath;
	}

	/**
	 * @param $stub
	 * @param $name
	 *
	 * @return MakeFactory
	 */
	protected function replaceNamespace(&$stub, $name): MakeFactory
	{
		return $this->package() ? $this->replaceNamespace_package($stub) :
			parent::replaceNamespace($stub, $name);
	}

	/**
	 * @param $stub
	 *
	 * @return MakeFactory
	 */
	protected function replaceNamespace_package(&$stub): MakeFactory
	{
		$namespace = $this->toPackageNamespace('Database', 'Factories');

		$replacements = [
			'{{ factoryNamespace }}'        => $namespace,
			'{{factoryNamespace}}'          => $namespace,
			'namespace Database\Factories;' => "namespace {$namespace};",
		];

		$stub = ReplaceArray::searchReplace($stub, $replacements);

		return $this;
	}

	/**
	 * Re-Pathing the Original Path from Laravel Output to the 'the-package' path
	 *
	 * @param $name
	 *
	 * @return array|string
	 */
	protected function getPath($name): array|string
	{
		return $this->package() ? $this->getPackagePath($name) : parent::getPath($name);
	}

	protected function guessModelName($name): string
	{
		return $this->package() ? $this->guessModelName_package($name) : parent::guessModelName($name);
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	protected function guessModelName_package($name): string
	{
		if (Str::endsWith($name, 'Factory')) {
			$name = substr($name, 0, -7);
		}

		$modelName = $this->qualifyModel($name);
		if (class_exists($modelName)) {
			return $modelName;
		}

		return Str::afterLast($modelName, Namespacering::Divider);
	}
}
