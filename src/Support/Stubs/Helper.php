<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Paxsy\Support\StackApp;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Helper at the moment to create stubs with the new configurable mechanism
 *
 * @internal
 */
class Helper
{
	/**
	 * Ugly Worker-Class to create the Stubs-Writing Process
	 *
	 * @param string $packageBasePath the new package config where the stubs will be rendered into
	 * @param array  $stubsMap        the whole stub array
	 * @param string $stubsDirectory
	 * @param array  $variables
	 *
	 * @return StubsWriter
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function createStubs(
		string $packageBasePath,
		array $stubsMap,
		string $stubsDirectory,
		array $variables,
	): StubsWriter {
		// todo forecast package output targets
		// todo make callable by a kind of singleton
		// todo describe internally

		$preparedStubs = (new StubsMap($stubsMap, $stubsDirectory))->getStubs();

		return new StubsWriter(
			// todo on multi-create use an other getter
			packageBasePath: $packageBasePath,
			preparedStubs  : $preparedStubs,
			variables      : $variables,
			filesystem     : StackApp::get()
									 ->getFilesystem(),
		);
	}
}
