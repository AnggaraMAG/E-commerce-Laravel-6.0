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

Route::get('/', 'Ecommerce\FrontController@index')->name('front.index');
Route::get('/product', 'Ecommerce\FrontController@product')->name('front.product');
Route::get('/category/{slug}', 'Ecommerce\FrontController@categoryProduct')->name('front.category');
Route::get('/product/{slug}', 'Ecommerce\FrontController@show')->name('front.show_product');

Auth::routes();

Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    //Manajemen Kategory
    Route::resource('category', 'CategoryController')->except(['create', 'show']);

    //manajemen Product
    Route::resource('product', 'ProductController')->except(['show']);
    Route::get('/product/bulk', 'ProductController@massUploadForm')->name('product.bulk');
    Route::post('/product/bulk', 'ProductController@massUploadForm')->name('product.saveBulk');
});
