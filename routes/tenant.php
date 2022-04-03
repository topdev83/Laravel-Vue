<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {


    #API routes common
    Route::middleware(['api'])->prefix('api')->group(function (){
        Route::get('config','ConfigController@get');
    });

    #API Private routes
    Route::middleware(['api', 'auth:api'])->prefix('api')->group(function () {
        Route::post('logout', 'Auth\LoginController@logout');

        Route::get('/user', 'Auth\UserController@current');
        Route::get('/auth/state','AuthStateController@get');
        Route::get('/oauth/connected', 'Auth\OAuthController@getConnected');

        #route for CRUD operations on User Model
        Route::apiResource('/users', 'UserController');

        Route::post('users/{user}/upload', 'UploadController@store');
        Route::delete('users/{user}/upload', 'UploadController@destroy');

        Route::patch('settings/profile', 'Settings\ProfileController@update');
        Route::patch('settings/password', 'Settings\PasswordController@update');
    });

    #API Public routes
    Route::middleware(['api', 'guest:api'])->prefix('api')->group(function () {
        Route::post('login', 'Auth\LoginController@login');

        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');

        Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'Auth\VerificationController@resend');

    });

    #SPA routes
    Route::middleware(['spa'])->group(function (){
        Route::get('oauth/{provider}/response', 'Auth\OAuthController@connectResponse')->name('oauth.response');
        Route::get('{path}', 'SpaController')
            ->where('path', '(.*)')
            ->where('path', '^(?!register).*$');
    });

});
