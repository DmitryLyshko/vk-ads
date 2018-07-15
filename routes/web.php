<?php

Route::get('/', function () {
    return redirect('accounts');
})->middleware('auth');

// Ads routes
Route::get('/accounts', 'AccountsController@index')->middleware('auth')->name('accounts');
Route::get('/{account_name}/{id}', 'CampaignsController@index')->middleware('auth')->name('campaigns');
Route::get("/{account_name}/{account_id}/{campaign_name}/{campaign_id}", 'CampaignsController@ads')->middleware('auth')->name('ads');

// Auth routes
Auth::routes();
Route::match(['get', 'post'], '/social_auth', 'Auth\LoginController@authVk')->name('social_auth');
Route::get('/logout', 'Auth\LoginController@logoutUser')->name('logout');

