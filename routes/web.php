<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LanguageController;
use App\Livewire\AlertDetails;
use App\Livewire\FlightResults;
use App\Livewire\FlightSearch;
use App\Livewire\MyAlerts;
use App\Livewire\MySearches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/search', FlightSearch::class)->name('home');
    Route::get('/search-alias', function () {
        return redirect()->route('home');
    })->name('search');
    Route::get('/results', FlightResults::class)->name('results');
    Route::get('/searches', MySearches::class)->name('searches.index');
    Route::get('/alerts', MyAlerts::class)->name('alerts.index');
    Route::get('/alerts/{id}', AlertDetails::class)->name('alerts.show');

    Route::post('/fcm-token', function (Request $request) {
        $token = $request->input('token');
        $user = $request->user();

        if ($user && $token) {
            $user->deviceTokens()->updateOrCreate(['token' => $token]);

            return response()->json(['message' => 'Token saved successfully']);
        }

        return response()->json(['message' => 'User or token not found'], 404);
    });
});

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
