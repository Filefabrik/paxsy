<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Composer\DumpAutoloadCommand;

beforeEach(function() {
	useShellDisabled();
});
it(
	'signature',
	function() {
		$dummy = new class() extends DumpAutoloadCommand {
			public function getSignature()
			{
				return $this->signature;
			}
		};

		expect($dummy->getSignature())->toBe('paxsy:dump-autoload');
	},
);
it(
	'handle dummy command',
	function() {
		$this->artisan('paxsy:dump-autoload', )

			 ->assertExitCode(0)
		;
	},
);
