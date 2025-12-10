<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectAnalyticsController;

Route::get('/project-analytics', [ProjectAnalyticsController::class, 'index'])->name('analytics.index');
Route::get('/project-analytics/{slug}', [ProjectAnalyticsController::class, 'show'])->name('analytics.show');
Route::get('/project-analytics/{slug}/project/{project}', [ProjectAnalyticsController::class, 'project'])->name('analytics.project');


Route::get('/declarations', [App\Http\Controllers\DeclarationController::class, 'index'])->name('declarations.index');
Route::get('/declarations/{declaration:slug}', [App\Http\Controllers\DeclarationController::class, 'show'])->name('declarations.show');
