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
//use DB;


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
//use DB;
use Modules\Movil\Model\ApiUsuario;
use Illuminate\Support\Facades\Auth;

Route::group(['middleware' => 'cors'], function(){
	Route::group(['prefix' => 'movil'], function () {
	    Route::get('/', function () {
	        dd('Este es el Modulo Movil index page. Bienvenido!');
	    });
	    Route::post('validarUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
	    	$data["correo"] = $request->correo;
			$data["password"] =  $request->password;
			
			$usuario = DB::table('api_usuario')
				->where('correo', $request->correo)
				->first();

			if ($usuario && password_verify($request->password, $usuario->password)) {
				return ['s' => 's'];
			}
			
			return ['s' => 'n', 
					'msj' => 'La combinacion de Usuario y Clave no Concuerdan.', 
					'correo' => $request->correo
					];
			
	    });
	    Route::post('guardarUsuario', function(){
	    	$postdata = file_get_contents("php://input");
			$request = json_decode($postdata);
			$id =  $request->id;
			$usuarioR = $request->usuario;
			$datos = array(
				'usuario' => $request->usuario,
				'password' => bcrypt($request->password),
	            'perfil_id' => 7,
	            'correo' => $request->correo,
	            'telefono' => $request->telefono,
	            'sexo' =>  $request->sexo
			);
			\DB::beginTransaction();
			    try {
			    	$Usuario = $id == 0 ? new ApiUsuario() : ApiUsuario::find($id);
					$Usuario->fill($datos);
		            $Usuario->save();
			     
				} catch (QueryException $e) {
		            \DB::rollback();
		            return $e->getMessage();
			    }
			\DB::commit();
			    return [
		            's' => 's', 
		            'msj' => 'Registro Guardado con Exito!!', 
		            "id" => $Usuario->id, "usuario" => $Usuario->usuario
		        ];
	    });
	    Route::post('ComprobarCorreoUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$encontrado = \DB::table('api_usuario')->where('correo', $request->correo)->get();
			if(count($encontrado) > 0){
				return ['s' => 's', 'msj' => 'Usuario Valido!!!', 'id' => $encontrado[0]->id];
			}
			return ['s' => 'n', 'msj' => 'El Correo del Usuario no Concuerda.', 'correo' => $request->correo];
			
	    });
	    Route::post('RestaurarContraseUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
	    	if ($request->contrasenanueva <> $request->contrasenarepetir)
	    		return ['s' => 'n', 'msj' => 'Las Contraseñas no Concuerdan!!!'];

			\DB::beginTransaction();
		    	try{
		    		DB::table('api_usuario')
			            ->where('id', $request->id)
			            ->update(array('password' => bcrypt($request->contrasenanueva)));
		    	} catch (QueryException $e) {
		            \DB::rollback();
		            return $e->getMessage();
		        }
	        \DB::commit();
	        return ['s' => 's', 'msj' => 'Contraseña Restablecida con Exito!!!'];
		});



		Route::post('CompruebaUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$Cantidad = \DB::table('api_usuario')
					        ->where('usuario', $request->usuario)
					        ->count();
			if($Cantidad > 0)		        
				return ['s' => 'n', 'msj' => 'El Usuario '. $request->usuario .' ya Exite!!!. ', 'cantidad' => $Cantidad ];
			
			return ['s' => 's', 'msj' => 'El Usuario '. $request->usuario .' está Disponible!!!. ', 'cantidad' => $Cantidad ];
			
	    });

	    Route::post('EliminarUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
	    	if(ApiUsuario::find($request->id) == false)
	    		return ['s' => 'n', 'msj' => 'El Usuario No Existe!!!. '];

	    	\DB::beginTransaction();
		    	try{
		    		ApiUsuario::destroy($request->id);
		    		ApiUsuario::withTrashed()->find($request->id)->forceDelete();
		    	} catch (QueryException $e) {
		            \DB::rollback();
		            return $e->getMessage();
		        }
	        \DB::commit();
			return ['s' => 's', 'msj' => 'El Usuario '.$request->usuario.' Fue Eliminado con Exito!!!. '];
		});
	    
	
	    /*************************************************/
	 	Route::post('TesEstiloUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$usuario = strtolower($request->usuario);
			$password =  $request->password;
		});

		Route::post('Categorias', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$categorias = DB::table('tipo_prenda')->get();
			return ['s' => 's', 'categorias' => $categorias];
		});

	 	/****************************************************/
	 	Route::post('CargarPrendasUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
	    	$id = 0;
	    	if($request->id == 0 || $request->id == '' )
	    		return ['s' => 'n', 'msj' => 'El Usuario No Existe!!!. '];
	    	if($request->url == '' )
	    		return ['s' => 'n', 'msj' => 'Seleccione una Prenda!!!. '];
	    	if($request->tipo_prenda_id == '' )
	    		return ['s' => 'n', 'msj' => 'Seleccione el Tipo Prenda!!!. '];
	    	
	    	$rgb = [];
	    	//dd($request);
	    	$rgb = rgb($request->color); 
	    	$datos = [
				'usuario_id' => $request->id,
				'url'=> $request->url,
	            'favorito' =>$request->favorito,
	            'tipo_prenda_id' => $request->tipo_prenda_id, ///categoria de prenda
	            'tipo_prenda_detalle_id' => $request->tipo_prenda_detalle_id, ///guardar el id de la prenda del detalle
	            'hexadecimal' => $request->color,
	            'r' => $rgb['r'],
	            'g' => $rgb['g'],
	            'b' => $rgb['b']
			];
				

			DB::beginTransaction();
		    	try{
		    		$id = DB::table('prenda_usuario')->insertGetId($datos);
		    	} catch (QueryException $e) {
		            \DB::rollback();
		            return $e->getMessage();
		        }
	        DB::commit();
	        return ['s' => 's', 'msj' => 'la Prenda fue Guardada con Exito!!!. ', 'id' => $id];
		});
		/****************************************************************/
		/****************************************************************/
	 	Route::post('TesEstiloUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$usuario = strtolower($request->usuario);
			$password =  $request->password;
		});
		Route::post('GenerarCombinaciones', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$usuario_id = strtolower($request->usuario_id);
			$prenda_id =  $request->prenda_id;
			$colorprendaRGB = DB::select('select cast(a.r || a.g || a.b as int ) as color , 0 id,  a.tipo_prenda_id, a.tipo_prenda_detalle_id, cast(a.r as int)r, cast(a.g as int)g, cast( a.b as int) b from prenda_usuario a where id = '.$prenda_id.' and usuario_id = '.$usuario_id.'');
				
			$PrendasAsociadas = PrendasAsociadasalaPrendaPrincipal($colorprendaRGB[0]->tipo_prenda_id);
			if ($config_id = buscarConfiCombinacionIgual($colorprendaRGB[0]->color)){
				
			}else{
				$config_id = buscarConfiCombinacionesParecidas($colorprendaRGB[0]->tipo_prenda_id,  $colorprendaRGB[0]->tipo_prenda_detalle_id, $colorprendaRGB);
			}
			$prendasSecundarias = buscarPrendasSecundariasGuardadas($config_id, $PrendasAsociadas);


		});
	 	/****************************************************/
	 	Route::post('ListarPrendasUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$usuario_id = strtolower($request->id);
			$prendas = 	DB::table('prenda_usuario')
							->select(	'prenda_usuario.id', 'prenda_usuario.usuario_id', 
										'prenda_usuario.url', 'tipo_prenda.descripcion as categoria_prenda',
										'tipo_prenda_detalle.descripcion as tipo_prenda'
									)
			          		->leftJoin('tipo_prenda', 'tipo_prenda.id', '=', 'prenda_usuario.tipo_prenda_id')
			          		->leftJoin('tipo_prenda_detalle', 'tipo_prenda_detalle.id', '=', 'prenda_usuario.tipo_prenda_detalle_id')
			             	->where('prenda_usuario.usuario_id', $usuario_id)
			             	->orderBy('tipo_prenda_detalle.id')
			             	->groupBy(	'tipo_prenda_detalle.id', 
			             				'prenda_usuario.id', 'prenda_usuario.usuario_id', 
										'prenda_usuario.url', 'tipo_prenda.descripcion',
										'tipo_prenda_detalle.descripcion'
									)
					        ->get();
		               
			return ['s' => 's', 'msj' => 'Prendas!!!. ', 'prendas' => $prendas];
		});
		
	 	Route::post('EliminarPrendasUsuario', function(){
	    	$postdata = file_get_contents("php://input");
	    	$request = json_decode($postdata);
			$usuario_id = strtolower($request->id);
			$prenda_id = strtolower($request->prenda_id);
			$eliminar = DB::table('prenda_usuario')
							->where('usuario_id', $usuario_id)
							->where('id', $prenda_id)
							->delete();
			if ($eliminar)
				return ['s' => 's', 'msj' => 'Prenda Eliminada con Exito!!!. '];



		});
	});

});
function PrendasAsociadasalaPrendaPrincipal($tipo_prenda_id){
	$accesorios_prendas = DB::select('select a.descripcion,
													 b.tipo_prenda_id_relacion as id,
				       								(select x.descripcion from tipo_prenda x where x.id = b.tipo_prenda_id_relacion)
													from tipo_prenda a, tipos_prendas_relacion b
													where a.id = b.tipo_prenda_id
													      and b.tipo_prenda_id = '. $tipo_prenda_id .''
												);
	$categoriasPrendas = [];
	foreach ($accesorios_prendas as $accesorios_prendas) {
		$categoriasPrendas[] = $accesorios_prendas->id;
	} 
	return $categoriasPrendas;
}
function buscarConfiCombinacionIgual($color){
	$buscarColorIgual = DB::select(

		'
		SELECT TABLA.color, TABLA.id FROM (SELECT cast(c.r || c.g || c.b as int) as color, a.id
			FROM config_combinaciones a, config_tipos_prendas_princ_detalle b, config_colores_prendas_princ c
			WHERE 	a.id = b.config_combina_id
				and a.prenda_princ_id = b.prenda_princ_id
				and a.id = c.config_combina_id
				and b.config_combina_id = c.config_combina_id
				and a.prenda_princ_id = 1	 
				and b.tipo_prenda_detalle_id = 1   
				order by c.r ,c.g , c.b
			)TABLA WHERE TABLA.color = '.$color.'



		'

		);
	return $buscarColorIgual;
}

