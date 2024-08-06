<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Providers;

use Filefabrik\Paxsy\Console\Commands\Make\MakeMigration;
use Filefabrik\Paxsy\Support\Components\ComponentInterface;
use Filefabrik\Paxsy\Support\Helper\OverrideCommands;
use Illuminate\Console\Application;
use Illuminate\Console\Application as Artisan;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand as OriginalMakeMigrationCommand;
use Illuminate\Support\ServiceProvider;

class PaxsyCommandsServiceProvider extends ServiceProvider
{
	/**
	 * @return void
	 */
	public function register(): void
	{
		// Register our overrides via the "booted" event to ensure that we override
		// the default behavior regardless of which service provider happens to be
		// bootstrapped first (this mostly matters for Livewire).
		$this->app->booted(function() {
			Artisan::starting(function(Application $artisan) {
				$this->registerMakeCommandOverrides();
				$this->registerMigrationCommandOverrides();

				$this->registerComponentCommands($artisan);
			});
		});
	}

	/**
	 * @return void
	 */
	protected function registerMakeCommandOverrides(): void
	{
		foreach (OverrideCommands::commands() as $alias => $class_name) {
			$this->app->singleton($alias, $class_name);
			$this->app->singleton(get_parent_class($class_name), $class_name);
		}
	}

	/**
	 * Own commands without override
	 *
	 * @param Artisan $artisan
	 *
	 * @return void
	 */
	protected function registerComponentCommands(Artisan $artisan): void
	{
		/** @var array<int,class-string<ComponentInterface>> $components $components */
		$components = config('paxsy.components');
		if ($components) {
			foreach ($components as $component) {
				$component::resolveCommands($this->app, $artisan);
			}
		}
	}

	/**
	 * @return void
	 */
	protected function registerMigrationCommandOverrides(): void
	{
		// Laravel 9
		$this->app->singleton(
			OriginalMakeMigrationCommand::class,
			fn($app) => new MakeMigration($app['migration.creator'], $app['composer']),
		);
	}
}
