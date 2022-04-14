<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DBController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

//column routes
Route::prefix('columns')->name('columns.')->group(function () {            
    Route::post('/', [ColumnController::class, 'store']);
    Route::delete('/{id}', [ColumnController::class, 'destroy']);
    Route::get('/', [ColumnController::class, 'index']);
});

//card routes
Route::prefix('cards')->name('cards.')->group(function () {            
    Route::post('/', [CardController::class, 'store']);
    Route::put('/{id}', [CardController::class, 'update']);
});


Route::get('/export-db', [DBController::class, 'exportDB']);