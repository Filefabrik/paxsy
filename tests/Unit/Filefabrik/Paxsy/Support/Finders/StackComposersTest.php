<?php
/*
 * Copyright (c) 2024-2026 filefabrik.com
 */



declare(strict_types=1);




use Filefabrik\Paxsy\Support\Finders\StackComposers;
use Filefabrik\Paxsy\Support\Stack;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;

beforeEach(function() {
    removePackageStack();
});
test(
    'find with buggy composer json file',
    function() {
        Log::shouldReceive('error')
           ->once()
           ->withArgs(fn($message) => str_contains($message, 'Syntax error'))
        ;
        makePackageByArtisanCommand($this);

        $defaultPackage = defaultTestPackage();
        file_put_contents($defaultPackage->packageBasePath().'/composer.json', '{ ');

        $foundPackages = StackComposers::findPackages(new Stack(currentStackName(), app(), new Filesystem()));
        expect($foundPackages->count())->toBe(0);
    },
);
