<?php
use Illuminate\Http\Request;

Use App\Person;
Use App\Address;

Route::get('people', 'PersonController@index');
Route::get('people/{id}', 'PersonController@show');
Route::post('people', 'PersonController@store');
Route::put('people/{id}', 'PersonController@update');
Route::delete('people/{id}', 'PersonController@delete');

Route::get('addresses', 'AddressController@index');
Route::get('addresses/{id}', 'AddressController@show');
Route::post('addresses', 'AddressController@store');
Route::put('addresses/{id}', 'AddressController@update');
Route::delete('addresses/{id}', 'AddressController@delete');
