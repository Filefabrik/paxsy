<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Finders;

use Filefabrik\Paxsy\Support\Composer\Extractor;
use Filefabrik\Paxsy\Support\Package;
use Filefabrik\Paxsy\Support\Stack;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use JsonException;
use Symfony\Component\Finder\Finder;

/**
 * Searches composer.json in the Top of a Stack (app-modules)
 *
 * @note other finders in this directory searching for packages inside app-modules/package-names.
 */
readonly class StackComposers
{
	/**
	 * Decoupled and independent finder for composer.json in a Stack /app-modules/** or /packages/**
	 *
	 * @param Stack $stack
	 *
	 * @return Collection<string,Package>
	 */
	public static function findPackages(Stack $stack): Collection
	{
		return self::packagesButler(self::find($stack->getStackBasePath()), $stack);
	}

	/**
	 * Searching for composer.json in a directory ...utility
	 *
	 * @param string $stackBasePath
	 *
	 * @return Finder
	 */
	public static function find(string $stackBasePath): Finder
	{
		return Finder::create()
					 ->files()
					 ->depth('1')
					 ->name('composer.json')
					 ->in($stackBasePath)
		;
	}

	/**
	 * !!Most Important Method!!
	 * Load each found composer.json and make it usable for the whole software
	 *
	 * @param Finder $finder
	 * @param Stack  $packageStack
	 *
	 * @return Collection<string,Package>
	 */
	protected static function packagesButler(Finder $finder, Stack $packageStack): Collection
	{
		$packages = [];
		foreach ($finder as $item) {
			try {
				$package = Extractor::fromComposerFile($item, $packageStack);
			} catch (JsonException $e) {
				Log::error($e->getMessage());
				continue;
			}

			// current Registry design is the package-Name as index
			$packages[$package->getName()] = $package;
		}

		return new Collection($packages);
	}
}
