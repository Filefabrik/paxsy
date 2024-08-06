<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Components\Livewire;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Filefabrik\Paxsy\Console\Commands\TraitPackageSupport;

trait TraitModularize
{
	use TraitPackageSupport;
	use TraitOptions;
}
