<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['restrictIp'])->group(function () {
    Auth::routes();

    Route::get('routes', function () {
        \Artisan::call('route:list');
        return '<pre>' . \Artisan::output() . '</pre>';
    });

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/login');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Wiadomość z link aktywacyjnym wysłana!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

//Route::group(['namespace' => 'Front', 'prefix' => '{locale?}', 'where' => ['locale' => '(?!admin)*[a-z]{2}'],], function() {
Route::group(['namespace' => 'Front', 'middleware' => 'restrictIp', 'as' => 'front.'], function () {

    Route::get('/', 'IndexController@index')->name('index');
    Route::get('/aktualnosci', 'ArticleController@index')->name('article.index');
    Route::get('/aktualnosci/{slug}', 'ArticleController@show')->name('article.show');

    //Cron
    Route::get('/cron', 'Cron\IndexController@updateVOX')->name('cron.vox');


    // Static pages
    Route::get(
        'kontakt',
        'ContactController@index'
    )->name('contact.index');

    Route::post(
        '/kontakt',
        'ContactController@contact'
    )->name('contact.form');

    Route::post(
        '/kontakt/{property}',
        'ContactController@property'
    )->name('contact.property');

    Route::get(
        'inwestor',
        'Static\IndexController@investor'
    )->name('investor');

    Route::get(
        'o-inwestycji',
        'Static\IndexController@investment'
    )->name('investment');

    Route::get(
        'udogodnienia',
        'Static\IndexController@amenities'
    )->name('amenities');

    Route::get('galeria',
        'Gallery\IndexController@index'
    )->name('gallery');

    // DeveloPro
    Route::group(['namespace' => 'Developro', 'as' => 'developro.'], function () {
        // Pan
        Route::get('/apartamenty', 'InvestmentController@index')->name('investment.index');

        Route::get('/properties/filter', 'InvestmentPropertyController@filter')->name('investment.property.filter');

        // Contact form
        Route::get('/{slug}/kontakt', 'Contact\IndexController@index')->name('investment.contact');
        Route::post('/{slug}/kontakt', 'Contact\IndexController@form')->name('investment.contact.form');

        // Building
        Route::get('/apartamenty/{buildingSlug}', 'InvestmentBuildingController@index')->name('investment.building');

        // Floor
        Route::get('/apartamenty/{buildingSlug}/{floor},{floorSlug}', 'InvestmentFloorController@index')->name('investment.floor');

        // Property
        Route::get('/apartamenty/{buildingSlug}/{floorSlug}/{property},{propertySlug},{propertyFloor},{propertyRooms},{propertyArea}', 'InvestmentPropertyController@index')->name('investment.property');
    });
});
