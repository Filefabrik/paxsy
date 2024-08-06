<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Bootraiser\Support\Str\Pathering;

readonly class StubsMap
{
	/**
	 * @param array  $stubsMap
	 * @param string $stubsDirectory
	 */
	public function __construct(
		private array $stubsMap,
		private string $stubsDirectory,
	) {
	}

	/**
	 * @return array<string,array{name:string,path:string,stubsDirectory:string,exists:bool,source:string}>
	 */
	public function getStubs(): array
	{
		return [
			'directories' => $this->prepareDirectories($this->stubsMap['directories'] ?? []),
			'files'       => $this->prepareFiles($this->stubsMap['files'] ?? []),
		];
	}

	private function prepareDirectories(array $directories): array
	{
		$rets = [];

		foreach ($directories as $dir) {
			$rets[] = $dir;
		}

		return $rets;
	}

	private function prepareFiles(array $files): array
	{
		$rets = [];
		foreach ($files as $outName => $source) {
			$pathName = Pathering::concat($this->stubsDirectory, $source);
			// if source-string ends with slash, it is a stubsDirectory to create

			// todo normalize from laravel host application path

			// todo strategy if missing a stub
			$rets[$outName] = $pathName;
		}

		return $rets;
	}
}
