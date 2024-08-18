<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Console\Commands\Admin;

use Filefabrik\Paxsy\Console\Support\SolvedOptions;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\suggest;

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
     * Perform actions after the user was prompted for missing arguments.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        parent::afterPromptingForMissingArguments($input, $output);
        // todo ugly
        // do not remove package
        // if model, it can be a "use an existing model" or "create a new Model"
        // factory uses a model
        foreach ($input->getOptions() as $name => $value) {
            if ($value === '__handleByInputMask__' && $name === 'model') {
                $this->suggestModelOption($name);
            }
        }
    }

    protected function suggestModelOption(string $name): void
    {
        if (method_exists($this, 'possibleModels')) {
            $command = Str::lower(Str::replaceFirst('Make', '', Str::afterLast(static::class, '\\')));
            $lbl     = sprintf('What model should this %s apply to? (Optional)', $command);
            // input mask
            $model = suggest(
                $lbl,
                $this->possibleModels(),
            );
            $this->input->setOption($name, $model);
        }
    }

    /**
     * for internal components ask interact direct if not delegated from parent
     *
     * @return void
     */
    protected function optionsBody(): void
    {
        if (config('paxsy.gui_interactions') && $this->package() && $opts = $this->provideOptions()) {
            // call once per make command
            // show for what the option is
            $selected = multiselect('Options for ' . class_basename($this) . '?', $opts, [], scroll: 15);

            $options = $this->getDefinition()
                            ->getOptions()
            ;
            $this->configureOptionValues($selected, $options);
        }
    }

    protected function configureOptionValues($selected, $options): void
    {
        // todo, chained options make:controller -> make:model, the make model must not create a controller

        foreach ($selected as $option => $value) {
            $useKey      = (is_string($option)) ? $option : $value;
            $acceptValue = $options[$useKey]->acceptValue();
            $this->configureOptionValue($option, $value, $useKey, $acceptValue);
        }
    }

    protected function configureOptionValue($option, $value, $useKey, $acceptValue): void
    {
        if ($acceptValue) {
            // todo ugly
            $setValue = (is_int($option) && $useKey === 'model') ? '__handleByInputMask__' : $value;
        }
        else {
            // set what was given via console command
            $setValue = true;
        }
        $this->input->setOption($useKey, $setValue);
    }

    /**
     * for menu
     * todo test all options
     * todo options they are called from a call before disable in next call
     *
     * @return array
     */
    protected function provideOptions(): array
    {
        // in config/paxsy.php ignores option loop create controller -> with model -> with controller

        $mustIgnore = [...(array)config('paxsy.ignore_option', []), ...SolvedOptions::solvedAsOption()];

        $opts = [];
        foreach ($this->getDefinition()
                      ->getOptions() as $option) {
            // todo if short option
            $name = $option->getName();
            if (!in_array($name, $mustIgnore)) {
                $opts[$option->getName()] = '--' . $option->getName() . ' ' . $option->getDescription();
            }
        }

        return $opts;
    }
}
