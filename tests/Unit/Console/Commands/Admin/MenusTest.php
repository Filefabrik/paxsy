<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Admin\Menus;

it(
	'Main Menu contains all Items',
	function($label, $method) {
		$foundFlag = false;
		foreach (Menus::$mainMenu as $item) {
			if ($label === $item['label']) {
				expect($method === $item['method'])->toBeTrue();
				$foundFlag = true;
				break;
			}
		}
		expect($foundFlag)->toBeTrue();
	}
)->with('main menu');

it(
	'Plug correctly',
	function($label, $method) {
		$res = Menus::selectableMenu(Menus::$mainMenu);

		expect($res[$method])->toBe($label);
	}
)->with('main menu');
