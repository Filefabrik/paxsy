<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Composer;

use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Illuminate\Console\Command;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DumpAutoloadCommand extends Command
{
	use TraitFlags;

	/**
	 * @var string
	 */
	protected $signature = 'paxsy:dump-autoload';

	/**
	 * @var string
	 */
	protected $description = 'dumps autoload in laravel host composer.json';

	/**
	 * @return int
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function handle(): int
	{
		/** @var WithInterface $composerInterface */
		$composerInterface = app()->get(WithInterface::class);

		$composerInterface->add('composer dump-autoload', $this->getFlags());
		$composerInterface->lastTransactionToConsole($this);

		return self::SUCCESS;
	}
}
