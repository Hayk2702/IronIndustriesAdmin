<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/', 'middleware' => 'blockcors'], function () {

    Route::group(['middleware' => ['loggable']], function () {

        Route::get('about-our-company', [ApiController::class, 'aboutOurCompany'])->name("about-our-company");

        Route::get('contact-us', [ApiController::class, 'contactUs'])->name("contact-us");

        Route::get('services', [ApiController::class, 'services'])->name("services");

        Route::get('products', [ApiController::class, 'products'])->name("products");

        Route::post('send-message', [ApiController::class, 'sendMessage'])->name('send-message');


    });



});


