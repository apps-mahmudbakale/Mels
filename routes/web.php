<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectAnalyticsController;

Route::get('/project-analytics', [ProjectAnalyticsController::class, 'index']);
Route::get('/project-analytics/{slug}', [ProjectAnalyticsController::class, 'show'])->name('analytics.show');
Route::get('/project-analytics/{slug}/project/{projectSlug}', [ProjectAnalyticsController::class, 'project'])->name('analytics.project');

