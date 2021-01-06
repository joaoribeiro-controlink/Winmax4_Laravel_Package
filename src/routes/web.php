<?php
use controlink\winmax4\Http\Controllers\ArticleController;

Route::get('/getArticles', [ArticleController::class, 'get']);
