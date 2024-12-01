<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/jobs/', [JobController::class, 'index']);
Route::get('/jobs/create', [JobController::class, 'create']);
