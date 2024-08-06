<?php declare(strict_types=1);
/**
 * PHP version 8.2
 */
/** @copyright-header * */
beforeEach(function() {
	removePackageStack();
});
it(
	'Create the default app-packages directory',
	function() {
		$target = base_path(currentStackName());
		expect($target)
			->not()
			->toBeDirectory()
		;

		// create
		$this->artisan('paxsy:package-create')
			 ->expectsOutputToContain('<kbd>php artisan vendor:publish --tag=paxsy-config</kbd>')
			 ->expectsOutputToContain('Package Stack "'.currentStackName().'" created successfully in your laravel: "'.base_path(currentStackName()).'"!')
			 ->assertExitCode(0)
		;
		expect($target)
			->toBeDirectory()
		;

		// re-create
		$this->artisan('paxsy:package-create')
			 ->expectsOutput('Package Stack "'.currentStackName().'" already exists in your laravel: "'.base_path(currentStackName()).'"!')
			 ->assertExitCode(0)
		;
	},
);
