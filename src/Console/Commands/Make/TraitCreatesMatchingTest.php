<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

use Filefabrik\Bootraiser\Support\Str\Namespacering;
use Filefabrik\Bootraiser\Support\Str\Pathering;
use Illuminate\Support\Str;

trait TraitCreatesMatchingTest
{
    /**
     * Create the matching test case if requested.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function handleTestCreation($path): bool
    {
        if (!$package = $this->package()) {
            return parent::handleTestCreation($path);
        }

        if (!$this->option('test') && !$this->option('pest') && !$this->option('phpunit')) {
            return false;
        }

        // first strip absolute path segments to detect

        $path              = Pathering::stripPathFromStart($package->intoPackagePath('src'), $path);
        $generatedTestName = Str::of($path)
                                ->beforeLast('.php')
                                ->append('Test')
                                ->replace(Namespacering::Divider, Pathering::Divider)
        ;

        return $this->call(
            'make:test',
            [
                    'name'      => $generatedTestName,
                    //'--package' => $package->getName(),
                    '--pest'    => $this->option('pest'),
                    '--phpunit' => $this->option('phpunit'),
                ],
        ) == 0;
    }
}
