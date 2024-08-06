<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Illuminate\Foundation\Console\ModelMakeCommand;

class MakeModel extends ModelMakeCommand
{
	use TraitModularize;
	use TraitCallDelegation;

	protected function getDefaultNamespace($rootNamespace): string
	{
		$rootNamespace = $this->package()
							  ?->srcPackageNamespace() ?? $rootNamespace
		;

		return Namespacering::concat($rootNamespace, 'Models');
	}
}
