<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Foundation\Console\NotificationMakeCommand;

// @codeCoverageIgnore
class MakeNotification extends NotificationMakeCommand
{
	use TraitModularize;
}
