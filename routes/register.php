<?php

use Illuminate\Support\Facades\Route;

Route::options('{all}', function () {})
    ->where('all', '.*')
    ->middleware('cors.all');

Route::get('/', 'SpaController')->name('spa.register');

Route::post('oauth/{provider}', 'Auth\OAuthController@redirectToProvider')
    ->middleware('cors.all');
Route::get('oauth/{provider}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
Route::get('/login/oauth/{provider}/{domain}', 'SpaController');

#API routes
Route::prefix('api')->middleware(['guest:api'])->group(function () {
    Route::post('/domain/verify', 'Auth\RegisterController@verifyDomain');
    Route::post('/register', 'Auth\RegisterController@register');
});
