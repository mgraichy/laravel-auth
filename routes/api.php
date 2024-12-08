<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\VideoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('/videos', [VideoController::class, 'getVideos']);
    Route::get('/video-strings', [VideoController::class, 'getVideoStrings']);
    Route::get('/comments', [CommentController::class, 'getComments']);
});
