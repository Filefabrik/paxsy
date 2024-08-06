# Testing-Version

todo test will not create correctly

Todo flight request wrong namespace

todo composer require geht nicht, wenn eine dependency im package fehlt

Named to Paxsy

Code-Base (relevant for devs they want to extend the Module-Package at itself)
FileSearcher for the Modular are under ./Support/Locators
* Can Search for all Modules
* Can Search for a single Module
* ModularBoot has less working code inside the boot methods
* Bootable Components (which is a need for running Modules from app-modules in the Laravel-Host) can be easily attached or particularly enhanced.
  * todo demo

todo all calls has to be with "paxsy:command" and not the class, so it is overridable

After Creating the First Livewire, route, controller ... and so on, re-boostrap the modules 

Dev Laravel 11 and SupportLivewire 3 fixing some stuff and try out

min Laravel 10 (tested with Laravel 11)
min SupportLivewire 3.4
min php 8.2

install
```shell
composer install filefabrik/booteraiser
```

todo: Listener with matching events


Works with Laravel 11 and Livewire 3
<img alt="Modular" src="art/docs/2024-03-30_06-39.webp" width="100%" />

The package in this Git Repository was renamed from `internachi/modular` to `filefabrik/modular`.

Reason is that I do not have access to `internachi/modular` Packagist-Registry.

At the Moment, I want to have a public Packagist so the current playground is usable for everyone.

https://packagist.org/packages/filefabrik/modular/

Namespaces in `filefabrik/modular` are the same as in `internachi/modular` (`Filefabrik\\Paxsy\\`)
It means, `internachi/modular` and `filefabrik/modular` can not coexist in the same Laravel installation.

`filefabrik/modular` can be merged into the `internachi/modular` with the `internachi` vendor prefix.

## composer install

* If already installed the original `internachi/modular` backup the file `/config/app-modules.php`, if it was published
  into Laravel 'internachi/modular'  `/config/app-modules.php`

Then remove the original from your composer `internachi/modular` or uninstall it via composer

```shell script
composer remove internachi/paxsy
``` 

After uninstall the original install this filefabrik version of modules

```shell script
composer install filefabrik/paxsy
``` 

current version is 3.0.3

```shell script
php artisan vendor:publish 
``` 

Please select `modular-config`

<img alt="Modular" src="art/docs/2024-03-30_06-36.webp" width="100%" />


--- original from internachi



## `internachi/modular`

<div>
	<a href="https://github.com/InterNACHI/modular/actions/workflows/phpunit.yml" target="_blank">
		<img 
			src="https://github.com/InterNACHI/modular/actions/workflows/phpunit.yml/badge.svg" 
			alt="Build Status" 
		/>
	</a>
	<a href="https://codeclimate.com/github/InterNACHI/modular/test_coverage" target="_blank">
		<img 
			src="https://api.codeclimate.com/v1/badges/dd927802d52f4f75ea6c/test_coverage" 
			alt="Coverage Status" 
		/>
	</a>
	<a href="https://packagist.org/packages/internachi/modular" target="_blank">
        <img 
            src="https://poser.pugx.org/internachi/modular/v/stable" 
            alt="Latest Stable Release" 
        />
	</a>
	<a href="./LICENSE" target="_blank">
        <img 
            src="https://poser.pugx.org/internachi/modular/license" 
            alt="MIT Licensed" 
        />
    </a>
    <a href="https://twitter.com/inxilpro" target="_blank">
        <img 
            src="https://img.shields.io/twitter/follow/inxilpro?style=social" 
            alt="Follow @inxilpro on Twitter" 
        />
    </a>
    <a href="https://any.dev/@chris" target="_blank">
        <img 
            src="https://img.shields.io/mastodon/follow/109584001693739813?domain=https%3A%2F%2Fany.dev&style=social" 
            alt="Follow @chris@any.dev on Mastodon" 
        />
    </a>
</div>

