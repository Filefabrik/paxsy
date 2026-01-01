<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\Config\RectorConfig;
use Rector\Php84\Rector\FuncCall\AddEscapeArgumentRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

/**
 * vendor/bin/rector process --dry-run
 */
return static function(RectorConfig $rectorConfig): void {
    // Paths to analyze
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/config',

        __DIR__.'/tests',
    ]);
    $rectorConfig->cacheDirectory(__DIR__.'/.tmp/rector/');
    $rectorConfig->containerCacheDirectory(__DIR__.'/.tmp/');

    // Skip specific rules
    // Skip specific rules
    $rectorConfig->skip([
        CompactToVariablesRector::class,
        AddEscapeArgumentRector::class,

    ]);

    // Enable caching for Rector
    # $rectorConfig->cacheDirectory(__DIR__.'/storage/rector');
    $rectorConfig->cacheClass(FileCacheStorage::class);

    // Apply sets for Laravel and general code quality
    $rectorConfig->sets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
        SetList::CODE_QUALITY,
        SetList::PHP_84,
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,
        /*
          Additional Sets, to improve different aspects of your code.
          */
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
        #LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_IF_HELPERS,
        #   LaravelSetList::LARAVEL_STATIC_TO_INJECTION
    ]);

    $rectorConfig->rules([DeclareStrictTypesRector::class]);
    // Define PHP version for Rector
    $rectorConfig->phpVersion(PhpVersion::PHP_84);

    $rectorConfig->phpstanConfig(__DIR__.'/phpstan.neon.dist');
};
