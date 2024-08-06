<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Tests\Support;

/**
 * Override if need
 */
class LivewireComponentNames
{
	/**
	 * Livewire Test-Component Name in tests to prevent from "handwritten" stuff inside tests
	 * does not use strings in tests ...
	 */
	public const default_component_class = 'MyLvCompo';

	public const default_blade_prefix = 'my-lv-compo';

	// Path in src
	public const default_src_dir = 'Livewire';

	public const default_resource_dir = 'livewire';

	public const default_location = 'Livewire';

	public const default_namespace_prefix = 'Livewire';

	public static function defaultResourceDir(): string
	{
		return '/resources/views/'.self::default_resource_dir.'/'.self::default_blade_prefix.'.blade.php';
	}
}
