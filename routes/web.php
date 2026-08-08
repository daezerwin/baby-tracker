<?php

use App\Http\Controllers\AgeController;
use App\Http\Controllers\BabyController;
use App\Http\Controllers\BabyPhotoController;
use App\Http\Controllers\DiaperEntryController;
use App\Http\Controllers\FeedEntryController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MilestoneEntryController;
use App\Http\Controllers\PediatricianController;
use App\Http\Controllers\SleepEntryController;
use App\Http\Controllers\WeightEntryController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('dashboard', 'dashboard')->name('dashboard');

    Route::resource('babies', BabyController::class);

    Route::prefix('babies/{baby}')->name('babies.')->group(function () {
        Route::get('age', [AgeController::class, 'show'])->name('age');
        Route::get('growth', [WeightEntryController::class, 'chart'])->name('growth');
        Route::get('guide', [GuideController::class, 'show'])->name('guide');

        Route::get('pediatrician', [PediatricianController::class, 'edit'])->name('pediatrician.edit');
        Route::put('pediatrician', [PediatricianController::class, 'update'])->name('pediatrician.update');

        Route::resource('weights', WeightEntryController::class)
            ->except(['show'])->parameters(['weights' => 'weight']);
        Route::resource('feeds', FeedEntryController::class)
            ->except(['show'])->parameters(['feeds' => 'feed']);
        Route::resource('diapers', DiaperEntryController::class)
            ->except(['show'])->parameters(['diapers' => 'diaper']);
        Route::resource('sleeps', SleepEntryController::class)
            ->except(['show'])->parameters(['sleeps' => 'sleep']);
        Route::resource('milestones', MilestoneEntryController::class)
            ->except(['show'])->parameters(['milestones' => 'milestone']);

        Route::resource('photos', BabyPhotoController::class)
            ->only(['index', 'store', 'destroy']);
        Route::patch('photos/{photo}/profile', [BabyPhotoController::class, 'setProfile'])->name('photos.profile');

        Route::get('import', [ImportController::class, 'show'])->name('import.show');
        Route::post('import/diapers', [ImportController::class, 'importDiapers'])->name('import.diapers');
        Route::post('import/feeds', [ImportController::class, 'importFeeds'])->name('import.feeds');
    });
});

require __DIR__.'/auth.php';
