<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use function Laravel\Prompts\confirm;

class MakeController extends ControllerMakeCommand
{
    use TraitPackagizer;
    use TraitCallDelegation;
    use TraitCreatesMatchingTest;

    /**
     * Build the model replacement values.
     * Will be called if a model-option was set
     *
     * @param array $replace
     *
     * @return array
     */
    protected function buildModelReplacements(array $replace): array
    {
        return $this->package() ? $this->buildModelReplacements_package($replace) :
            parent::buildModelReplacements($replace);
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        if (!$this->package()) {
            return parent::buildClass($name);
        }
        // todo if --requests do it into solved in future but not by model
        // todo in Model handle the --all option without creating controller.
        // That should modify the --all option model

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            // during options there is a --model flag,
            // the option is a select option
            // so the model is want but there is no input for the model name or selectables
            $replace = $this->buildModelReplacements_package($replace);
        }

        if ($this->option('creatable')) {
            $replace['abort(404);'] = '//';
        }

        return $this->classReplacements($replace, $name);
    }

    protected function classReplacements(array $replace, string $name)
    {
        $packageNamespace           = $this->package()
                                           ->srcPackageNamespace()
        ;
        $rootAppNamespace           = $this->rootNamespace();
        $rootAppControllerNamespace = 'App\Http\Controllers';

        $baseControllerExists = file_exists($this->getPath("{$packageNamespace}Http\Controllers\Controller"));

        if ($baseControllerExists) {
            $replace["use {$rootAppControllerNamespace}\Controller;\n"] =
                "use {$packageNamespace}\Http\Controllers\Controller;\n";
        }
        else {
            $replace[' extends Controller']                                    = '';
            $replace["use {$rootAppNamespace}\Http\Controllers\Controller;\n"] = '';
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            // has to be the parent class
            $this->replaceControllerStub($name),
        );
    }

    protected function replaceControllerStub(string $name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                    ->replaceClass($stub, $name)
        ;
    }

    /**
     * @param $replace
     *
     * @return array
     */
    protected function buildModelReplacements_package($replace): array
    {
        $relativeModel = $this->option('model');

        // don't know where the lower case results from
        if (in_array($relativeModel, ['model', true], true)) {
            // generate Model Class from Controller
            $relativeModel = Str::replaceEnd('Controller', '', $this->argument('name'));
            $this->input->setOption('model', $relativeModel);
        }

        $modelClass = $this->parseModel($relativeModel);
        $this->makeModelIfNeed($modelClass, $relativeModel);

        $replace = $this->buildFormRequestReplacements_package($replace, $modelClass);

        return $this->mergeReplacements($replace, $modelClass);
    }

    protected function mergeReplacements(array $replace, string $modelClass)
    {
        $cbn   = class_basename($modelClass);
        $cbLcf = lcfirst($cbn);

        // keep sync with ControllerMakeCommand
        return array_merge(
            $replace,
            [
                'DummyFullModelClass'   => $modelClass,
                '{{ namespacedModel }}' => $modelClass,
                '{{namespacedModel}}'   => $modelClass,
                'DummyModelClass'       => $cbn,
                '{{ model }}'           => $cbn,
                '{{model}}'             => $cbn,
                'DummyModelVariable'    => $cbLcf,
                '{{ modelVariable }}'   => $cbLcf,
                '{{modelVariable}}'     => $cbLcf,
            ],
        );
    }

    protected function makeModelIfNeed(string $modelClass, string $relativeModel): void
    {
        if (
            !class_exists($modelClass) && confirm(
                "A {$modelClass} model does not exist. Do you want to generate it?",
                true,
            )
        ) {
            $this->call('make:model', ['name' => $relativeModel]);
        }
    }

    /**
     * Build the model replacement values.
     *
     * @param array  $replace
     * @param string $modelClass
     *
     * @return array
     */
    protected function buildFormRequestReplacements_package(array $replace, string $modelClass): array
    {
        [$namespace, $storeRequestClass, $updateRequestClass] = [
            'Illuminate\\Http',
            'Request',
            'Request',
        ];

        if ($this->option('requests')) {
            $namespace = $this->package()
                              ->joinPackageNamespace('Http', 'Requests')
            ;

            [$storeRequestClass, $updateRequestClass] = $this->generateFormRequests(
                $modelClass,
                $storeRequestClass,
                $updateRequestClass,
            );
        }

        $namespacedRequests = $namespace . '\\' . $storeRequestClass . ';';

        if ($storeRequestClass !== $updateRequestClass) {
            $namespacedRequests .= PHP_EOL . 'use ' . $namespace . '\\' . $updateRequestClass . ';';
        }

        return $this->replaceFormRequests_package([$replace,
                                                   $namespace,
                                                   $namespacedRequests,
                                                   $storeRequestClass,
                                                   $updateRequestClass]);
    }

    protected function replaceFormRequests_package(array $vars): array
    {
        [$replace, $namespace, $namespacedRequests, $storeRequestClass, $updateRequestClass] = $vars;

        return array_merge(
            $replace,
            [
                '{{ storeRequest }}'            => $storeRequestClass,
                '{{storeRequest}}'              => $storeRequestClass,
                '{{ updateRequest }}'           => $updateRequestClass,
                '{{updateRequest}}'             => $updateRequestClass,
                '{{ namespacedStoreRequest }}'  => $namespace . '\\' . $storeRequestClass,
                '{{namespacedStoreRequest}}'    => $namespace . '\\' . $storeRequestClass,
                '{{ namespacedUpdateRequest }}' => $namespace . '\\' . $updateRequestClass,
                '{{namespacedUpdateRequest}}'   => $namespace . '\\' . $updateRequestClass,
                '{{ namespacedRequests }}'      => $namespacedRequests,
                '{{namespacedRequests}}'        => $namespacedRequests,
            ],
        );
    }

    /**
     * @param $model
     *
     * @return string
     */
    protected function parseModel($model): string
    {
        return $this->package() ? $this->parseModel_package($model) : parent::parseModel($model);
    }

    /**
     * @param $model
     *
     * @return string
     */
    protected function parseModel_package($model): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }
}
