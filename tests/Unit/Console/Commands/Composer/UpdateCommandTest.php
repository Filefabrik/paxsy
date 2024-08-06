<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */

/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Composer\UpdateCommand;

it(
	'signature',
	function() {
		$dummy = new class() extends UpdateCommand {
			public function getSignature()
			{
				return $this->signature;
			}
		};

		expect($dummy->getSignature())->toBe('paxsy:composer-update');
	},
);
// todo feature test
it(
	'Flags expectation',
	function() {
		$this->artisan('paxsy:composer-update', ['--help' => true])
			 ->expectsOutputToContain('--flags')
			 ->assertExitCode(0)
		;
	},
);

it(
	'Execute with disabled',
	function() {
		useShellDisabled();

		$this->artisan('paxsy:composer-update', [])
			 ->assertExitCode(0)
		;
	},
);
