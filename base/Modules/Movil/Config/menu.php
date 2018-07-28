<?php

$menu['movil'] = [
	[
		'nombre' 	=> 'Movil',
		'direccion' => '#Administrador',
		'icono' 	=> 'fa fa-gear',
		'menu' 		=> [
			
			[
				'nombre' 	=> 'Definiciones',
				'direccion' => '',
				'icono' 	=> 'fa fa-users',
				'menu' 		=> [
					
					[
						'nombre' 	=> 'Colores que se imponen en temporada',
						'direccion' => 'ColoresImponenEstacion',
						'icono' 	=> 'fa fa-paint-brush'
					],
					[
						'nombre' 	=> 'Categorias de Colores',
						'direccion' => 'catColores',
						'icono' 	=> 'fa fa-pencil-square-o'
					],
					[
						'nombre' 	=> 'Estaciones',
						'direccion' => 'Estaciones',
						'icono' 	=> 'fa fa-thumbs-up'
					],
					[
						'nombre' 	=> 'Tipos de Ocasiones',
						'direccion' => 'Ocasiones',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Tipos de Tonos de Piel',
						'direccion' => 'TonoPiel',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Categorias de Prendas',
						'direccion' => 'TipoPrenda',
						'icono' 	=> 'fa fa-users',
						'menu'		=> [
								[
									'nombre' 	=> 'Detalle de Prendas',
									'direccion' => 'TipoPrendaDetalle',
									'icono' 	=> 'fa fa-users'
								],


						]
					],
					[
						'nombre' 	=> 'Tipos de Estilos',
						'direccion' => 'ApiEstilos',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Preguntas según los tipos de Estilos',
						'direccion' => 'ApiPreguntasEstilos',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Telas',
						'direccion' => 'Telas',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Textura de las Prendas',
						'direccion' => 'Texturaprenda',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Turnos',
						'direccion' => 'Turnos',
						'icono' 	=> 'fa fa-users'
					],
					[
						'nombre' 	=> 'Configurar Combinaciones',
						'direccion' => 'ConfigCombinaciones',
						'icono' 	=> 'fa fa-users'
					]

				]
			]
		]
	]
];