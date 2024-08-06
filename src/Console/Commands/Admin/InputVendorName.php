<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Filefabrik\Paxsy\Support\Stack;
use Filefabrik\Paxsy\Support\Stringularity;
use Illuminate\Console\Command;

readonly class InputVendorName
{
	private Command $command;

	private Stack $stack;

	public function __construct(...$config)
	{
		[$this->command, $this->stack] = $config;
	}

	public static function handle(...$config): ?Stringularity
	{
		return (new self(...$config))->handleVendorName();
	}

	/**
	 * @return Stringularity|null
	 */
	protected function handleVendorName(): ?Stringularity
	{
		$vendorName = null;
		if (! config('paxsy.ui_vendor_select')) {
			$vendorName = $this->staticVendorName();
		}
		if (! $vendorName) {
			$this->command->info('1. enter the vendor-name');
			$vendorName = Inputs::suggestComposerVendors(
				$this->stack,
				(string) config('paxsy.ui_default_vendor')
			);
		}

		if (! $vendorName) {
			$this->command->error('Could not handle your vendor-name');

			return null;
		}

		return new Stringularity($vendorName);
	}

	/**
	 * @return string|null
	 */
	private function staticVendorName(): ?string
	{
		if (! $defaultVendorName = (string) config('paxsy.ui_default_vendor')) {
			$this->command->error('You have to Configure the /config/app-paxsy.php#ui_default_vendor');

			return null;
		}

		$this->command->line('"'.$defaultVendorName.'" (used default from /config/app-paxsy.php#ui_default_vendor)');

		return $defaultVendorName;
	}
}
