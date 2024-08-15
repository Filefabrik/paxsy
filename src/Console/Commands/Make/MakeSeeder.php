<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Support\Str;

class MakeSeeder extends SeederMakeCommand
{
	use TraitPackagizer {
		TraitPackagizer::getPath as getModularPath;
	}

	protected function getPath($name): array|string
	{
		if ($this->package()) {
			$name = Str::replaceFirst($this->seederNamespace(), '', $name);

			return $this->getModularPath($name);
		}

		return parent::getPath($name);
	}

	protected function rootNamespace(): string
	{
		return $this->package() ? $this->seederNamespace() : parent::rootNamespace();
	}

	protected function seederNamespace(): string
	{
		return$this->toPackageNamespace('Database', 'Seeders');
	}
}
