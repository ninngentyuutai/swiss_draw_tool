<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AggregateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::get('/user/login',function() {
//     // return 'まずこうした場合どうなるか見てみようじゃん';
//     // return json_encode('次にこうするとどうなるか');
//     return response()->json(
//         [
//             'sample' => 'サンプル'
//         ]
//         );

// });
Route::middleware(['middleware' => 'api'])->group(function () {
    // Route::post('/user/login', [UserController::class, 'api_login']);

Route::post('/user/login',function() {
    // return 'まずこうした場合どうなるか見てみようじゃん';
    // return json_encode('次にこうするとどうなるか');
    return response()->json(
        [
            'sample' => 'サンプル'
        ]
        );

});



});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
