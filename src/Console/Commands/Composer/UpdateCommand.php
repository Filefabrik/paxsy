<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Composer;

use Filefabrik\Paxsy\Support\Composer\WithInterface;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
	use TraitFlags;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'paxsy:composer-update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Executes composer update against Laravel Host composer.json';

	/**
	 * Execute the console command.
	 */
	public function handle(): int
	{
		app()
			->get(WithInterface::class)
			->add('composer update', $this->getFlags())
			->execute()
			->lastTransactionToConsole($this)
		;

		return self::SUCCESS;
	}
}
