<?php

$menu['admin'][0]['menu'][] = [
	'nombre' 	=> 'Generator',
	'direccion' => '#Generator',
	'icono' 	=> 'fa fa-gear',
	'menu' 		=> [
		[
			'nombre' 	=> 'Tablas',
			'direccion' => 'generator/table',
			'icono' 	=> 'fa fa-user'
		],
		[
			'nombre' 	=> 'Generador',
			'direccion' => 'generator',
			'icono' 	=> 'fa fa-users'
		]
	]
];