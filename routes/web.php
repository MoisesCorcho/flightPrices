<?php

use App\Livewire\AlertDetails;
use App\Livewire\FlightResults;
use App\Livewire\FlightSearch;
use App\Livewire\MyAlerts;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('search');
});

Route::get('/search', FlightSearch::class)->name('search');
Route::get('/results', FlightResults::class)->name('results');
Route::get('/alerts', MyAlerts::class)->name('alerts.index');
Route::get('/alerts/{id}', AlertDetails::class)->name('alerts.show');
Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
