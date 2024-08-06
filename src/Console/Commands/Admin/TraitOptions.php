<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Filefabrik\Paxsy\Console\Support\SolvedOptions;
use function Laravel\Prompts\multiselect;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Each make: has its own options. So display them to console
 */
trait TraitOptions
{
	/**
	 * Interact with the user before validating the input.
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return void
	 */
	protected function interact(InputInterface $input, OutputInterface $output): void
	{
		$this->optionsBody();

		parent::interact($input, $output);
	}

	/**
	 * for internal components ask interact direct if not delegated from parent
	 *
	 * @return void
	 */
	protected function optionsBody(): void
	{
		if (config('paxsy.gui_interactions') && $this->package()) {
			$opts = $this->provideOptions();
			// call once per make command

			if ($opts) {
				// show for what the option is
				$selected = multiselect('Options for '.class_basename($this).'?', $opts, [], scroll: 15);

				$options = $this->getDefinition()
								->getOptions()
				;
				// todo, chained options make:controller -> make:model, the make model must not create a controller

				foreach ($selected as $option => $value) {
					$useKey = (is_string($option)) ? $option : $value;

					if ($options[$useKey]->acceptValue()) {
						$this->input->setOption($useKey, $value);
					} else {
						// set what was given via console command
						$this->input->setOption($useKey, true);
					}
				}
			}
		}
	}

	/**
	 * for menu
	 * todo test all options
	 * todo options they are called from a call before disable in next call
	 * @return array
	 */
	protected function provideOptions(): array
	{
		// in config/paxsy.php ignores option loop create controller -> with model -> with controller

		$mustIgnore = [...(array) config('paxsy.ignore_option', []), ...SolvedOptions::solvedAsOption()];

		$opts = [];
		foreach ($this->getDefinition()
					  ->getOptions() as $option) {
			// todo if short option
			$name = $option->getName();
			if (! in_array($name, $mustIgnore)) {
				$opts[$option->getName()] = '--'.$option->getName().' '.$option->getDescription();
			}
		}

		return $opts;
	}
}
