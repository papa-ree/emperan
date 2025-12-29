<?php

use Illuminate\Support\Facades\Route;
use Bale\Emperan\Controllers\MediaController;

Route::get('/media/{path}', [MediaController::class, 'show'])
    ->where('path', '.*')
    ->name('media.show');