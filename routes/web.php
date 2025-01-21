<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Http\Controllers\ReviewController;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Route;

/* Initial Routing */
Route::controller(AppController::class)->group(function () {
    Route::get('/', 'initialize')->name('index');
    Route::post('/logout', 'logout')->name('logout');
});
/* Home Pages */
Route::view('/home', 'pages.home')->name('pages.home');
Route::view('/start', 'pages.start')->name('pages.start');
Route::view('/error', 'pages.error')->name('pages.error');
/* Login with Google Routing */
Route::controller(GoogleSocialiteController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('google.redirect');  // redirect to google login
    Route::get('callback/google',
        'handleCallback')->name('google.callback');    // callback route after google account chosen
});
/* Review Routing */
Route::view('/question', 'pages.question')->name('pages.question');
Route::view('/care', 'pages.care')->name('pages.care');
Route::view('/review', 'pages.review')->name('pages.review');
Route::controller(ReviewController::class)->group(function () {
    Route::get('/finish', 'composeReview')->name('finish');
});
/* Error:  account NOT active */
Route::view('/notactive', 'pages.notactive')->name('pages.notactive');
Route::view('/test', 'test')->name('test');


Route::get('/send-test-email', function () {
    $data = [
        'message' => 'This is a stil  another test email sent using Laravel.',
    ];

    Mail::to('sslarsen411@gmail.com')->send(new TestEmail($data));

    return 'Image test email sent successfully!';
});
