<?php

use App\Http\Controllers\Installer\ConfigureDatabaseController;
use App\Http\Controllers\Installer\CreateAdminController;
use App\Http\Controllers\Installer\FinishInstallationController;
use App\Http\Controllers\Installer\VerifyPurchaseCodeController;
use App\Http\Middleware\Installer\EnsureWizardProcessIsValid;
use Illuminate\Support\Facades\Route;

Route::prefix('/install')
    ->as('installer.')
    ->middleware(['web', EnsureWizardProcessIsValid::class])
    ->group(function () {
        Route::view('', 'installer.index')->name('index');
        Route::view('/requirements', 'installer.requirements')->name('requirements');
        Route::view('/database', 'installer.database')->name('database');
        Route::post('/database', ConfigureDatabaseController::class)->name('database.configure');
        Route::view('/admin', 'installer.admin')->name('admin');
        Route::post('/admin', CreateAdminController::class)->name('admin.create');
        Route::view('/finish', 'installer.finish')->name('finish');
        Route::post('/finish', FinishInstallationController::class)->name('finish.clean-up');
    });
