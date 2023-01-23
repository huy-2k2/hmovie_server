<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmojiController;
use App\Http\Controllers\NotifiController;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', function () {
    return response()->json(Category::all());
});

Route::get('/statuses', function () {
    return response()->json(Status::all());
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/comments', [CommentController::class, 'getAllByFilmId']);

Route::group(['middleware' => ['auth.api']], function () {
    Route::post('/createComment', [CommentController::class, 'createComment']);
    Route::post('/createEmoji', [EmojiController::class, 'createEmoji']);
    Route::post('/notifis', [NotifiController::class, 'getAllByUserId']);

    Route::group(['middleware' => ['notifi']], function () {
        Route::post('/markNotifiReaded', [NotifiController::class, 'markNotifiReaded']);
        Route::post('/deleteNotifi', [NotifiController::class, 'deleteNotifi']);
    });

    Route::group(['middleware' => ['comment']], function () {
        Route::post('/removeComment', [CommentController::class, 'removeComment']);
    });
});
