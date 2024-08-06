<?php declare(strict_types=1);
// @codeCoverageIgnoreStart
use Illuminate\Support\Facades\Route;

/**
 * Paxsy-Stub
 * Test double route
 */
Route::prefix('StubPackageName')
     ->group(function() {
         Route::get('index',
             function() {
                 return 'Paxsy Route in: ' . ' StubRelPackageDir';
             });
     })
;

// use StubVendorNamespace\StubPackageNamespace\Http\Controllers\StubPackageNamespaceController;

// Route::get('/StubPackageNamePlural', [StubPackageNamespaceController::class, 'index'])->name('StubPackageNamePlural.index');
// Route::get('/StubPackageNamePlural/create', [StubPackageNamespaceController::class, 'create'])->name('StubPackageNamePlural.create');
// Route::post('/StubPackageNamePlural', [StubPackageNamespaceController::class, 'store'])->name('StubPackageNamePlural.store');
// Route::get('/StubPackageNamePlural/{StubPackageNameSingular}', [StubPackageNamespaceController::class, 'show'])->name('StubPackageNamePlural.show');
// Route::get('/StubPackageNamePlural/{StubPackageNameSingular}/edit', [StubPackageNamespaceController::class, 'edit'])->name('StubPackageNamePlural.edit');
// Route::put('/StubPackageNamePlural/{StubPackageNameSingular}', [StubPackageNamespaceController::class, 'update'])->name('StubPackageNamePlural.update');
// Route::delete('/StubPackageNamePlural/{StubPackageNameSingular}', [StubPackageNamespaceController::class, 'destroy'])->name('StubPackageNamePlural.destroy');
// @codeCoverageIgnoreEnd