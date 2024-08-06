<?php declare(strict_types=1);
/**
 * PHP version 8.2
 * todo check against config-vars they are not expected
 */

/** @copyright-header * */

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * toto re-enable the testing config
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function makeOriginalConfig(): void
{
	$d          = config('paxsy.stub_sets.default');
	$origConfig = dirname(__DIR__, 2).'/paxsy.php';
	if (file_exists($origConfig)) {
		$aCfg = app()->get('config');

		config()->set('paxsy', require $origConfig);
	}
}

beforeEach(/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */ fn() => makeOriginalConfig());
it(
	'config that we will be delivered all segments',
	function() {
		expect(config('paxsy.stub_sets.default'))
			->toBeArray()
			->and(config('paxsy.stub_sets.default.stubs'))
			->toBeArray()
			->and(config('paxsy.stub_sets.default.directory'))
			->toBeString()
			->and(config('paxsy.stub_sets.default.replacementMap'))
			->toBeArray()
		;
	},
);

it(
	'modules stubsDirectory default where to store into the packs',
	function() {
		forcePaxsyConfig(false);
		expect(config('paxsy.stack_name'))->toBe('app-paxsy');
	},
);

it(
	'stub sets default',
	function() {
		[$key, $stubDepartment] = func_get_args();

		expect(config('paxsy.stub_sets.default.'.$key))->toBe($stubDepartment);
	},
)->with([
	['comment', 'App-Paxsy default new Module creation Stubs'],
	['stubs.directories', ['/tests', '/database/factories', '/database/seeders']],
	['stubs.files',
		[
			'composer.json'                                         => 'composer-stub-latest.json.stub',
			'src/Providers/StubPackageNamespaceServiceProvider.php' => 'ServiceProvider.php.stub',
			'tests/StubPackageNamespaceServiceProviderTest.php'     => 'ServiceProviderTest.php.stub',
			'.gitignore'                                            => 'gitignore-file.stub',
			'tests/TestCase.php'                                    => 'TestCase.php.stub',

		], ],
	['replacementMap',
		[/* With RendererPackage */
			'package' => // The Keys can only exist one time in all replacementVariables, otherwise they will override
				  ['StubRelPackageDir' => 'relPackageDir',
				  	// absolute directory to the package
				  	'StubPackagePath'         => 'packagePath',
				  	'StubVendorNamespace'     => 'vendor.class',
				  	'StubPackageNamespace'    => 'package.class',
				  	'StubPackageNameSingular' => 'package.singular',
				  	'StubPackageNamePlural'   => 'package.plural',
				  	'StubPackageName'         => 'package.name',
				  	'StubComposerName'        => 'composerName',
				  ],
			// simple text-Parser
			'text' => [
				'StubTestCaseBase'     => 'TestCase',
				'#StubTestUseTestCase' => 'use PHPUnit\TestCase;',
			], ], ],
]);
it(
	'stubs maps default. Files the must mapped correctly',
	function() {
		[$outputPath, $inStubsFilename] = func_get_args();

		$defaultMap = config('paxsy.stub_sets.default.stubs.files');

		expect($defaultMap[$outputPath])->toBe($inStubsFilename);
	},
)->with([
	['composer.json', 'composer-stub-latest.json.stub'],
	['src/Providers/StubPackageNamespaceServiceProvider.php', 'ServiceProvider.php.stub'],
	['.gitignore', 'gitignore-file.stub'],
]);

it(
	'original stub directories',
	function() {
		// getting the original without patching the stubs-path

		[$replaceKey, $replaceVarDepartment] = func_get_args();
		$inCfg                               = config('paxsy.stub_sets.default.'.$replaceKey);
		expect($inCfg)->toBe($replaceVarDepartment);
	},
)->with([['directory', 'vendor/filefabrik/paxsy/stubs']]);

it(
	'stub replacementMap default',
	function() {
		[$replaceKey, $replaceVarDepartment] = func_get_args();
		$inCfg                               = config('paxsy.stub_sets.default.replacementMap.package.'.$replaceKey);
		expect($inCfg)->toBe($replaceVarDepartment);
	},
)->with(
	[
		// these replacementVariables are handled by the package-processor which is automatically available for every new Module creation
		// relative in laravel
		['StubRelPackageDir', 'relPackageDir'],
		// absolute stubsDirectory to the package
		['StubPackagePath', 'packagePath'],
		['StubVendorNamespace', 'vendor.class'],
		['StubPackageNamespace', 'package.class'],
		['StubPackageNameSingular', 'package.singular'],
		['StubPackageNamePlural', 'package.plural'],
		['StubPackageName', 'package.name'],
		['StubComposerName', 'composerName'], ],
);

it(
	'components array default filled correctly',
	function() {
		$componentsCfg = config('paxsy.components');
		expect($componentsCfg)
			->toBeArray()
			->toContain(
				\Filefabrik\Paxsy\Components\LaravelRoute\Component::class,
				\Filefabrik\Paxsy\Components\Livewire\Component::class,
			)
		;
	}
);
