<?php

use Illuminate\Support\Facades\Route;

// SPA Route - Serve Vue.js app (exclude API routes)
Route::get('{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');
