<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

/**
 * Keep Structuring in Variables
 */
interface VariablesRendererInterface
{
	/**
	 * @param array $variablesMap
	 * @param mixed $objects
	 *
	 * @return array
	 */
	public static function toArray(array $variablesMap, mixed $objects = null): array;
}
