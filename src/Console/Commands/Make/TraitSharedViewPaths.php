<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */

declare(strict_types=1);

namespace Filefabrik\Paxsy\Console\Commands\Make;

trait TraitSharedViewPaths
{
    /**
     * @param string $path
     *
     * @return string
     */
    protected function viewPath($path = ''): string
    {
        if (!$package = $this->package()) {
            return parent::viewPath($path);
        }

        return $package
            ->intoPackagePath("resources/views/$path")
        ;
    }
}
