<?php

use Composite\ServerHealth\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/healthservice', [HealthCheckController::class, 'index'])->name('health.index');
