<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Illuminate\Support\Arr;

/**
 * Some menu handling. Not ideal but for the Moment it will work
 */
class Menus
{
	/**
	 * @var array[]
	 */
	public static array $mainMenu = [
		['label' => 'add components', 'method' => 'handle_package'],
		['label' => 'new laravel composer package', 'method' => 'create_package'],
		['label' => 'list packages', 'method' => 'list_packages'],
		// Handle composer Package into the Laravel-Host
		['label' => 'add VendorPackage with there Repository (Laravel Host Composer )', 'method' => 'composer_add_repository_vendor_package'],
		['label' => 'remove VendorPackage with there Repository (Laravel Host Composer)', 'method' => 'composer_remove_repository_vendor_package'],
		['label' => 'Composer Update (experimental)', 'method' => 'composer_update'],

	];

	/**
	 * @param array $menu
	 *
	 * @return array
	 */
	public static function selectableMenu(array $menu): array
	{
		return Arr::pluck($menu, 'label', 'method');
	}
}
