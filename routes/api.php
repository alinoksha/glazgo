<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::post('/excel', [ArticleController::class, 'getExcel']);
Route::post('/csv', [ArticleController::class, 'getCSV']);
