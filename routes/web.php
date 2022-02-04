<?php

use Composite\ServerHealth\Http\Controllers\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/healthservice', [HealthCheckController::class, 'index'])->name('health.index');
Route::get('/logs', [HealthCheckController::class, 'logs'])->name('health.logs');
