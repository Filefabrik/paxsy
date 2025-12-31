<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

namespace Filefabrik\Paxsy\Components\ComposerRepository;

use Filefabrik\Paxsy\Support\Str\ReplaceArray;

/**
 * todo move out from here because it is only a stubs-parser
 */
class Repository
{
    /**
     * todo display as hint for console user
     */
    public static function body(string $repositoryPath): string
    {
        $fc            = file_get_contents(__DIR__.'/stubs/repository.stub.json');
        $searchReplace = ['{{RepositoryUrl}}' => $repositoryPath];

        return ReplaceArray::searchReplace($fc, $searchReplace);
    }
}
