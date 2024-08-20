<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Stubs;

use Filefabrik\Bootraiser\Support\Str\Pathering;
use Filefabrik\Paxsy\Support\Helper\TraitGenericLines;
use Illuminate\Filesystem\Filesystem;

class StubsWriter
{
	use TraitGenericLines;

	/**
	 * @var int[]|string[]
	 */
	private ?array $searches = null;

	/**
	 * @var array|null
	 */
	private ?array $replaces = null;

	/**
	 * @param string                                                                 $packageBasePath
	 * @param array{directories:array<int,string>|null,files:array<int,string>|null} $preparedStubs
	 * @param array                                                                  $variables
	 * @param Filesystem                                                             $filesystem
	 */
	public function __construct(
		private readonly string $packageBasePath,
		private readonly array $preparedStubs,
		private readonly array $variables,
		private readonly Filesystem $filesystem = new Filesystem(),
	) {
	}

	/**
	 * @return static
	 */
	public function writeStubs(): static
	{
		$this->createIfNeed();

		$this->writeDirectories($this->preparedStubs['directories'] ?? []);

		$this->writeFiles($this->preparedStubs['files'] ?? []);

		return $this;
	}

	public function createIfNeed(): void
	{
		$this->filesystem->ensureDirectoryExists($this->filesystem->dirname($this->packageBasePath));
	}

	/**
	 * @param array $files
	 *
	 * @return void
	 */
	public function writeFiles(array $files): void
	{
		foreach ($files as $destination => $stub) {
			$this->writeFile($destination, $stub);
		}
	}

	/**
	 * @param string $destinationPath
	 * @param        $stubPath
	 *
	 * @return bool
	 */
	public function writeFile(string $destinationPath, $stubPath): bool
	{
		$contents = file_get_contents($stubPath);

		$output = $this->replaceContent($contents);

		$destinationPath = $this->replaceContent($destinationPath);

		$filename = $this->makeFilename($destinationPath);

		// todo perhaps flag force or re-create all files
		if ($this->filesystem->exists($filename)) {
			$this->line(sprintf(' - Skipping <info>%s</info> (already exists)', $destinationPath));

			return false;
		}

		$out = $this->filesystem->dirname($filename);

		$this->filesystem->ensureDirectoryExists($out);
		$this->filesystem->put($filename, $output);

		$this->line(sprintf(' — Wrote file <info>%s</info> into %s', $destinationPath, $out));

		return true;
	}

	/**
	 * @param array $directories
	 *
	 * @return void
	 */
	public function writeDirectories(array $directories): void
	{
		foreach ($directories as $directory) {
			// todo test, pathname was filled
			$destination = $this->replaceContent($directory);
			$createPath  = Pathering::concat($this->packageBasePath, $destination);
			$this->filesystem
				->ensureDirectoryExists($createPath)
			;
		}
	}

	/**
	 * @return array
	 */
	public function searches(): array
	{
		return $this->searches ??= array_keys($this->variables);
	}

	/**
	 * @return array
	 */
	public function replaces(): array
	{
		return $this->replaces ??= array_values($this->variables);
	}

	/**
	 * @param $destination
	 *
	 * @return string
	 */
	protected function makeFilename($destination): string
	{
		// todo make sure it is trimmed
		return Pathering::concat($this->packageBasePath, $destination);
	}

	/**
	 * @param string $contents
	 *
	 * @return string
	 */
	protected function replaceContent(string $contents): string
	{
		return $this->searches() && $this->replaces() ?
			str_replace($this->searches(), $this->replaces(), $contents) :
			$contents;
	}
}
