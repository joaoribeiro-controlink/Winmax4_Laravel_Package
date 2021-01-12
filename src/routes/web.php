<?php
use Controlink\Winmax4\Http\Controllers\ArticleController;
use Controlink\Winmax4\Http\Controllers\FamilyController;
use Controlink\Winmax4\Http\Controllers\TaxController;

Route::get('/getArticles', [ArticleController::class, 'get']);
Route::get('/getFamilies', [FamilyController::class, 'get']);
Route::get('/saveSubFamilies', [FamilyController::class, 'saveSubFamilies']);
Route::get('/getTaxes', [TaxController::class, 'get']);
