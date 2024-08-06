<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Support\Stubs\Variables;

it(
	'test empty vars',
	function() {
		$vars = new Variables();
		expect($vars->renderVariables())->toBe([]);
	}
);
