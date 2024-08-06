<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Support\StackApp;
use Illuminate\Console\Command;

/**
 * Create the Directory where Packages will be stored into this directory
 */
class MakePackageStack extends Command
{
	protected $signature = 'paxsy:package-create';

	protected $description = 'Initial a create directory where packages will be organized into';

	public function handle(): int
	{
		$packageStack = StackApp::get();

		if (! $packageStack->exists()) {
			$packageStack->ensureStackDirectoryExists();

			// todo would you like to publish configs before. so you can customize some stuff if need
			$this->line('<kbd>php artisan vendor:publish --tag=paxsy-config</kbd>');

			$successfully = 'Package Stack "'.$packageStack->getStackName().'" created successfully in your laravel: "'.$packageStack->getStackBasePath().'"!';

			$this->getOutput()
				 ->title($successfully)
			;

			return self::SUCCESS;
		}
		$this->getOutput()
			 ->title('Package Stack "'.$packageStack->getStackName().'" already exists in your laravel: "'.$packageStack->getStackBasePath().'"!')
		;

		return self::SUCCESS;
	}
}
