<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Console\Commands\Composer\VendorPackageCommand;

it(
	'signature',
	function() {
		$dummy = new class() extends VendorPackageCommand {
			public function getSignature()
			{
				return $this->signature;
			}
		};
		$contains = ['paxsy:vendor-package ',
			'{vendor/package : my-company/my-package-name}',
			'{--remove=false : remove vendor/package from laravel host composer.json}'];

		expect($dummy->getSignature())->toContain(...$contains);
	},
);
it(
	'Check has Flag Flags',
	function() {
		$this->artisan('paxsy:vendor-package', ['--help' => true])
			 ->expectsOutputToContain('--flags')
			 ->assertExitCode(0)
		;
	},
);
it(
	'Check has Flag Remove',
	function() {
		$this->artisan('paxsy:vendor-package', ['--help' => true])
			 ->expectsOutputToContain('--remove')
			 ->assertExitCode(0)
		;
	},
);
// todo check content of response
it(
	'Handle against with Disabled',
	function() {
		useShellDisabled();
		$this->artisan('paxsy:vendor-package', [VendorPackageCommand::VendorPackageIdent => 'my-company/my-package-name'])

			 ->assertExitCode(0)
		;
	},
);
// todo check content of response
it(
	'Handle against with Disabled --remove',
	function() {
		useShellDisabled();
		$this->artisan('paxsy:vendor-package', [VendorPackageCommand::VendorPackageIdent => 'my-company/my-package-name', '--remove' => true])

			 ->assertExitCode(0)
		;
	},
);
