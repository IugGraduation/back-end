<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:sanctum'])->prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/verify_code', [\App\Http\Controllers\Api\AuthController::class, 'verifyCode']);
    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/offer/store', [\App\Http\Controllers\Api\Offer\OfferController::class, 'addOffer']);
    Route::get('/offer/{uuid}/edit', [\App\Http\Controllers\Api\Offer\OfferController::class, 'editOffer']);
    Route::post('/offer/update', [\App\Http\Controllers\Api\Offer\OfferController::class, 'updateOffer']);
    Route::delete('/offer/{uuid}/delete', [\App\Http\Controllers\Api\Offer\OfferController::class, 'deleteOffer']);

    Route::post('/post/store', [\App\Http\Controllers\Api\Post\PostController::class, 'addPost']);
    Route::get('/post/{uuid}/edit', [\App\Http\Controllers\Api\Post\PostController::class, 'editPost']);
    Route::post('/post/update', [\App\Http\Controllers\Api\Post\PostController::class, 'updatePost']);
    Route::delete('/post/{uuid}/delete', [\App\Http\Controllers\Api\Post\PostController::class, 'deletePost']);

});
Route::get('home', [\App\Http\Controllers\Api\Home\HomeController::class, 'home']);
Route::get('see_all', [\App\Http\Controllers\Api\Home\HomeController::class, 'seeAll']);
Route::get('post/{uuid}', [\App\Http\Controllers\Api\Home\HomeController::class, 'detailsPost']);
Route::get('category/{uuid}/posts', [\App\Http\Controllers\Api\Home\HomeController::class, 'getPostsFromCategory']);

Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
