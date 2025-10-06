<?php

use App\Http\Controllers\KeywordController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class)->only(['index', 'store']);
Route::post('tasks/{id}/toggle', [TaskController::class, 'toggle']);

Route::apiResource('keywords', KeywordController::class)->only(['index', 'store']);