<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Bootraiser\Support\Str\PathsNamespaces;
use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Filefabrik\Paxsy\Console\Commands\TraitPackageSupport;
use Illuminate\Support\Str;

trait TraitModularize
{
	use TraitPackageSupport;
	use TraitOptions;

	/**
	 * @param $rootNamespace
	 *
	 * @return array|string
	 */
	protected function getDefaultNamespace($rootNamespace): array|string
	{
		$namespace = parent::getDefaultNamespace($rootNamespace);
		$package   = $this->package();

		if (
			$package && ! str_starts_with(
				$rootNamespace,
				$package->srcPackageNamespace(),
			)
		) {
			$find      = Namespacering::rtrim($rootNamespace);
			$replace   = $package->srcPackageNamespace();
			$namespace = str_replace($find, $replace, $namespace);
		}

		return $namespace;
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	protected function qualifyClass($name): string
	{
		$name = PathsNamespaces::ltrim($name);

		if ($package = $this->package()) {
			if (
				Str::startsWith(
					$name,
					$package->srcPackageNamespace(),
				)
			) {
				return $name;
			}
		}

		return parent::qualifyClass($name);
	}

	/**
	 * Make Model or Package Model
	 *
	 * @param string $model
	 *
	 * @return array|string
	 */
	protected function qualifyModel(string $model): array|string
	{
		if ($this->package()) {
			$model = PathsNamespaces::fromPathToNamespace(PathsNamespaces::ltrim($model));
			// clear model, only the Model-Name
			$model = Str::afterLast($model, Namespacering::Divider, );

			return $this->toPackageNamespace('Models', $model);
		}

		return parent::qualifyModel($model);
	}

	/**
	 * @param $name
	 *
	 * @return array|string
	 */
	protected function getPath($name): array|string
	{
		if ($module = $this->package()) {
			// relative name to original laravel /app

			$name = $module->subtractsPackageNamespace($name);
		}

		// Absolute Path where original will be stored in Laravel /app
		$laravelComponentAppPath = parent::getPath($name);

		return $module ? $this->toVendorPackageDirectory($laravelComponentAppPath) : $laravelComponentAppPath;
	}
}
