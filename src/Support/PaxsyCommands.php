<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support;

use Filefabrik\Paxsy\Console\Commands\Composer\DumpAutoloadCommand;
use Filefabrik\Paxsy\Console\Commands\Composer\RepositoryCommand;
use Filefabrik\Paxsy\Console\Commands\Composer\UpdateCommand;
use Filefabrik\Paxsy\Console\Commands\Composer\VendorPackageCommand;
use Filefabrik\Paxsy\Console\Commands\Info\ListCommand;
use Filefabrik\Paxsy\Console\Commands\Make\MakePackage;
use Filefabrik\Paxsy\Console\Commands\Make\MakePackageStack;
use Filefabrik\Paxsy\Console\Commands\PaxsyCommand;

/**
 * List of Commands they accessible by "public" cli
 */
class PaxsyCommands
{
	/**
	 * Add new Commands in Paxsy here
	 *
	 * @var class-string[]
	 */
	public static array $bootPublicCommands = [

		PaxsyCommand::class,
		DumpAutoloadCommand::class,
		MakePackage::class,
		ListCommand::class,
		MakePackageStack::class,
		UpdateCommand::class,
		RepositoryCommand::class,
		VendorPackageCommand::class,
	];

	/**
	 * @return class-string[]
	 */
	public static function publicCommands(): array
	{
		return self::$bootPublicCommands;
	}
}
