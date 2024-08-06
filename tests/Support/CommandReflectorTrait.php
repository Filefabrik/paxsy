<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Tests\Support;

trait CommandReflectorTrait
{
	public function getSignature()
	{
		return $this->signature;
	}

	public function reflectOptions()
	{
		return$this->getOptions();
	}

	public function reflectArguments()
	{
		return$this->getArguments();
	}
}
