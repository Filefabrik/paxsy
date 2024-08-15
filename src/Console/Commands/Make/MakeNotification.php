<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Illuminate\Foundation\Console\NotificationMakeCommand;

// @codeCoverageIgnore
class MakeNotification extends NotificationMakeCommand
{
    use TraitPackagizer;
    use TraitOptions;
    use TraitCreatesMatchingTest;
    use TraitCallDelegation;
}