function buscarConfiCombinacionesParecidas($id_cat_prenda_princ, $tipo_prenda_detalle_id, $colorprendaRGB){

	$Rdesde  = (int)($colorprendaRGB[0]->r - 10);
	$Rhasta  = (int)($colorprendaRGB[0]->r + 10);
	$Gdesde  = (int)($colorprendaRGB[0]->g - 10);
	$Ghasta  = (int)($colorprendaRGB[0]->g + 10);
	$Bdesde  = (int)($colorprendaRGB[0]->b - 10);
	$Bhasta  = (int)($colorprendaRGB[0]->b + 10);

	$sql = 'SELECT DISTINCT TABLA.color, TABLA.id, tabla.r, tabla.g, tabla.b FROM (	SELECT cast(c.r || c.g || c.b as int) as color, a.id, cast(c.r as int)r, cast(c.g as int)g, cast( c.b as int) b
				FROM config_combinaciones a, config_tipos_prendas_princ_detalle b, config_colores_prendas_princ c
				WHERE 	a.id = b.config_combina_id
					and a.prenda_princ_id = b.prenda_princ_id
					and a.id = c.config_combina_id
					and b.config_combina_id = c.config_combina_id
					and a.prenda_princ_id = '.$colorprendaRGB[0]->tipo_prenda_id.'	 
					and b.tipo_prenda_detalle_id = '. $tipo_prenda_detalle_id .'
					order by c.r ,c.g , c.b)TABLA
			WHERE TABLA.r >= '.$Rdesde.' and TABLA.r <= '.$Rhasta.'
				  AND TABLA.g >= '.$Gdesde.' and TABLA.g <= '.$Ghasta.'
				  AND TABLA.b >= '.$Bdesde.' and TABLA.b <= '.$Bhasta.'
			';
	$BuscarCombinaciones = DB::select($sql);
	
		if ($BuscarCombinaciones > 0){
			$combinaciones = $BuscarCombinaciones;
			$combinaciones = collect($combinaciones);
			$combinacionesOrder = $combinaciones->sortBy('color');
			//dd($combinacionesOrder );
		}
	return $combinacionesOrder ;
}
function buscarPrendasSecundariasGuardadas($confi_id, $PrendasAsociadas) {
  $concat = "";
  foreach ($PrendasAsociadas as $Categoriaid) {
  	$concat .=  $Categoriaid . ", ";
  }
  
  $inCategorias = rtrim($concat, ", ");
   
  	foreach ($confi_id as $config ) {
   		$DetallePrendasVestier = buscarDetallesPrendasSecundariasVestier($config, $inCategorias);
	  	dd($DetallePrendasVestier);

   	}
}
function buscarDetallesPrendasSecundariasVestier($config, $inCategorias){
	
	$coloresPrendas = DB::select('


	    select a.prenda_sec_id, a.tipo_prenda_detalle_id, b.hexadecimal, b.r, b.g, b.b 
		from config_tipos_prendas_sec_detalle a, config_colores_prendas_sec b
		where a.config_combina_id = b.config_combina_id
		      	and a.prenda_sec_id = b.tipo_prenda_id
		     	and a.config_combina_id =  '.$config->id.'
	      		and a.prenda_sec_id in('.$inCategorias.')

	');

	$detallePrendasBuscar = DB::select('
					select * from prenda_usuario where (tipo_prenda_id, tipo_prenda_detalle_id) in (
							select a.prenda_sec_id, a.tipo_prenda_detalle_id 
							from config_tipos_prendas_sec_detalle a
							where a.config_combina_id = '.$config->id.'
							      and a.prenda_sec_id in('.$inCategorias.')

							    )
		  	');
	
	foreach ($detallePrendasBuscar as $detallePrendasBuscar) {
	  $concatPrendasVestier[] =  $detallePrendasBuscar;
	}
	  
	 return $concatPrendasVestier;
}
function PrendasPorOcasiones($ocasiones_id, $usuario_id){
			$prendas = 	DB::table('prenda_usuario')
							->select(	'prenda_usuario.id', 'prenda_usuario.usuario_id', 
										'prenda_usuario.url', 'tipo_prenda.descripcion as tipo_prenda',
										'ocasiones.descripcion as ocasion'
									)
		              		->leftJoin('tipo_prenda', 'tipo_prenda.id', '=', 'prenda_usuario.tipo_prenda_id')
		                 	->leftJoin('ocasiones', 'ocasiones.id', '=', 'prenda_usuario.ocasiones_id')
		                	->where('prenda_usuario.usuario_id', $usuario_id)
		                	->where('prenda_usuario.ocasiones_id', $ocasiones_id)
		                	->get();
		                
	return $prendas;	                
}
function rgb($hex = ''){
        $hex = str_replace("#", "", $hex);
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        $rgb = array('r' => $r, 'g' => $g, 'b' => $b);
        return $rgb; 
}
//{{router}}
Route::group(['prefix' => 'catColores'], function() {
		Route::get('/', 				'catColoresController@index');
		Route::get('buscar/{id}', 		'catColoresController@buscar');

		Route::post('guardar',			'catColoresController@guardar');
		Route::put('guardar/{id}', 		'catColoresController@guardar');

		Route::delete('eliminar/{id}', 	'catColoresController@eliminar');
		Route::post('restaurar/{id}', 	'catColoresController@restaurar');
		Route::delete('destruir/{id}', 	'catColoresController@destruir');

		Route::post('cambio', 			'catColoresController@cambio');
		Route::get('arbol', 			'catColoresController@arbol');
		Route::get('datatable', 		'catColoresController@datatable');
});

Route::group(['prefix' => 'Estaciones'], function() {
		Route::get('/', 				'EstacionesController@index');
		Route::get('buscar/{id}', 		'EstacionesController@buscar');

		Route::post('guardar',			'EstacionesController@guardar');
		Route::put('guardar/{id}', 		'EstacionesController@guardar');

		Route::delete('eliminar/{id}', 	'EstacionesController@eliminar');
		Route::post('restaurar/{id}', 	'EstacionesController@restaurar');
		Route::delete('destruir/{id}', 	'EstacionesController@destruir');

		Route::post('cambio', 			'EstacionesController@cambio');
		Route::get('arbol', 			'EstacionesController@arbol');
		Route::get('datatable', 		'EstacionesController@datatable');
});
Route::group(['prefix' => 'Ocasiones'], function() {
		Route::get('/', 				'OcasionesController@index');
		Route::get('buscar/{id}', 		'OcasionesController@buscar');

		Route::post('guardar',			'OcasionesController@guardar');
		Route::put('guardar/{id}', 		'OcasionesController@guardar');

		Route::delete('eliminar/{id}', 	'OcasionesController@eliminar');
		Route::post('restaurar/{id}', 	'OcasionesController@restaurar');
		Route::delete('destruir/{id}', 	'OcasionesController@destruir');

		Route::post('cambio', 			'OcasionesController@cambio');
		Route::get('arbol', 			'OcasionesController@arbol');
		Route::get('datatable', 		'OcasionesController@datatable');
});
Route::group(['prefix' => 'TonoPiel'], function() {
		Route::get('/', 				'TonoPielController@index');
		Route::get('buscar/{id}', 		'TonoPielController@buscar');

		Route::post('guardar',			'TonoPielController@guardar');
		Route::put('guardar/{id}', 		'TonoPielController@guardar');

		Route::delete('eliminar/{id}', 	'TonoPielController@eliminar');
		Route::post('restaurar/{id}', 	'TonoPielController@restaurar');
		Route::delete('destruir/{id}', 	'TonoPielController@destruir');

		Route::post('cambio', 			'TonoPielController@cambio');
		Route::get('arbol', 			'TonoPielController@arbol');
		Route::get('datatable', 		'TonoPielController@datatable');
});
Route::group(['prefix' => 'TipoPrenda'], function() {
		Route::get('/', 				'TipoPrendaController@index');
		Route::get('buscar/{id}', 		'TipoPrendaController@buscar');

		Route::post('guardar',			'TipoPrendaController@guardar');
		Route::put('guardar/{id}', 		'TipoPrendaController@guardar');

		Route::delete('eliminar/{id}', 	'TipoPrendaController@eliminar');
		Route::post('restaurar/{id}', 	'TipoPrendaController@restaurar');
		Route::delete('destruir/{id}', 	'TipoPrendaController@destruir');

		Route::post('cambio', 			'TipoPrendaController@cambio');
		Route::get('arbol', 			'TipoPrendaController@arbol');
		Route::get('datatable', 		'TipoPrendaController@datatable');
});
Route::group(['prefix' => 'ColoresImponenEstacion'], function() {
		Route::get('/', 				'ColoresImponenEstacionController@index');
		Route::get('buscar/{id}', 		'ColoresImponenEstacionController@buscar');

		Route::post('guardar',			'ColoresImponenEstacionController@guardar');
		Route::put('guardar/{id}', 		'ColoresImponenEstacionController@guardar');

		Route::delete('eliminar/{id}', 	'ColoresImponenEstacionController@eliminar');
		Route::post('restaurar/{id}', 	'ColoresImponenEstacionController@restaurar');
		Route::delete('destruir/{id}', 	'ColoresImponenEstacionController@destruir');

		Route::post('cambio', 			'ColoresImponenEstacionController@cambio');
		Route::get('arbol', 			'ColoresImponenEstacionController@arbol');
		Route::get('datatable', 		'ColoresImponenEstacionController@datatable');
});
Route::group(['prefix' => 'ApiEstilos'], function() {
		Route::get('/', 				'ApiEstilosController@index');
		Route::get('buscar/{id}', 		'ApiEstilosController@buscar');

		Route::post('guardar',			'ApiEstilosController@guardar');
		Route::put('guardar/{id}', 		'ApiEstilosController@guardar');

		Route::delete('eliminar/{id}', 	'ApiEstilosController@eliminar');
		Route::post('restaurar/{id}', 	'ApiEstilosController@restaurar');
		Route::delete('destruir/{id}', 	'ApiEstilosController@destruir');

		Route::post('cambio', 			'ApiEstilosController@cambio');
		Route::get('arbol', 			'ApiEstilosController@arbol');
		Route::get('datatable', 		'ApiEstilosController@datatable');
});
Route::group(['prefix' => 'ApiPreguntasEstilos'], function() {
		Route::get('/', 				'ApiPreguntasEstilosController@index');
		Route::get('buscar/{id}', 		'ApiPreguntasEstilosController@buscar');

		Route::post('guardar',			'ApiPreguntasEstilosController@guardar');
		Route::put('guardar/{id}', 		'ApiPreguntasEstilosController@guardar');

		Route::delete('eliminar/{id}', 	'ApiPreguntasEstilosController@eliminar');
		Route::post('restaurar/{id}', 	'ApiPreguntasEstilosController@restaurar');
		Route::delete('destruir/{id}', 	'ApiPreguntasEstilosController@destruir');

		Route::post('cambio', 			'ApiPreguntasEstilosController@cambio');
		Route::get('arbol', 			'ApiPreguntasEstilosController@arbol');
		Route::get('datatable', 		'ApiPreguntasEstilosController@datatable');
});
Route::group(['prefix' => 'Telas'], function() {
		Route::get('/', 				'TelasController@index');
		Route::get('buscar/{id}', 		'TelasController@buscar');

		Route::post('guardar',			'TelasController@guardar');
		Route::put('guardar/{id}', 		'TelasController@guardar');

		Route::delete('eliminar/{id}', 	'TelasController@eliminar');
		Route::delete('eliminarimagen/{id}', 'TelasController@eliminarimagen');
		Route::post('restaurar/{id}', 	'TelasController@restaurar');
		Route::delete('destruir/{id}', 	'TelasController@destruir');

		Route::post('cambio', 			'TelasController@cambio');
		Route::get('arbol', 			'TelasController@arbol');
		Route::get('datatable', 		'TelasController@datatable');
		Route::post('subir',		 	'TelasController@subir');
		Route::post('eliminarimagen',	'TelasController@eliminarimagen'); 
});
Route::group(['prefix' => 'Texturaprenda'], function() {
		Route::get('/', 				'TexturaprendaController@index');
		Route::get('buscar/{id}', 		'TexturaprendaController@buscar');

		Route::post('guardar',			'TexturaprendaController@guardar');
		Route::put('guardar/{id}', 		'TexturaprendaController@guardar');

		Route::delete('eliminar/{id}', 	'TexturaprendaController@eliminar');
		Route::post('restaurar/{id}', 	'TexturaprendaController@restaurar');
		Route::delete('destruir/{id}', 	'TexturaprendaController@destruir');

		Route::post('cambio', 			'TexturaprendaController@cambio');
		Route::get('arbol', 			'TexturaprendaController@arbol');
		Route::get('datatable', 		'TexturaprendaController@datatable');
});
Route::group(['prefix' => 'Turnos'], function() {
		Route::get('/', 				'TurnosController@index');
		Route::get('buscar/{id}', 		'TurnosController@buscar');

		Route::post('guardar',			'TurnosController@guardar');
		Route::put('guardar/{id}', 		'TurnosController@guardar');

		Route::delete('eliminar/{id}', 	'TurnosController@eliminar');
		Route::post('restaurar/{id}', 	'TurnosController@restaurar');
		Route::delete('destruir/{id}', 	'TurnosController@destruir');

		Route::post('cambio', 			'TurnosController@cambio');
		Route::get('arbol', 			'TurnosController@arbol');
		Route::get('datatable', 		'TurnosController@datatable');
});
Route::group(['prefix' => 'ConfigCombinaciones'], function() {
		Route::get('/', 				'ConfigCombinacionesController@index');
		Route::get('buscar/{id}', 		'ConfigCombinacionesController@buscar');
		Route::get('RelacionDetalleRopa/{id}', 		'ConfigCombinacionesController@RelacionDetalleRopa');

		Route::post('guardar',			'ConfigCombinacionesController@guardar');
		Route::put('guardar/{id}', 		'ConfigCombinacionesController@guardar');

		Route::delete('eliminar/{id}', 	'ConfigCombinacionesController@eliminar');
		Route::post('restaurar/{id}', 	'ConfigCombinacionesController@restaurar');
		Route::delete('destruir/{id}', 	'ConfigCombinacionesController@destruir');

		Route::post('cambio', 			'ConfigCombinacionesController@cambio');
		Route::get('arbol', 			'ConfigCombinacionesController@arbol');
		Route::get('datatable', 		'ConfigCombinacionesController@datatable');
		Route::post('buscarPrendasAsociadas', 'ConfigCombinacionesController@buscarPrendasAsociadas', function(){
			return ['s' => 's', 'msj' => 'ggg'];
		});

});
Route::group(['prefix' => 'TipoPrendaDetalle'], function() {
		Route::get('/', 				'TipoPrendaDetalleController@index');
		Route::get('buscar/{id}', 		'TipoPrendaDetalleController@buscar');

		Route::post('guardar',			'TipoPrendaDetalleController@guardar');
		Route::put('guardar/{id}', 		'TipoPrendaDetalleController@guardar');

		Route::delete('eliminar/{id}', 	'TipoPrendaDetalleController@eliminar');
		Route::post('restaurar/{id}', 	'TipoPrendaDetalleController@restaurar');
		Route::delete('destruir/{id}', 	'TipoPrendaDetalleController@destruir');

		Route::post('cambio', 			'TipoPrendaDetalleController@cambio');
		Route::get('arbol', 			'TipoPrendaDetalleController@arbol');
		Route::get('datatable', 		'TipoPrendaDetalleController@datatable');
		
});

