<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => Config::get('admin.prefix')], function() {

	Route::group(['prefix' => 'generator'], function() {
		Route::get('/', 				'GeneratorController@index');
		Route::get('campos/{tabla}',	'GeneratorController@campos');
		Route::post('guardar',			'GeneratorController@guardar');
		Route::get('modelos',			'GeneratorController@modelos');

		Route::group(['prefix' => 'table'], function() {
			Route::get('/', 		'TableController@index');
			Route::post('guardar',	'TableController@guardar');
		});
	});

	//{{route}}
});