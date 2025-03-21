<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Components\Livewire\Component;
use Filefabrik\Paxsy\Support\Components\ComponentHelper;

it(
	'make commands',
	function() {
		$components = [
			// core
			\Filefabrik\Paxsy\Components\LaravelRoute\Component::class,
			// 3rd Party
			Component::class,
		];

		$commandList = ComponentHelper::makeCommands($components);
		expect($commandList)->toContain('make:livewire', 'make:route');
	},
);
