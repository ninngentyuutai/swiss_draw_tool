<?php
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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AggregateController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PromoterController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\UserController;

// Route::post('/user/login', [UserController::class, 'api_login']);

//Route::get('/user/login', [HelloController::class, 'index']);

Route::get('/aggregate/all', [AggregateController::class, 'index']);

Route::get('/promoter/next_match', [PromoterController::class, 'next_match']);


Route::get('/match/reserve', [MatchController::class, 'reserve']);
Route::get('/match/victory_report', [MatchController::class, 'victory_report']);

Route::get('/registration/register_end_participant', [ParticipantController::class, 'register_end_participant']);

Route::get('/create_tournament/create_end_tournament', [PromoterController::class, 'create_end_tournament']);


Route::get('/batch/start_tournament', [PromoterController::class, 'start_tournament']);





Route::get('/', function () {
    return view('welcome');
});
