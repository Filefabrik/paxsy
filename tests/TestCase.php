<?php declare(strict_types=1);

namespace Filefabrik\Paxsy\Tests;

use Filefabrik\Paxsy\Tests\Support\TestCaseTrait;

require_once __DIR__.'/testing_helper.php';
/**
 * Testing paxsy only in standalone installation.
 * Feature testing will not work properly.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
	abstract class TestCase extends \Orchestra\Testbench\TestCase
	{
		use TestCaseTrait;
	}
} else {
	/**
	 * Testing paxsy in a laravel installation
	 * Unit Testing and Feature Testing will work
	 */
	abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
	{
		// todo test in live habitat
		use TestCaseTrait;
	}
}
