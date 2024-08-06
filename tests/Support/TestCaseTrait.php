<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Tests\Support;

use Filefabrik\Paxsy\Providers\PaxsyCommandsServiceProvider;
use Livewire\LivewireServiceProvider;

/**
 * bundling all Test-Case Methods that they are usable in package-test context and in laravel "live" context testable
 */
trait TestCaseTrait
{
	/**
	 * @return void
	 */
	protected function tearDown(): void
	{
		parent::tearDown();
	}

	/**
	 * @param $app
	 *
	 * @return string[]
	 */
	protected function getPackageProviders($app): array
	{
		return [
			\Filefabrik\Paxsy\Providers\PaxsyServiceProvider::class,
			PaxsyCommandsServiceProvider::class,
			LivewireServiceProvider::class,
		];
	}

	/**
	 * @param $app
	 *
	 * @return class-string[]
	 */
	protected function getPackageAliases($app): array
	{
		// todo rename to packages
		return [
		];
	}
}
