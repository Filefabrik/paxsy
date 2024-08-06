<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Bootraiser\Support\Str\PathsNamespaces;
use Filefabrik\Paxsy\Support\Package;
use Filefabrik\Paxsy\Support\Stack;
use Filefabrik\Paxsy\Support\Str\CaseConverters;
use Filefabrik\Paxsy\Support\Stringularity;
use Filefabrik\Paxsy\Support\VendorPackageNames;
use Illuminate\Support\Collection;
use JsonException;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Extracts a vendor/package json file to prepare the important things to handle this package in laravel via the filefabrik/package software
 */
class Extractor
{
	/**
	 * Todo refactor
	 *
	 * @param SplFileInfo $composer_file
	 * @param Stack       $packageStack read-only in package create directory
	 *
	 * @return Package
	 * @throws JsonException
	 */
	public static function fromComposerFile(SplFileInfo $composer_file, Stack $packageStack): Package
	{
		$composer_config = json_decode($composer_file->getContents(), true, 16, JSON_THROW_ON_ERROR);

		// base_path will be calculated from the get
		$base_path = Pathering::rtrim(PathsNamespaces::fromNamespaceToPath($composer_file->getPath()));

		// fix we are working with namespaced or direct package
		$moduleName = (str_ends_with($base_path, $composer_config['name'])) ? $composer_config['name'] :
			basename($base_path);// that is the way to make internal

		// Todo check Seeders and Migration namespaces such as in Tests to handle (only if need by users ...)
		$namespaces = self::extractNamespaces($composer_config['autoload']['psr-4'] ?? []);
		// todo read autoload-dev if need by users ..otherwise ignore it and take the default namespaced "Tests"
		// $devNamespaces = self::extractNamespaces($composer_config['autoload-dev']['psr-4'] ?? []);

		// create the names and class-names for the readin package
		// if no src found looks like an anarchy package ^^
		$srcNs = $namespaces->get('src', '');

		[$vendorClassName, $packageClassName] = explode(Namespacering::Divider, $srcNs);

		[$vendorName, $packageName] = CaseConverters::fromVendorPackageName($composer_config['name']);

		$vendorPackageNames = (new VendorPackageNames(
			new Stringularity($vendorName, $vendorClassName),
			new Stringularity(
				$packageName,
				$packageClassName,
			),
		))
			->setStackName($packageStack->getStackName())
		;

		return new Package(
			name              : $moduleName,
			vendorPackageNames: $vendorPackageNames
		);
	}

	/**
	 * @param array $autoloadPsr4
	 *
	 * @return Collection
	 */
	private static function extractNamespaces(array $autoloadPsr4): Collection
	{
		// todo package-name is where located the package (Vendor-Module or Simple-package)

		return Collection::make($autoloadPsr4)
						 ->mapWithKeys(function($src, $namespace) {
						 	$path      = Pathering::trim($src);
						 	$namespace = PathsNamespaces::trim($namespace);

						 	return [$path => $namespace];
						 })
		;
	}
}
