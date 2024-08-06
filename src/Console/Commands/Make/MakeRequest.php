<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\RequestMakeCommand;

// @codeCoverageIgnore
class MakeRequest extends RequestMakeCommand
{
	use TraitModularize;
}
