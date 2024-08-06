<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

use Illuminate\Contracts\Foundation\Application;
use UnexpectedValueException;

class PaxsyComposerBootService
{
	/**
	 * @param Application|null $app
	 *
	 * @return void
	 */
	public static function boot(?Application $app = null): void
	{
		$composer_execution_mode = config('paxsy.composer_execution');

		$composerExecutor = (new self())->getClass($composer_execution_mode);

		($app ?? app())->singleton(WithInterface::class, fn() => new $composerExecutor());
	}

	/**
	 * Which mode
	 *
	 * @param class-string<WithInterface> $configKey
	 *
	 * @return string
	 */
	private function getClass(string $configKey): string
	{
		return match ($configKey) {
			'shell_exec' => WithShellExec::class,
			'disabled'   => WithDisabled::class,
			default      => throw new UnexpectedValueException('Paxsy Composer configuration key is invalid. key:'.$configKey),
		};
	}
}
