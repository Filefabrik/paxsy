<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

class RendererText implements VariablesRendererInterface
{
	/**
	 * @param array $variablesMap
	 * @param mixed $objects
	 *
	 * @return array
	 */
	public static function toArray(array $variablesMap, mixed $objects = null): array
	{
		return $variablesMap;
	}
}
