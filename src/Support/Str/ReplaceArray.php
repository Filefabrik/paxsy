<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Str;

class ReplaceArray
{
	public static function searchReplace(string $s, array $searchReplace): string
	{
		return (string) str_replace(array_keys($searchReplace), array_values($searchReplace), $s);
	}
}
