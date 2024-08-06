<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Paxsy\Support\VendorPackageNames;

class Facade
{
	public static function variables(VendorPackageNames $vendorPackageNames, ?FromConfig $config = null): Variables
	{
		$config ??= new FromConfig('default');

		return (new Variables())->setReplacementMaps($config->replacementMap())
								->setRendererClasses($config->getVariablesRenderer())
								->addVariables('package', $vendorPackageNames)
		;
	}
}
