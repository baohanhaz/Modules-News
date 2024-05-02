<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Modules\News\Http\Controllers\web\News::routes();
Modules\News\Http\Controllers\web\NewsCategory::routes();
Modules\News\Http\Controllers\web\HashTags::routes();
