<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Support\Stubs\Variables;
use Filefabrik\Paxsy\Tests\Support\DefaultPackageNames;

it(
	'handle replacements correctly',
	function() {
		$configPrefix = 'paxsy';

		$defaultPackage = defaultTestPackage();

		$selectedStubsSet = 'default';

		$replacementMap = config(
			$configPrefix.'.stub_sets.'.$selectedStubsSet.'.replacementMap',
			[],
		);
		$variablesRenderer = config($configPrefix.'.VariablesRenderer');

		// Filling, move somewhere out
		$vars = (new Variables())->setReplacementMaps($replacementMap)
											->setRendererClasses($variablesRenderer)
											->addVariables('package', $defaultPackage)
		;
		$replaceableVars = $vars->renderVariables();

		expect($replaceableVars['StubRelPackageDir'])
			->toBe(DefaultPackageNames::RelativePackagePath())
			->and($replaceableVars['StubPackagePath'])
			->toBe(DefaultPackageNames::PackagePath())
			->and($replaceableVars['StubVendorNamespace'])
			->toBe(DefaultPackageNames::vendor_namespace)
			->and($replaceableVars['StubPackageNamespace'])
			->toBe(DefaultPackageNames::package_namespace)
			->and($replaceableVars['StubPackageNameSingular'])
			->toBe(DefaultPackageNames::package_name_singular)
			->and($replaceableVars['StubPackageNamePlural'])
			->toBe(DefaultPackageNames::package_name_plural)
			->and($replaceableVars['StubComposerName'])
			->toBe(DefaultPackageNames::vendor_name.'/'.DefaultPackageNames::package_name)
			// texts
			->and($replaceableVars['StubTestCaseBase'])
			->toBe('TestCase')
			->and($replaceableVars['#StubTestUseTestCase'])
			->toBe('use PHPUnit\TestCase;')
		;
	},
);
