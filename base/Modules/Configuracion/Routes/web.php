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

    Route::group(['prefix' => 'configuracion'], function() {
		Route::get('/', 				'ConfiguracionController@index');
		Route::get('buscar/{id}', 		'ConfiguracionController@buscar');

		Route::post('guardar',			'ConfiguracionController@guardar');
		Route::put('guardar/{id}', 		'ConfiguracionController@guardar');

		Route::delete('eliminar/{id}', 	'ConfiguracionController@eliminar');
		Route::post('restaurar/{id}', 	'ConfiguracionController@restaurar');
		Route::delete('destruir/{id}', 	'ConfiguracionController@destruir');
	    Route::get('datatable', 		'ConfiguracionController@datatable');
		Route::get('configuracion', 	'ConfiguracionController@configuracion');
		Route::get('datos/{id}', 	'ConfiguracionController@getDatos');
	});
});
