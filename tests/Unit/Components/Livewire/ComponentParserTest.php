<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Components\Livewire\ComponentParser;
use Filefabrik\Paxsy\Support\Package;

it(
	'component parser package not set',
	function() {
		$defaultPackage = defaultTestPackage();
		$package        = new Package($defaultPackage->getPackageName(), $defaultPackage);

		$classNamespace = $package->joinPackageNamespace('Livewire');
		$viewPath       = $package->intoPackagePath('resources/views/livewire');
		$name           = 'TestingLivewireName';
		$parser         = new ComponentParser(
			$classNamespace,
			$viewPath,
			$name,
			'default',
		);

		// testbench dir
		$livewirePathing = 'app-paxsy-testing.the-test-package.resources.views.livewire.testing-livewire-name';
		expect($parser->viewName())->toEndWith($livewirePathing);
	}
);
