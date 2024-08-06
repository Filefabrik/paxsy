<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Filefabrik\Paxsy\Support\Composer\WithShellExec;

afterAll(function() {
	$i = new WithShellExec();

	$i->batchMode();
	$i->removeRepository('my/key', null);
	$i->execute();
});
it(
	'presence',
	function() {
		expect(class_exists(WithShellExec::class))
			->toBeTrue()
			->and(new WithShellExec())
			->toBeInstanceOf(WithShellExec::class)
			->toBeInstanceOf(WithInterface::class)
		;
	},
);

it(
	'switch to batch mode',
	function() {
		$i = new WithShellExec();
		expect($i->isSingle())->toBeTrue();
		$i->batchMode();
		expect($i->isSingle())->toBeFalse();
	},
);

it(
	'add repository',
	function() {
		$i = new WithShellExec();

		$i->batchMode();
		$i->addRepository('my/key', '----');

		expect($i->getCommandExpressions()[0])->toBe('composer config repositories.paxsy-my/key \'----\'');
	},
);
it(
	'add repository execute',
	function() {
		$i = new WithShellExec();

		$i->batchMode();
		$body = \Filefabrik\Paxsy\Components\ComposerRepository\Repository::body('my/key');
		$i->addRepository('my/key', $body);
		$i->execute();
		expect($i->getCommandExpressions()[0])->toBe("composer config repositories.paxsy-my/key '$body'");
	},
);

it(
	'remove repository',
	function() {
		$i = new WithShellExec();

		$i->batchMode();
		$i->removeRepository('my/key');

		expect($i->getCommandExpressions()[0])->toBe('composer config repositories.paxsy-my/key --unset');
	},
);

it(
	'add vendor package',
	function() {
		$i = new WithShellExec();

		$i->batchMode();
		$i->addVendorPackage('my/key');

		expect($i->getCommandExpressions()[0])->toBe('composer require my/key');
	},
);

it(
	'remove vendor package',
	function() {
		$i = new WithShellExec();

		$i->batchMode();
		$i->removeVendorPackage('my/key');

		expect($i->getCommandExpressions()[0])->toBe('composer remove my/key');
	},
);
it('render Flags', function() {
	$i = new WithShellExec();

	$i->batchMode();
	$i->removeVendorPackage('my/key', 'testflag');

	expect($i->getCommandExpressions()[0])->toBe('composer remove my/key testflag');

	$i = new WithShellExec();

	$i->batchMode();
	$i->removeVendorPackage('my/key', ['testflag', 'second-flag']);

	expect($i->getCommandExpressions()[0])->toBe('composer remove my/key testflag second-flag');
});
