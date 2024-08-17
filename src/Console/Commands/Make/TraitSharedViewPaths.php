<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

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
