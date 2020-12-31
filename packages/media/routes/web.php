<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'media',
        'as' => 'media::',
        'middleware' => ['web', 'auth'],
    ],
    function (\Illuminate\Routing\Router $router) {
        Route::post('media', [\Laravolt\Media\Controllers\MediaController::class, 'store'])->name('store');
        Route::delete('media/{id}', [\Laravolt\Media\Controllers\MediaController::class, 'destroy'])->name('destroy');
    }
);
