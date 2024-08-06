<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Support\Composer\Composer;
use Filefabrik\Paxsy\Support\Composer\PaxsyComposerBootService;
use Filefabrik\Paxsy\Support\Composer\WithDisabled;
use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Filefabrik\Paxsy\Support\Composer\WithShellExec;

it(
	'check booted default',
	function() {
		expect($this->app->get(WithInterface::class))->toBeInstanceOf(WithShellExec::class);
	}
);
// switching
it(
	'check booted with disabled',
	function() {
		config()->set('paxsy.composer_execution', 'disabled');
		// reboot
		PaxsyComposerBootService::boot();
		expect($this->app->get(WithInterface::class))->toBeInstanceOf(WithDisabled::class);
	}
);
// switching
it(
	'check booted with shell_exec',
	function() {
		config()->set('paxsy.composer_execution', 'shell_exec');
		// reboot
		PaxsyComposerBootService::boot();
		expect($this->app->get(WithInterface::class))->toBeInstanceOf(WithShellExec::class);
	}
);

it(
	'check booted with exception',
	function() {
		config()->set('paxsy.composer_execution', 'stupid');
		// reboot
		PaxsyComposerBootService::boot();
	}
)->throws(UnexpectedValueException::class, 'Paxsy Composer configuration key is invalid. key:stupid');

it(
	'existence',
	function() {
		$relComposer = new Composer(__DIR__);
		expect($relComposer->directory())->toBeDirectory();
	},
);
