<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Info;

use Filefabrik\Paxsy\Console\Commands\Admin\Output;
use Filefabrik\Paxsy\Support\Stack;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ListCommand extends Command
{
	/**
	 * @var string
	 */
	protected $signature = 'paxsy:list';

	/**
	 * @var string
	 */
	protected $description = 'List all Packages with additional information\'s';

	/**
	 * @param Stack $packageStack
	 *
	 * @return int
	 */
	public function handle(Stack $packageStack): int
	{
		$count = $packageStack->packages()
							  ->count()
		;
		$this->line('You have '.$count.' '.Str::plural('package', $count).' installed.');
		$this->line('');

		Output::packageTable();

		return self::SUCCESS;
	}
}
