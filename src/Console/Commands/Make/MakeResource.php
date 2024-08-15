<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\ResourceMakeCommand;

// @codeCoverageIgnore
class MakeResource extends ResourceMakeCommand
{
	use TraitPackagizer;
}
