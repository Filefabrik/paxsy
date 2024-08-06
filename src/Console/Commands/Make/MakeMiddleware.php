<?php

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Routing\Console\MiddlewareMakeCommand;

// @codeCoverageIgnore
class MakeMiddleware extends MiddlewareMakeCommand
{
	use TraitModularize;
}
