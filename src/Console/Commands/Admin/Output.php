<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Filefabrik\Paxsy\Support\Components\ComponentHelper;
use Filefabrik\Paxsy\Support\Helper\OverrideCommands;
use Filefabrik\Paxsy\Support\Package;
use Filefabrik\Paxsy\Support\StackApp;
use function Laravel\Prompts\table;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Seld\JsonLint\ParsingException;

/**
 * Some Outputs Merged for re-usability
 */
class Output
{
	/**
	 * for listing / selecting purposes as a menu
	 *
	 * @return array
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function getPackages(): array
	{
		return StackApp::get()
					   ->packages()
					   ->map(function(Package $package) {
					   	return ['name' => $package->getName(),
					   		'path'        => $package->getComposerName(),
					   	];
					   })
					   ->toArray()
		;
	}

	/**
	 * for listing / selecting purposes as a menu
	 *
	 * @return array
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function getPackageList(): array
	{
		return StackApp::get()
					   ->packages()
					   ->mapWithKeys(function(Package $package) {
					   	return [$package->getComposerName() => $package->getComposerName()];
					   })
					   ->toArray()
		;
	}

	/**
	 * @return array
	 */
	public static function availableMakeCommands(): array
	{
		$aMakeCommands = OverrideCommands::pureMakeCommands();

		$aMakeCommands[] = 'make:migration';

		// extra make commands via components or from somewhere but configured in config('app-paxsy.components');
		return [...$aMakeCommands, ...self::configuredCommands()];
	}

	/**
	 * @return array
	 * @see config('app-paxsy.components')
	 */
	public static function configuredCommands(): array
	{
		$components = config('paxsy.components');

		return $components ? ComponentHelper::makeCommands($components) : [];
	}

	public static function packageTable(): void
	{
		$packageStack = StackApp::get();
		$table        = $packageStack->packages()
									 ->map(function(Package $package) {
									 	$loadableStyle  = self::stylePackageNamespaceLoadable($package);
									 	$inRequireStyle = self::vendorPackageInRequire($package);

									 	return [
									 		$inRequireStyle.' '.$loadableStyle.' '.'./'.$package->getName(),
									 		$package->getComposerName(),
									 		$package->srcPackageNamespace(),

									 	];
									 })
									 ->toArray()
		;
		table(
			['/'.$packageStack->getStackName().' Package Directory',
				'composer name',
				'Package Namespace',
			],
			$table,
		);
	}

	/**
	 * @param Package $package
	 *
	 * @return string
	 */
	private static function stylePackageNamespaceLoadable(Package $package): string
	{
		return class_exists($package->serviceProviderNamespace()) ? '<fg=green;options=bold>▶ </>' :
			'<fg=red;options=bold>⏹ </>';
	}

	/**
	 * @param Package $package
	 *
	 * @return string
	 * @throws ParsingException
	 */
	private static function vendorPackageInRequire(Package $package): string
	{
		$bool = Composer::vendorPackageInRequire($package->getVendorPackageNames()
														 ->toComposerName());

		return $bool ? '<fg=green;options=bold>🖌 </>' : '<fg=red;options=bold>🖌 </>';
	}
}
