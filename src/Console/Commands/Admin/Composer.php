<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Seld\JsonLint\ParsingException;

/**
 * Host Composer helper to prevent waste the main console command scripts.
 * And also the shell_exec should be overridable for testing purposes
 */
class Composer
{
	/**
	 * @var string|null
	 */
	protected static ?string $laravelHostComposerPath = null;

	/**
	 * @return string
	 */
	public static function getLaravelHostComposerPath(): string
	{
		return self::$laravelHostComposerPath ??= app()->basePath();
	}

	/**
	 * @return \Filefabrik\Paxsy\Support\Composer\Composer
	 */
	public static function getLaravelHostComposer(): \Filefabrik\Paxsy\Support\Composer\Composer
	{
		return new \Filefabrik\Paxsy\Support\Composer\Composer(self::getLaravelHostComposerPath());
	}

	/**
	 * Check a vendor/package is in the laravel host composer.json
	 *
	 * @param string $vendor_package_name
	 *
	 * @return bool
	 * @throws ParsingException
	 */
	public static function vendorPackageInRequire(string $vendor_package_name): bool
	{
		$hostComposer = Composer::getLaravelHostComposer();
		$exists       = $hostComposer->vendorPackageInRequire($vendor_package_name);
		$hostComposer->__destruct();

		return $exists;
	}
}
