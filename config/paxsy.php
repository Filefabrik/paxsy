<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

return [
	/*
	|--------------------------------------------------------------------------
	| Paxsy Directory
	|--------------------------------------------------------------------------
	|
	| If you want to install modules in a custom directory, you can do so here.
	| Keeping the default `app-modules/` directory is highly recommended,
	| though, as it keeps your modules near the rest of your application code
	| in an alpha-sorted directory listing.
	| Todo Multiple Stack-Names
	| The Only relevant config, all other configs have to be into a /config/app-modules-developers.php
	*/
	'stack_name' => 'app-paxsy',
	/*
	|--------------------------------------------------------------------------
	| UI Configuration
	|--------------------------------------------------------------------------
	| Customize your console menu as you need it
	*/
	/*
	|--------------------------------------------------------------------------
	| UI Vendor Select
	|--------------------------------------------------------------------------
	| Customize your console menu as you need it
	*/
	'ui_vendor_select' => 'completion',
	/*
	|--------------------------------------------------------------------------
	| UI Default Vendor
	|--------------------------------------------------------------------------
	| if ui_vendor_select=null this value ui_default_vendor has to be set to your vendor-name
	| if ui_vendor_select='completion' this this value ui_default_vendor can be empty or set your vendor-name. Then your default vendor-name is pre-selected.
	*/
	'ui_default_vendor' => null,
	/*
	|--------------------------------------------------------------------------
	| GUI Interactions (experimental) default:'gui_interactions' => 'false'
	|--------------------------------------------------------------------------
	| During `php artisan paxsy` create component, paxsy offers the laravel creation options
	*/
	'gui_interactions' => false,
	/*
	|--------------------------------------------------------------------------
	| Composer Executor default:'composer_execution' => 'write_only', @todo examples by screencasts
	|--------------------------------------------------------------------------
	| Paxsy offers two ways the handle the Laravel host composer.json.
	| * Write and execute -> it uses the php `shell_exec`
	| directly -> shell_exec applies the changes directly in your laravel installation
	| -- 'composer_execution' => 'shell_exec',
	|
	| If you do not want any change in the laravel host composer.json set to 'disabled'.
	| Then you have to manually write your wanted composer entries by hand and execute the changes by using the terminal
	| -- 'composer_execution' => 'disabled',
	*/
	'composer_execution' => 'shell_exec',
	/*
	|--------------------------------------------------------------------------
	| Ignore Option
	|--------------------------------------------------------------------------
	| Hide options during paxsy package component cli
	*/
	'ignore_option' => [
		'package',
	],
	/*
	|--------------------------------------------------------------------------
	| Bootable 3rdParty
	|--------------------------------------------------------------------------
	|
	| You have additional 3rd party Packages you want to boot to work with packages, link it into the boot process
	|
	| * Livewire is a full example under /src/Components/Livewire
	| todo refer to documentation
	*/
	'components' => [
		// core
		\Filefabrik\Paxsy\Components\LaravelRoute\Component::class,
		// 3rd Party
		\Filefabrik\Paxsy\Components\Livewire\Component::class,
	],
	/*
	|--------------------------------------------------------------------------
	| Stubs Sets (selectable during create Module)
	|--------------------------------------------------------------------------
	|
	| artisan package:admin 'create new Module' offers the following list.
	|
	| The Stub Sets is the logical consequence of Stubs Map and Stub-Directories
	|
	| During intensive development of Paxsy and repeatable creation task of your Paxsy, define here a set of useful combinations
	|
	| go to /config/app-modules-developers.php
	*/
	'stub_sets' => [
		'default' => [
			// comment will be shown in the console
			'comment' => 'App-Paxsy default new Module creation Stubs',
			/*
			 * Directory where to find the Stub-Files
			*/
			'directory' => 'vendor/filefabrik/paxsy/stubs',
			// from the stubs-map which contain target (when create a package)-files and source (from the stubs-directory)-files
			/*
		   |--------------------------------------------------------------------------
		   | Stubs Map (selectable during create Module)
		   |--------------------------------------------------------------------------
		   |
		   | artisan package:admin 'create new Module' offers the following list.
		   | During the Creation-Process of new Module, an output-filename (is the array key) will be created with the modified stub-file(array value)
		   |
		   | So you can create some personal creation mappings with your own stubs.
		   | Make Sure,
		   |
		   | go to /config/app-modules-developers.php
		   */
			'stubs' => [
				'directories' => ['/tests', '/database/factories', '/database/seeders'],
				'files'       => [
					'composer.json' => 'composer-stub-latest.json.stub',
					/*
					 * 'StubPackageNamespaceServiceProvider' will be replaced by 'StubPackageNamespace,'
					 * which has to be in replacementVariables mapping
					 */
					'src/Providers/StubPackageNamespaceServiceProvider.php' => 'ServiceProvider.php.stub',
					// into test directory
					'tests/StubPackageNamespaceServiceProviderTest.php' => 'ServiceProviderTest.php.stub',
					/*
					 * write .gitignore in package
					 */
					'.gitignore'         => 'gitignore-file.stub',
					'tests/TestCase.php' => 'TestCase.php.stub',
					/*
					 * Make sure 'my-stubs-readme.md' exists in the stub-directory
					 *
					 * 'README.md'     => 'my-stubs-readme.md.stub',
					 */
				],
			],

			// replacements
			'replacementMap' => [
				/* With RendererPackage */
				'package' => // The Keys can only exist one time in all replacementVariables, otherwise they will override
					['StubRelPackageDir' => 'relPackageDir',
						// absolute directory to the package
						'StubPackagePath'         => 'packagePath',
						'StubVendorNamespace'     => 'vendor.class',
						'StubPackageNamespace'    => 'package.class',
						'StubPackageNameSingular' => 'package.singular',
						'StubPackageNamePlural'   => 'package.plural',
						'StubPackageName'         => 'package.name',

						'StubComposerName' => 'composerName',
					],
				// simple text-Parser
				'text' => [
					'StubTestCaseBase' => 'TestCase',
					// in the file, the replaceable string was written with a #. it will be also replaced
					'#StubTestUseTestCase' => 'use PHPUnit\\TestCase;',
				],

			],
		],

	],
	/*
	 * Renderer Mapped
	 */
	'VariablesRenderer' => ['package' => Filefabrik\Paxsy\Support\Stubs\RendererPackage::class,
		'text'                           => Filefabrik\Paxsy\Support\Stubs\RendererText::class],
];
