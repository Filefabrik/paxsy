<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy;

use UnexpectedValueException;

class Paxsy
{
	// unique key app container key for the composer handler
	public static function currentStackName()
	{
		return config('paxsy.stack_name') ?? throw new UnexpectedValueException('Missing StackName');
	}
}
