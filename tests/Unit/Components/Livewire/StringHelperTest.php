<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Components\Livewire\StringHelper;
use Filefabrik\Paxsy\Support\Package;
use Symfony\Component\Finder\SplFileInfo;

it(
	'component name from class name',
	function($name, $ex) {
		$res = StringHelper::componentNameFromClassName($name);
		expect($res)->toBe($ex);
	},
)->with([['MakeLoveWireGreatAgain', 'make-love-wire-great-again']]);
it(
	'input name to class name',
	function($name, $ex) {
		$res = StringHelper::inputNameToClassName($name);
		expect($res)->toBe($ex);
	},
)->with([
	['MakeLoveWireGreatAgain', 'MakeLoveWireGreatAgain'],
	['make-love-wire-great-again', 'MakeLoveWireGreatAgain'],
	['make love wire-great-again', 'MakeLoveWireGreatAgain'],
]);
it(
	'view name',
	function($name, $ex) {
		$packageName = 'paxsy-sexy';
		$res         = StringHelper::viewName($packageName, $name);
		expect($res)->toBe($packageName.'::livewire.'.$ex);
	},
)->with([['make-love-wire-great-again', 'make-love-wire-great-again']]);

it(
	'blade tag',
	function($name, $ex) {
		$packageName = 'paxsy-sexy';
		$res         = StringHelper::bladeTag($packageName, $name);
		expect($res)->toBe('<livewire:'.$packageName.'::'.$ex.'/>');
	},
)->with([['make-love-wire-great-again', 'make-love-wire-great-again']]);

it(
	'tag From Input Name',
	function($name, $ex) {
		$res = StringHelper::tagFromInputName('paxsy-sexy', $name);
		expect($res)->toBe('<livewire:paxsy-sexy::'.$ex.'/>');
	},
)->with([['make-love-wire-great-again', 'make-love-wire-great-again'],
	['make love wire great-again', 'make-love-wire-great-again'],
	['fast and furious', 'fast-and-furious']]);

it(
	'to Livewire Component Name ',
	closure: function() {
		$dummyFile = new SplFileInfo(
			file            : '/notExists/FastAndFurious.php',
			relativePath    : '',
			relativePathname: 'FastAndFurious.php'
		);

		$defaultPackage = defaultTestPackage();
		$package        = new Package($defaultPackage->vendorPackageName(), $defaultPackage);

		$res = StringHelper::toLivewireComponentName($dummyFile, $package);
		expect($res)->toBe('the-test-package::fast-and-furious');
	},
);
