<?php
use Controlink\Winmax4\Http\Controllers\ArticleController;
use Controlink\Winmax4\Http\Controllers\FamilyController;
use Controlink\Winmax4\Http\Controllers\TaxController;
use Controlink\Winmax4\Http\Controllers\WarehouseController;

Route::get('/getArticles', [ArticleController::class, 'get']);
Route::post('/createArticle', [ArticleController::class, 'create']);
Route::get('/getFamilies', [FamilyController::class, 'get']);
Route::get('/saveSubFamilies', [FamilyController::class, 'saveSubFamilies']);
Route::get('/getTaxes', [TaxController::class, 'get']);
Route::post('/getWarehouse', [WarehouseController::class, 'get'])->name('getWarehouse');
