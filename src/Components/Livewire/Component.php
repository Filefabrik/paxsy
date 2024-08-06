<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Components\Livewire;

use Filefabrik\Paxsy\Support\Components\ComponentInterface;
use Illuminate\Console\Application as Artisan;
use Illuminate\Foundation\Application;
use Livewire\Features\SupportConsoleCommands\Commands\MakeCommand;
use Livewire\Features\SupportConsoleCommands\Commands\MakeLivewireCommand;

class Component implements ComponentInterface
{
	public static function make_commands(): array
	{
		return class_exists(MakeCommand::class) ? ['make:livewire'] : [];
	}

	public static function resolveCommands(Application $application, Artisan $artisan): void
	{ // Don't register commands if Livewire isn't installed
		if (class_exists(MakeCommand::class)) {
			// Replace the resolved command with our subclass
			$artisan->resolveCommands([MakeLivewire::class]);

			// Ensure that if 'make:livewire' or 'livewire:make' is resolved from the container
			// in the future, our subclass is used instead
			$application->extend(
				MakeCommand::class,
				fn() => new MakeLivewire(),
			);
			$application->extend(
				MakeLivewireCommand::class,
				fn() => new MakeLivewire(),
			);
		}
	}
}
