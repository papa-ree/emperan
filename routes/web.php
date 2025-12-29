<?php

use Illuminate\Support\Facades\Route;
use Paparee\BaleEmperan\Controllers\MediaController;

Route::get('/media/{path}', [MediaController::class, 'show'])
    ->where('path', '.*')
    ->name('media.show');