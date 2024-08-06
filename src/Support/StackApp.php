<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support;

use Illuminate\Contracts\Foundation\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Central point to prevent ugly app()->has(...) somewhere in code-base
 */
class StackApp
{
	/**
	 * public getter
	 *
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function get(?Application $app = null): ?Stack
	{
		return ($app ?? app())->get(Stack::class);
	}
}
