<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Filefabrik\Paxsy\Console\Commands\PaxsyCommand;
use Filefabrik\Paxsy\Paxsy;
use Filefabrik\Paxsy\Support\Stack;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Reusable Inputs
 */
class Inputs
{
	public static function suggestMainMenu($menuOptions): int|string
	{
		$menuOptions[PaxsyCommand::QUIT] = PaxsyCommand::QUIT;

		$selected = select(
			label   : 'Paxsy Menu',
			options : $menuOptions,
			scroll  : 10,
			validate: function(string $name) use ($menuOptions) {
				return in_array($name, $menuOptions) || ($menuOptions[$name] ?? null) ? null :
					'Menu Item "'.$name.'" does not exist';
			},
			hint    : 'The Paxsy main menu ',
		);

		return ($menuOptions[$selected] ?? null) ? $selected : array_search($selected, $menuOptions);
	}

	/**
	 * @param mixed ...$params
	 *
	 * @return int|string
	 */
	public static function suggestComposerVendors(...$params): int|string
	{
		[$packageStack, $defaultVendor] = $params;
		$opts                           = [];
		if (($packageStack instanceof Stack)) {
			$opts = $packageStack->getVendorList();
		}

		return suggest(
			label  : '"your-vendor-name" of the Package',
			options: fn($value) => (new Collection($opts))
				->filter(fn($title) => str_contains(Str::lower($title), Str::lower($value)))
				->all(),
			default: $defaultVendor,
			hint   : 'enter the first part before the slash for the new composer.json {"name": "your-vendor-name/..."}',
		);
	}

	/**
	 * Packages within the current Paxsy Stack Name
	 *
	 * @param string|null $commandName
	 *
	 * @return string
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function suggestPackageName(?string $commandName = null): string
	{
		$packages = Output::getPackageList();

		if (! $packages) {
			return '';
		}
		$stackName = Paxsy::currentStackName();

		$commandName ??= 'command';

		return suggest(
			label  : "Select Package where to apply the $commandName",
			options: fn($value) => (new Collection($packages))
				->filter(fn($title) => str_contains(Str::lower($title), Str::lower($value)))
				->all(),
			hint   : "select package from  /$stackName/",
		);
	}

	/**
	 * The package_name my-company-vendor/{package-name}
	 *
	 * @param mixed ...$params
	 *
	 * @return string
	 */
	public static function packageName(...$params): string
	{
		$vendorNamespace = $params[0] ?? '';
		// todo track the current wanted package
		$stackName = Paxsy::currentStackName();

		// your company in composer.json "name": "$vendor_namespace/laravel",
		return text(
			label      : 'Name of your package?',
			placeholder: 'My Amazing Package',
			hint       : "/$stackName/my-amazing-package\n Namespace will be {$vendorNamespace}\MyAmazingPackage",
		);
	}

	/**
	 * @return int|string
	 */
	public static function selectStubsSet(): int|string
	{
		$options = self::stubsList();
		//  nothing to select
		if (1 === count($options)) {
			return array_key_first($options);
		}

		// todo default architecture from app-package config
		// your company in composer.json "name": "$vendor_namespace/laravel",
		// todo track the current wanted package
		return select(
			label  : 'Which preconfigured Stub-Set?',
			options: $options,
			// todo validate in options
			hint   : 'Predefined Stub-Sets will help you create Paxsy with your preferred Package Layout',
		);
	}

	protected static function stubsList(): array
	{
		$options = [];
		foreach (config('paxsy.stub_sets', []) as $name => $data) {
			$desc = $data['comment'] ?? '';
			if ($desc) {
				$desc = ' ('.$desc.')';
			}
			$options[$name] = $name.$desc;
		}

		return $options;
	}

	/**
	 * @return string
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public static function suggestExistingPackages(): string
	{
		// todo table move outside
		$options = Output::getPackages();
		if (! $options) {
			return '';
		}
		table(headers: ['name', 'path'], rows: $options);

		$pa                     = Output::getPackageList();
		$pa[PaxsyCommand::QUIT] = PaxsyCommand::QUIT;
		$packageCollection      = new Collection($pa);

		// todo suggestion with default laravel /app directory
		return suggest(
			'package to call a make: command',
			options : fn($value) => $packageCollection
				->filter(fn($title) => str_contains(Str::lower($title), Str::lower($value)))
				->all(),
			scroll  : 10,
			validate: function(string $name) use ($pa) {
				return in_array($name, $pa) ? null : 'Package "'.$name.'" does not exist';
			},
			hint    : 'Select an existing Package and select a make command that will be called against your package',
		);
	}

	/**
	 * Available Make-Commands
	 * Todo Check which commands are implemented in Packages, otherwise mark as "not tested"
	 * Todo if Command selecting, try to get the available options from the Laravel-Core-Component to give the User more experience
	 *
	 * @param string $label
	 *
	 * @return bool|string
	 */
	public static function suggestMakeCommands(string $label): bool|string
	{
		$commandList                     = Output::availableMakeCommands();
		$commandList[PaxsyCommand::QUIT] = PaxsyCommand::QUIT;

		// hint
		$hint = ! config('paxsy.gui_interactions') ?
			'set config [paxsy.gui_interactions => true ] to get all creation options as menu' : '';

		return suggest(
			$label,
			options : fn($value) => (new Collection($commandList))
				->filter(fn($title) => str_contains(Str::lower($title), Str::lower($value)))
				->all(),
			scroll  : 10,
			validate: function(string $name) use ($commandList) {
				return in_array($name, $commandList) ? null : 'make command  "'.$name.'" does not exist';
			},
			hint    : $hint,
		);
	}
}