`internachi/modular` is a package system for Laravel applications. It uses
[Composer path repositories](https://getcomposer.org/doc/05-repositories.md#path) for autoloading,
and [Laravel package discovery](https://laravel.com/docs/7.x/packages#package-discovery) for package
initialization, and then provides minimal tooling to fill in any gaps.

This project is as much a set of conventions as it is a package. The fundamental idea
is that you can create “modules” in a separate `app-modules/` directory, which allows you to
better organize large projects. These modules use the existing
[Laravel package system](https://laravel.com/docs/7.x/packages), and follow existing Laravel
conventions.


- [Installation](#installation)
- [Usage](#usage)
- [Comparison to `nwidart/laravel-modules`](#comparison-to-nwidartlaravel-modules)


## Installation

To get started, run:

```shell script
composer require internachi/paxsy
``` 

Laravel will auto-discover the package and everything will be automatically set up for you.

### Publish the config

While not required, it's highly recommended that you customize your default namespace
for modules. By default, this is set to `Modules\`, which works just fine but makes it
harder to extract your package to a separate package should you ever choose to.

We recommend configuring a organization namespace (we use `"InterNACHI"`, for example).
To do this, you'll need to publish the package config:

```shell script
php artisan vendor:publish --tag=paxsy-config
```

### Create a package

Next, let's create a package:

```shell script
php artisan paxsy:package my-package 
```

Modular will scaffold up a new package for you:

```
app-modules/
  my-package/
    composer.json
    src/
    tests/
    routes/
    resources/
    database/
```

It will also add two new entries to your app's `composer.json` file. The first entry registers
`./app-modules/my-package/` as a [path repository](https://getcomposer.org/doc/05-repositories.md#path),
and the second requires `modules/my-package:*` (like any other Composer dependency).

Modular will then remind you to perform a Composer update, so let's do that now:

```shell script
composer update modules/my-package
```

### Optional: Config synchronization

You can run the sync command to make sure that your project is set up
for package support:

```shell script
php artisan modules:sync
```

This will add a `Modules` test suite to your `phpunit.xml` file (if one exists)
and update your [PhpStorm Laravel plugin](https://plugins.jetbrains.com/plugin/7532-laravel)
configuration (if it exists) to properly find your package's views.

It is safe to run this command at any time, as it will only add missing configurations.
You may even want to add it to your `post-autoload-dump` scripts in your application's
`composer.json` file.

## Usage now with bootraiser

All modules follow existing Laravel conventions, and auto-discovery
should work as expected in most cases:

- Commands are auto-registered with Artisan 
- Migrations will be run by the Migrator
- Factories are auto-loaded for `factory()`
- Policies are auto-discovered for your Models
- Blade components will be auto-discovered
- Event listeners will be auto-discovered

### Commands

#### Package Commands

We provide a few helper commands:

- `php artisan paxsy:package`  — scaffold a new package
- `php artisan modules:cache` — cache the loaded modules for slightly faster auto-discovery
- `php artisan modules:clear` — clear the package cache
- `php artisan modules:sync`  — update project packageConfigs (like `phpunit.xml`) with package settings
- `php artisan modules:list`  — list all modules

#### Laravel “`make:`” Commands

We also add a `--package=` option to most Laravel `make:` commands so that you can
use all the existing tooling that you know. The commands themselves are exactly the
same, which means you can use your [custom stubs](https://laravel.com/docs/7.x/artisan#stub-customization)
and everything else Laravel provides:

- `php artisan make:cast MyModuleCast --package=[package name]`
- `php artisan make:controller MyModuleController --package=[package name]`
- `php artisan make:command MyModuleCommand --package=[package name]`
- `php artisan make:component MyModuleComponent --package=[package name]`
- `php artisan make:channel MyModuleChannel --package=[package name]`
- `php artisan make:event MyModuleEvent --package=[package name]`
- `php artisan make:exception MyModuleException --package=[package name]`
- `php artisan make:factory MyModuleFactory --package=[package name]`
- `php artisan make:job MyModuleJob --package=[package name]`
- `php artisan make:listener MyModuleListener --package=[package name]`
- `php artisan make:mail MyModuleMail --package=[package name]`
- `php artisan make:middleware MyModuleMiddleware --package=[package name]`
- `php artisan make:model MyModule --package=[package name]`
- `php artisan make:notification MyModuleNotification --package=[package name]`
- `php artisan make:observer MyModuleObserver --package=[package name]`
- `php artisan make:policy MyModulePolicy --package=[package name]`
- `php artisan make:provider MyModuleProvider --package=[package name]`
- `php artisan make:request MyModuleRequest --package=[package name]`
- `php artisan make:resource MyModule --package=[package name]`
- `php artisan make:rule MyModuleRule --package=[package name]`
- `php artisan make:seeder MyModuleSeeder --package=[package name]`
- `php artisan make:test MyModuleTest --package=[package name]`

#### Other Laravel Commands

In addition to adding a `--package` option to most `make:` commands, we’ve also added the same
option to the `db:seed` command. If you pass the `--package` option to `db:seed`, it will look
for your seeder within your package namespace:

- `php artisan db:seed --package=[package name]` will try to call `Modules\MyModule\Database\Seeders\DatabaseSeeder`
- `php artisan db:seed --class=MySeeder --package=[package name]` will try to
  call `Modules\MyModule\Database\Seeders\MySeeder`

#### Vendor Commands

We can also add the `--package` option to commands in 3rd-party packages. The first package
that we support is Livewire. If you have Livewire installed, you can run:

- `php artisan make:livewire counter --package=[package name]`

#### Blade Components

Your [Laravel Blade components](https://laravel.com/docs/blade#components) will be
automatically registered for you under
a [component namespace](https://laravel.com/docs/9.x/blade#manually-registering-package-components).
A few examples:

| File                                                               | Component                      |
|--------------------------------------------------------------------|--------------------------------|
| `app-modules/demo/src/View/Components/Basic.php`                   | `<x-demo::basic />`            |
| `app-modules/demo/src/View/Components/Nested/One.php`              | `<x-demo::nested.one />`       |
| `app-modules/demo/src/View/Components/Nested/Two.php`              | `<x-demo::nested.two />`       |
| `app-modules/demo/resources/components/anonymous.blade.php`        | `<x-demo::anonymous />`        |
| `app-modules/demo/resources/components/anonymous/index.blade.php`  | `<x-demo::anonymous />`        |
| `app-modules/demo/resources/components/anonymous/nested.blade.php` | `<x-demo::anonymous.nested />` |

#### Customizing the Default Module Structure

When you call `paxsy:package`, Modular will scaffold some basic boilerplate for you. If you
would like to customize this behavior, you can do so by publishing the `app-modules.php`
config file and adding your own stubs.

Both filenames and file contents support a number of placeholders. These include:

- `StubBasePath`
- `StubVendorNamespace`
- `StubPackageNamespace`
- `StubPackageNameSingular`
- `StubPackageNamePlural`
- `StubPackageName`
- `StubComposerName`
- `StubMigrationPrefix`
- `StubFullyQualifiedTestCaseBase`
- `StubTestCaseBase`

## Comparison to `nwidart/laravel-modules`

[Laravel Modules](https://nwidart.com/laravel-modules) is a great package that’s been
around since 2016 and is used by 1000's of projects. The main reason we decided to build
our own package system rather than using `laravel-modules` comes down to two decisions:

1. We wanted something that followed Laravel conventions rather than using its own
   directory structure/etc.
2. We wanted something that felt “lighter weight”

If you are building a CMS that needs to support 3rd-party modules that can be dynamically
enabled and disabled, Laravel Modules will be a better fit.

On the other hand, if you're mostly interested in modules for organization, and want to
stick closely to Laravel conventions, we’d highly recommend giving InterNACHI/Modular a try! 
