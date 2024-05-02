<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Modules\News\Http\Controllers\api\NewsController::routes();
Modules\News\Http\Controllers\api\NewsCategoryController::routes();

Modules\News\Http\Controllers\api\user\NewsController::routes();
Modules\News\Http\Controllers\api\user\NewsCategoryController::routes();

