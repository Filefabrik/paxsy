<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Paxsy\Support\VendorPackageNames;

/**
 * Render all 'package.relPackageDir' and so on
 */
class RendererPackage implements VariablesRendererInterface
{
	/**
	 * @param string             $segmentExpression
	 * @param VendorPackageNames $moduleConfig
	 *
	 * @return ?string
	 */
	public static function get(string $segmentExpression, VendorPackageNames $moduleConfig): ?string
	{
		return match ($segmentExpression) {
			'relPackageDir' => $moduleConfig->relPackageDir(),
			'packagePath'   => $moduleConfig->packageBasePath(),
			'composerName'  => $moduleConfig->toComposerName(),
			'vendor.class'  => $moduleConfig->getVendor()
											   ->toClass(),
			'package.class' => $moduleConfig->getPackage()
											   ->toClass(),
			'package.singular' => $moduleConfig->getPackage()
											   ->toSingularName(),
			'package.plural' => $moduleConfig->getPackage()
											   ->toPluralName(),
			'package.name' => $moduleConfig->getPackage()
											   ->toName(),
			// todo handle null because wanted and not found.
			// todo log error
			default => null,
		};
	}

	/**
	 * @param array                   $variablesMap
	 * @param VendorPackageNames|null $objects
	 *
	 * @return array
	 */
	public static function toArray(array $variablesMap, mixed $objects = null): array
	{
		$vars = [];

		foreach ($variablesMap as $key => $value) {
			$parsedV    = self::get($value, $objects);
			$vars[$key] = $parsedV;
		}

		return $vars;
	}
}
