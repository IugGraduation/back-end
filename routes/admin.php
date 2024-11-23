<?php

use App\Http\Controllers\Admin\Places\CityController;
use App\Http\Controllers\Admin\Places\CountryController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

//        Route::get('admin/login', function () {
//            return view('admin.auth.login');
//        });

        Route::get('admin/login',[\App\Http\Controllers\Admin\Auth\AuthController::class,'index'])->middleware('guest');
        Route::post('admin/login',[\App\Http\Controllers\Admin\Auth\AuthController::class,'login'])->name('login');

        Route::middleware('auth')->prefix('admin')->group(function () {
            Route::post('logout',[\App\Http\Controllers\Admin\Auth\AuthController::class,'logout'])->name('logout');

            Route::controller(\App\Http\Controllers\Admin\User\UserController::class)->middleware(['permission:user'])->prefix('users')->name('users.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{id}', 'updateStatus')->name('updateStatus');
                Route::get('/country/{uuid}', 'country')->name('country');
            });
            Route::controller(\App\Http\Controllers\Admin\Places\AreaController::class)->prefix('areas')->name('areas.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });
            Route::controller(CityController::class)->prefix('cities')->name('cities.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });
            Route::controller(CountryController::class)->prefix('countries')->name('countries.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });
            Route::controller(\App\Http\Controllers\Admin\Category\CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');
            });
            Route::controller(\App\Http\Controllers\Admin\Role\RolesController::class)->name('roles.')->prefix('roles')->group(function () {
                Route::delete('/{uuid}', 'destroy')->name('delete');
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{uuid}', 'updateStatus')->name('updateStatus');

            });
            Route::controller(\App\Http\Controllers\Admin\Offer\OfferController::class)->name('offers.')->prefix('offers')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/indexTable', 'indexTable')->name('indexTable');

            });
            Route::controller(\App\Http\Controllers\Admin\Post\PostController::class)->name('posts.')->prefix('posts')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{id}', 'updateStatus')->name('updateStatus');
            });
            Route::controller(\App\Http\Controllers\Admin\AdminController::class)->name('managers.')->prefix('managers')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('delete');
                Route::get('/indexTable', 'indexTable')->name('indexTable');
                Route::put('/updateStatus/{status}/{id}', 'updateStatus')->name('updateStatus');
                Route::get('/edit/{id}', 'edit')->name('edit');
            });

        });

    });
