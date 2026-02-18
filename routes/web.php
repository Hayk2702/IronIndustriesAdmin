<?php

use App\Http\Controllers\AboutCompanyController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '{locale?}', 'middleware' => ['multiLang']], function () {

    Route::get('/', function () {
        return redirect(app()->getLocale() . '/dashboard');
    });

    Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {

        Route::group(['middleware' => ['loggable']], function () {

            Route::resource('/', HomeController::class)->names('dashboard');

            Route::get('sendTestNot', [HomeController::class, 'sendTestNot'])->name('sendTestNot');

            Route::post('/setLocale/{lang}', [HomeController::class, 'setLocale'])->name('setLocale');

            Route::get('abort403', [HomeController::class, 'abort403'])->name('abort403');

            Route::get('getRoles', [HomeController::class, 'getRoles'])->name('getRoles');

            Route::get('getPermissions', [HomeController::class, 'getPermissions'])->name('getPermissions');

            Route::resource('users', UserController::class)->names('users');

            Route::resource('about-company', AboutCompanyController::class)->names('about-company');

            Route::resource('about-us', AboutUsController::class)->names('about-us');

            Route::resource('services', ServiceController::class)->names('services');

            Route::delete('services/{service}/images/{image}', [ServiceController::class, 'deleteImage'])->name('services.images.delete');

            Route::resource('products', ProductController::class)->names('products');

        });

    });

    Auth::routes();
});



/*
Route::post('photo', [StreamController::class, 'photo'])->name("photo");

Route::post('/startStreamEntrance', [StreamController::class, 'startStreamEntrance'])->name("startStreamEntrance");

Route::post('/startStreamExit', [StreamController::class, 'startStreamExit'])->name("startStreamExit");

Route::post('/stopStream', [StreamController::class, 'stopStream'])->name("stopStream");*/
