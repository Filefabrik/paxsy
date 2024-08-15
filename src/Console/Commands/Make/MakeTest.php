<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Bootraiser\Support\Str\PathsNamespaces;
use Illuminate\Foundation\Console\TestMakeCommand;

class MakeTest extends TestMakeCommand
{
	use TraitPackagizer {
		TraitPackagizer::getPath as getModularPath;
	}

	protected function getPath($name): array|string
	{
		if ($package = $this->package()) {
			$name    = $package->subtractsPackageNamespace($name);
			$name    = $package->subtractsNamespace('Tests', $name);
			$relPath = Pathering::concat('tests', PathsNamespaces::fromNamespaceToPath($name)).'.php';

			return $this->intoPackagePath($relPath);
		}

		return parent::getPath($name);
	}

	/**
	 * @return string
	 */
	protected function rootNamespace(): string
	{
		return $this->package()
					?->joinPackageNamespace('Tests') ?? parent::rootNamespace()
		;
	}
}
