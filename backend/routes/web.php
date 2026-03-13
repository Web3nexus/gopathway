<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return file_get_contents(public_path('frontend.html'));
})->where('any', '^(?!api|sanctum).*$');
