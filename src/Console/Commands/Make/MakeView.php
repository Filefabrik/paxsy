<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Paxsy\Console\Commands\Admin\TraitOptions;
use Illuminate\Foundation\Console\ViewMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeView extends ViewMakeCommand
{
    use TraitPackagizer;
    use TraitOptions;
    use TraitCallDelegation;
    use TraitSharedViewPaths;

    protected function getPath($name): string
    {
        return $this->viewPath(
            $this->getNameInput() . '.' . $this->option('extension'),
        );
    }

    /**
     * Get the destination test case path.
     *
     * @return string
     */
    protected function getTestPath(): string
    {
        return $this->package()
                    ?->intoPackagePath(
                        Str::of($this->testClassFullyQualifiedName())
                           ->replace('\\', '/')
                           ->replaceFirst('Tests/Feature', 'tests/Feature')
                           ->append('Test.php')
                           ->value(),
                    ) ?? parent::getTestPath();
    }

    /**
     * Create the matching test case if requested.
     *
     * @param string $path
     */
    protected function handleTestCreation($path): bool
    {
        if (!$package = $this->package()) {
            return parent::handleTestCreation($path);
        }
        if (!$this->option('test') && !$this->option('pest') && !$this->option('phpunit')) {
            return false;
        }

        $ns  = $package->joinPackageNamespace($this->testNamespace());
        $tcn = $this->testClassName();
        $vtn = $this->testViewName();

        $contents = preg_replace(
            ['/\{{ namespace \}}/', '/\{{ class \}}/', '/\{{ name \}}/'],
            [$ns, $tcn, $vtn],
            File::get($this->getTestStub()),
        );
        $testDir  = dirname($this->getTestPath());
        File::ensureDirectoryExists($testDir, 0755, true);

        $result = File::put($path = $this->getTestPath(), $contents);

        $this->components->info(sprintf('%s [%s] created successfully.', 'Test', $path));

        return $result !== false;
    }
}
