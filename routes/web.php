<?php

use Illuminate\Support\Facades\Route;
use Bale\Emperan\Controllers\MediaController;
use Bale\Emperan\Models\Option;

Route::get('/media/' . Option::whereName('organization_slug')->first() . '{path}', [MediaController::class, 'show'])
    ->where('path', '.*')
    ->name('media.show');