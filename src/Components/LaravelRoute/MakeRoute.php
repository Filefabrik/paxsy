<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Components\LaravelRoute;

use Filefabrik\Paxsy\Console\Commands\Admin\Inputs;
use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Filefabrik\Paxsy\Console\Commands\Make\TraitPackagizer;
use Filefabrik\Paxsy\Support\Str\ReplaceArray;
use Filefabrik\Paxsy\Support\Stubs\Facade;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * custom command to create a route
 */
class MakeRoute extends GeneratorCommand
{
    use TraitPackagizer;
    use TraitOptions;

    /**
     * @var string
     */
    protected $name = 'make:route';

    /**
     * @return int|bool
     */
    public function handle(): int|bool
    {
        if (!$this->stagePackage()) {
            return self::FAILURE;
        }

        $this->optionsBody();
        // todo already exist
        $path = $this->intoPackagePath('/routes/web.php');

        if (file_exists($path) && !$this->option('force')) {
            $this->error('Route "' . $path . '" already exists!');
            $this->info('Set the -f flag to force create the route file again.');

            return self::FAILURE;
        }

        $this->makeDirectory($path);

        return $this->stagePutRoute($path);
    }

    protected function stagePackage()
    {
        if (!$this->package()) {
            $packageName = Inputs::suggestPackageName($this->name);

            if (!$packageName) {
                $this->error('No packages found!');

                return false;
            }
            // reset the load state
            $this->resetPackage()->input->setOption('package', $packageName);
        }

        return true;
    }

    protected function stagePutRoute(string $path)
    {
        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceContent($stub);

        $putted = $this->files->put($path, $stub) ? self::SUCCESS : self::FAILURE;

        if ($putted === self::SUCCESS) {
            $this->info('Route is written to:' . $path);
            $this->info(url($this->package()
                                 ->getName() . '/index'));
        }
        else {
            // @codeCoverageIgnoreStart
            $this->error('Not written Route to:' . $path);
            // @codeCoverageIgnoreEnd
        }

        return $putted;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/web.stub.php';
    }

    /**
     * @param string $stub
     *
     * @return string
     */
    protected function replaceContent(string $stub): string
    {
        // todo config stub location/set ...whatever

        $vars = Facade::variables(
            vendorPackageNames: $this->package()
                                     ->getVendorPackageNames(),
        )
                      ->renderVariables()
        ;

        return ReplaceArray::searchReplace($stub, $vars);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the file even if the route file already exists'],
        ];
    }
}
