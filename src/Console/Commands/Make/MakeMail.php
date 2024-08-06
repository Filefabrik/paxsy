<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\MailMakeCommand;

// @codeCoverageIgnore
class MakeMail extends MailMakeCommand
{
	use TraitModularize;
}
