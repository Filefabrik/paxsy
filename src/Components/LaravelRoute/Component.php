<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Components\LaravelRoute;

use Filefabrik\Paxsy\Support\Components\ComponentInterface;
use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Application;

class Component implements ComponentInterface
{
	/**
	 * @return string[]
	 */
	public static function make_commands(): array
	{
		return ['make:route'];
	}

	/**
	 * @param Application $application
	 * @param Artisan     $artisan
	 *
	 * @return void
	 */
	public static function resolveCommands(Application $application, Artisan $artisan): void
	{
		$artisan->resolveCommands([MakeRoute::class]);
	}
}
