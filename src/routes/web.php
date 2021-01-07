<?php
use Controlink\Winmax4\Http\Controllers\ArticleController;
use Controlink\Winmax4\Http\Controllers\FamilyController;

Route::get('/getArticles', [ArticleController::class, 'get']);
Route::get('/getFamilies', [FamilyController::class, 'get']);
Route::get('/saveSubFamilies', [FamilyController::class, 'saveSubFamilies']);
