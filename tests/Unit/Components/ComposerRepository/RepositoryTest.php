<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */

use Filefabrik\Paxsy\Components\ComposerRepository\Repository;

it(
	'repository body',
	function() {
		$parsed = Repository::body('app-paxsy-testing/that-is-a-package-path');

		expect($parsed)->toBe('{
  "type": "path",
  "url": "app-paxsy-testing/that-is-a-package-path",
  "options": {
    "symlink": true
  }
}');
	}
);
