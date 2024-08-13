<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Support\Str;

class MakeComponent extends ComponentMakeCommand
{
    use TraitModularize;

    public function handle()
    {
        parent::handle();

        if ($this->package()) {
            $this->line("<options=bold,reverse;fg=green>Copy the Tag into other blade views </> 🤙\n");
            $this->line(sprintf('<options=bold;fg=green>TAG:</> %s', $this->bladeTag()));
        }
    }

    protected function buildClass($name): string
    {
        if (!$this->package() || $this->option('inline')) {
            return parent::buildClass($name);
        }

        // otherwise paxsy
        $alienStub     = $this->alienBuildClass($name);
        $vName         = $this->getView();
        // there was an update in livewire(v3.5.4) that requires this line
        $componentName = Str::startsWith($vName, 'components.') ? $vName : 'components.' . $vName;

        $viewPart = sprintf("view('%s')",
                            $this->package()
                                 ->getName() . '::' . $componentName);

        // relevant for custom packages
        return str_replace(
            ['DummyView', '{{ view }}'],
            $viewPart,
            $alienStub,
        );
    }

    protected function bladeTag(): string
    {
        return sprintf(
            '<x-%s-%s />',
            $this->package()
                 ->getName(),
            $this->getView(),
        );
    }

    /**
     * Todo move up, that is the core method of the laravel generatorCommand::buildClass($name)
     *
     * @param $name
     *
     * @return string
     * @throws FileNotFoundException
     */
    protected function alienBuildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                    ->replaceClass($stub, $name)
        ;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function viewPath($path = ''): string
    {
        return $this->package()
                    ?->intoPackagePath("resources/views/$path") ??
            parent::viewPath($path);
    }
}
