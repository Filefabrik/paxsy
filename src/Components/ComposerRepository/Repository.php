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
	 * @param string $repositoryPath
	 * todo display as hint for console user
	 *
	 * @return string
	 */
	public static function body(string $repositoryPath): string
	{
		$fc            = file_get_contents(__DIR__.'/stubs/repository.stub.json');
		$searchReplace = ['{{RepositoryUrl}}' => $repositoryPath];

		return ReplaceArray::searchReplace($fc, $searchReplace);
	}
}
