<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Components\Livewire;

use Filefabrik\Paxsy\Support\Package;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Symfony\Component\Finder\SplFileInfo;

/**
 * todo check the whole parsing
 * Methods used from InterNachi. They are not logical sorted. a lot of case/namespace shaking that in this kind not need
 */
class StringHelper
{
	/**
	 * ./app-paxsy/the-test-package/src/Livewire/FastAndFurious.php
	 *
	 * @param SplFileInfo $splFileInfo
	 * @param Package     $package
	 *
	 * @return string the-test-package::fast-and-furious
	 * @see Livewire::component
	 */
	public static function toLivewireComponentName(SplFileInfo $splFileInfo, Package $package): string
	{
		return $package->getVendorPackageNames()
					   ->getPackage()
					   ->toName().'::'.self::componentNameFromSpl($splFileInfo)
		;
	}

	/**
	 * ./app-paxsy/the-test-package/src/Livewire/FastAndFurious.php
	 *
	 * @param SplFileInfo $splFileInfo
	 *
	 * @return string fast-and-furious
	 */
	protected static function componentNameFromSpl(SplFileInfo $splFileInfo): string
	{
		return Str::of($splFileInfo->getRelativePath())
				  ->explode('/')
				  ->filter()
				  ->push($splFileInfo->getBasename('.php'))
				  ->map([Str::class, 'kebab'])
				  ->implode('.')
		;
	}

	/**
	 * @param string $packageName the-test-package
	 * @param string $inputName   "fast and furious"
	 *
	 * @return string $packageName::livewire.$inputName
	 */
	public static function tagFromInputName(string $packageName, string $inputName): string
	{
		$className = StringHelper::inputNameToClassName($inputName);

		return self::bladeTag($packageName, self::classToComponentName($className));
	}

	/**
	 * @param $inputName
	 *
	 * @return string
	 */
	public static function inputNameToClassName($inputName): string
	{
		return Str::of($inputName)
				  ->split('/[.\/(\\\\)]+/')
				  ->map([Str::class, 'studly'])
				  ->join(DIRECTORY_SEPARATOR)
		;
	}

	/**
	 * Shows in console info TAG: <livewire:the-test-package::fast-and-furious/>
	 *
	 * @param string $packageName
	 * @param string $componentName
	 *
	 * @return string
	 */
	public static function bladeTag(string $packageName, string $componentName): string
	{
		return "<livewire:$packageName::$componentName/>";
	}

	protected static function classToComponentName(string $className): string
	{
		return Str::lower(\Filefabrik\Bootraiser\Raisilence\Livewire::compileLivewireComponentName($className));
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public static function componentNameFromClassName($name): string
	{
		return Str::of($name)
				  ->explode('/')
				  ->filter()
				  ->map([Str::class, 'kebab'])
				  ->implode('.')
		;
	}

	public static function viewNameFromClass(string $packageName, string $className): string
	{
		return StringHelper::viewName($packageName, self::classToComponentName($className));
	}

	/**
	 * render() return view('the-test-package::livewire.fast-and-furious');
	 *
	 * @param string $packageName
	 * @param string $componentName
	 *
	 * @return string
	 */
	public static function viewName(string $packageName, string $componentName): string
	{
		return "$packageName::livewire.$componentName";
	}
}
