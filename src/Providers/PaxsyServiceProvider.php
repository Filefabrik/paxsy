<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Providers;

use Exception;
use Filefabrik\Bootraiser\Raiser;
use Filefabrik\Paxsy\Paxsy;
use Filefabrik\Paxsy\Support\Composer\PaxsyComposerBootService;
use Filefabrik\Paxsy\Support\PaxsyCommands;
use Filefabrik\Paxsy\Support\Stack;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class PaxsyServiceProvider extends ServiceProvider
{
	/**
	 * todo stack is wrong at this location
	 * @var Stack|null
	 */
	protected ?Stack $stack = null;

	/**
	 * @return void
	 * @throws Exception
	 */
	public function register(): void
	{
		Raiser::forProvider($this)->loadConfigs();

		/*
			* todo move body out, not testable
			* todo for multi-stack make it configurable
			* Discovers the currently the only existing app-packages directory
			*/
		$this->app->singleton(
			abstract: Stack::class,
			concrete: fn() => $this->packageStackCreator(),
		);
	}

	/**
	 * Whole Boot-Process
	 *
	 * @return void
	 */
	public function boot(): void
	{
		// todo, do not boot if paxsy commands are used. it is not need
		$this->bootPackageCommands();
		/*		// internal paxsy part
				;*/
	}

	/**
	 * Booting Paxsy Package Commands
	 *
	 * @return void
	 */
	protected function bootPackageCommands(): void
	{
		if (! $this->app->runningInConsole()) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}
		// handle composer execution
		PaxsyComposerBootService::boot($this->app);

		$this->commands(PaxsyCommands::publicCommands());
	}

	protected function packageStackCreator(): Stack
	{
		return new Stack(
			packageStackName: Paxsy::currentStackName(),
			application     : $this->app,
			filesystem      : new Filesystem(),
		);
	}
}
