<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Commands\TraitPackageSupport;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Filesystem\Filesystem;

class MakeMigration extends MigrateMakeCommand
{
	use TraitPackageSupport;

	protected function getMigrationPath(): string
	{
		$path = parent::getMigrationPath();

		if ($module = $this->package()) {
			$app_directory    = $this->laravel->databasePath('migrations');
			$module_directory = $module->intoPackagePath('database/migrations');

			$path = str_replace($app_directory, $module_directory, $path);

			$filesystem = $this->getLaravel()->make(Filesystem::class);
			if (! $filesystem->isDirectory($module_directory)) {
				$filesystem->makeDirectory($module_directory, 0755, true);
			}
		}

		return $path;
	}
}
