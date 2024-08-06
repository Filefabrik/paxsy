<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Helper;

use Filefabrik\Paxsy\Console\Commands\Make\MakeCast;
use Filefabrik\Paxsy\Console\Commands\Make\MakeChannel;
use Filefabrik\Paxsy\Console\Commands\Make\MakeCommand;
use Filefabrik\Paxsy\Console\Commands\Make\MakeComponent;
use Filefabrik\Paxsy\Console\Commands\Make\MakeController;
use Filefabrik\Paxsy\Console\Commands\Make\MakeEvent;
use Filefabrik\Paxsy\Console\Commands\Make\MakeException;
use Filefabrik\Paxsy\Console\Commands\Make\MakeFactory;
use Filefabrik\Paxsy\Console\Commands\Make\MakeJob;
use Filefabrik\Paxsy\Console\Commands\Make\MakeListener;
use Filefabrik\Paxsy\Console\Commands\Make\MakeMail;
use Filefabrik\Paxsy\Console\Commands\Make\MakeMiddleware;
use Filefabrik\Paxsy\Console\Commands\Make\MakeModel;
use Filefabrik\Paxsy\Console\Commands\Make\MakeNotification;
use Filefabrik\Paxsy\Console\Commands\Make\MakeObserver;
use Filefabrik\Paxsy\Console\Commands\Make\MakePolicy;
use Filefabrik\Paxsy\Console\Commands\Make\MakeProvider;
use Filefabrik\Paxsy\Console\Commands\Make\MakeRequest;
use Filefabrik\Paxsy\Console\Commands\Make\MakeResource;
use Filefabrik\Paxsy\Console\Commands\Make\MakeRule;
use Filefabrik\Paxsy\Console\Commands\Make\MakeSeeder;
use Filefabrik\Paxsy\Console\Commands\Make\MakeTest;

class OverrideCommands
{
	/**
	 * @var array|string[]
	 */
	protected static array $overrideCommands = [
		'make:cast'         => MakeCast::class,
		'make:controller'   => MakeController::class,
		'make:command'      => MakeCommand::class,
		'make:channel'      => MakeChannel::class,
		'make:event'        => MakeEvent::class,
		'make:exception'    => MakeException::class,
		'make:factory'      => MakeFactory::class,
		'make:job'          => MakeJob::class,
		'make:listener'     => MakeListener::class,
		'make:mail'         => MakeMail::class,
		'make:middleware'   => MakeMiddleware::class,
		'make:model'        => MakeModel::class,
		'make:notification' => MakeNotification::class,
		'make:observer'     => MakeObserver::class,
		'make:policy'       => MakePolicy::class,
		'make:provider'     => MakeProvider::class,
		'make:request'      => MakeRequest::class,
		'make:resource'     => MakeResource::class,
		'make:rule'         => MakeRule::class,
		'make:seeder'       => MakeSeeder::class,
		'make:test'         => MakeTest::class,
		'make:component'    => MakeComponent::class,

	];

	/**
	 * @return array<string,class-string<Command>> |string[]
	 */
	public static function commands(): array
	{
		return self::$overrideCommands;
	}

	/**
	 * On Bord Commands they are explizit in paxsy
	 *
	 * @return array
	 */
	public static function pureMakeCommands(): array
	{
		return array_keys(self::commands());
	}
}
