<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel Online' => app()->version()];
});

require __DIR__.'/auth.php';
