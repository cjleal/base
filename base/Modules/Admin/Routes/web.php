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
use App\Http\Requests\Request;


Route::group(['prefix' => Config::get('admin.prefix')], function() {
	Route::get('/', 'EscritorioController@index');
	Route::group(['prefix' => 'MensajeRecuperacionController'], function() {
		Route::get('/', 				'MensajeRecuperacionController@index');
	});
	/**
	 * LoginController
	 */
	
	Route::group(['prefix' => 'login'], function() {
		Route::get('/', 				'LoginController@index');
		Route::get('salir', 			'LoginController@salir');
		Route::post('validar', 			'LoginController@validar');
		Route::get('bloquear', 			'LoginController@bloquear');
		Route::get('recuperar', 		'LoginController@recuperar');
	});
	Route::group(['prefix' => 'RestablecerClave'], function() {
		Route::get('/', 				'RestablecerClave@index');
		Route::get('salir', 			'RestablecerClave@salir');
		Route::post('validar', 			'RestablecerClave@validar');
		Route::get('bloquear', 			'RestablecerClave@bloquear');
	});

	Route::group(['prefix' => 'recuperar'], function() {
		Route::get('/', 				'RecuperarController@index');
		Route::post('validarNombreUsuario', 	'RecuperarController@validarNombreUsuario');
		Route::post('respuestaUsuario', 	'RecuperarController@respuestaUsuario');
		
	});
	/*
	 	* Perfiles
	*/

	Route::group(['prefix' => 'perfiles'], function() {
		Route::get('/', 				'PerfilesController@index');
		Route::get('buscar/{id}', 		'PerfilesController@buscar');
		Route::post('guardar', 			'PerfilesController@guardar');
		Route::put('guardar/{id}', 		'PerfilesController@guardar');
		Route::delete('eliminar/{id}', 	'PerfilesController@eliminar');
		Route::post('restaurar/{id}', 	'PerfilesController@restaurar');
		Route::delete('destruir/{id}', 	'PerfilesController@destruir');
		Route::get('arbol', 			'PerfilesController@arbol');
		Route::get('datatable', 		'PerfilesController@datatable');
	});

	/**
	 * Perfil
	 */

	Route::group(['prefix' => 'perfil'], function() {
		Route::get('/', 			'PerfilController@index');
		Route::put('actualizar', 	'PerfilController@actualizar');
		Route::put('clave', 		'PerfilController@clave');
		Route::post('cambio', 		'PerfilController@cambio');
	});

	/**
	 * Usuarios
	 */

	Route::group(['prefix' => 'usuarios'], function() {
		Route::get('/', 				'UsuariosController@index');
		Route::get('buscar/{id}', 		'UsuariosController@buscar');

		Route::post('guardar',			'UsuariosController@guardar');
		Route::put('guardar/{id}', 		'UsuariosController@guardar');

		Route::delete('eliminar/{id}', 	'UsuariosController@eliminar');
		Route::post('restaurar/{id}', 	'UsuariosController@restaurar');
		Route::delete('destruir/{id}', 	'UsuariosController@destruir');

		Route::post('cambio', 			'UsuariosController@cambio');
		Route::get('arbol', 			'UsuariosController@arbol');
		Route::get('datatable', 		'UsuariosController@datatable');
	});

	Route::group(['prefix' => 'configuracion'], function() {
		Route::get('/', 				'AppController@index');
	});
});