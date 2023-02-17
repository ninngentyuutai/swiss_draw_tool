<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AggregateController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\ParticipantController;


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

Route::get('/aggregate/all', [AggregateController::class, 'index']);

Route::get('/promoter/next_match', [PromoterController::class, 'next_match']);

Route::get('/registration/register_end_participant', [ParticipantController::class, 'register_end_participant']);



Route::get('/', function () {
    return view('welcome');
});
