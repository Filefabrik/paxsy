<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Support\Composer;

use Composer\Factory;
use Composer\Json\JsonFile;
use Seld\JsonLint\ParsingException;

/**
 * Handle some composer Tasks
 *
 * @deprecated use native composer commands because more save
 * @property mixed|null $definition
 */
class Composer
{
	/**
	 * @var string|false
	 */
	private string|false $original_working_dir;

	/**
	 * Load if need
	 *
	 * @var ?JsonFile
	 */
	private ?JsonFile $composer_file = null;

	/**
	 * load composer.json as array
	 *
	 * @var array|null
	 */
	private ?array $definition = null;

	/**
	 * @param string $directory beginning from laravel installation
	 */
	public function __construct(private readonly string $directory)
	{
		// We're going to move into the Laravel base directory while
		// we're updating the composer file so that we're sure we update
		// the correct composer.json file (we'll restore CWD at the end)
		$this->original_working_dir = getcwd();
		chdir($this->directory);
	}

	/**
	 * @return JsonFile
	 */
	public function getComposerFile(): JsonFile
	{
		return $this->composer_file ??= new JsonFile(Factory::getComposerFile());
	}

	/**
	 * @return array|null
	 * @throws ParsingException
	 */
	public function getDefinition(): ?array
	{
		return $this->definition ??= $this->loadDefinition();
	}

	/**
	 * @return array|null
	 * @throws ParsingException
	 */
	private function getRequire(): ?array
	{
		return  $this->getDefinition()['require'] ?? null;
	}

	/**
	 * @throws ParsingException
	 */
	public function vendorPackageInRequire(string $vendor_package_name): bool
	{
		return (bool) (($this->getRequire() ?? [])[$vendor_package_name] ?? null);
	}

	/**
	 * @return array|null
	 * @throws ParsingException
	 */
	private function loadDefinition(): ?array
	{
		return $this->getComposerFile()
					->read()
		;
	}

	public function __destruct()
	{
		chdir($this->original_working_dir);
	}

	/**
	 * @return string
	 */
	public function directory(): string
	{
		return $this->directory;
	}

	/**
	 * @return null|false|string
	 */
	public function getOriginalWorkingDir(): false|string|null
	{
		return $this->original_working_dir;
	}
}
