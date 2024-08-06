<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Make\MakeCast;

arch(
	'commands extends original command',
	function($command, $extCls) {
		expect($command)
			->toExtend($extCls)
		;
	}
)->with('make Commands Extends');

/**
 * todo check trait implemented
 */
arch(
	'architecture has traits',
	function($commandClass) {
		expect($commandClass)
			->traits()
			->toExtend('\Filefabrik\Paxsy\Console\Commands\Make\TraitModularize')
		;
	}
)->with([[MakeCast::class]]);

arch(
	'has package flags',
	function($command) {
		$this->artisan($command, ['--help' => true])
			 ->expectsOutputToContain('--package')
			 ->assertExitCode(0)
		;
	}
)->with('make Commands List');
