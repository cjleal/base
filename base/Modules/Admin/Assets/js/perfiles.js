var aplicacion, arbol_ele, to = false, tabla = false, $arbol;
$(function(){
	aplicacion = new app("formulario", {
		'antes' : function(accion){
			if (accion !== 'guardar') return;

			var data = data_arbol();
			$("#permisos").val(data.join(','));
		},
		'limpiar' : function(){
			$arbol.uncheck_all();
			tabla.fnDraw();
		},
		'buscar' : function(r){
			$arbol.uncheck_all();

			var $estructura = $arbol.get_json('#', {flat : true});

			for(var i in $estructura){
				if ($estructura[i].icon == "fa fa-folder-o" || $estructura[i].li_attr['data-direccion'].substring(0, 1) == '#' || r.permisos.indexOf($estructura[i].li_attr['data-direccion']) === -1){
					continue;
				}

				$arbol.check_node($estructura[i].id);
			}
		}
	});

	arbol();

	$('#input_buscar').keyup(function () {
		if(to) { clearTimeout(to); }
		to = setTimeout(function () {
			$arbol.search($('#input_buscar').val());
		}, 250);
	});

	tabla = datatable('#tabla', {
		ajax: $url + "datatable",
		columns: [
			{ data: 'nombre', name: 'nombre' }
		]
	});
	
	$('#tabla').on("click", "tbody tr", function(){
		aplicacion.buscar(this.id);
	});
});

function data_arbol(){
	var $estructura = $arbol.get_json('#', {flat : true}),
	$seleccionados = $arbol.get_checked(),
	$data = [],
	direccion = '';

	$('.jstree-checkbox.jstree-undetermined', '#arbol').each(function(){
		var id = $(this).parent().parent().attr('id');

		if (id == '#'){
			return;
		}

		$seleccionados.push(id);
	});

	for(var i in $estructura){
		if ($seleccionados.indexOf($estructura[i].id) === -1){
			continue;
		}

		direccion = $estructura[i].li_attr['data-direccion'];

		if (direccion != '#' && $data.indexOf(direccion) === -1){
			$data.push(direccion);
		}
	}

	return $data;
}

function arbol(){
	$.ajax({
		url : $url + 'arbol',
		success : function(r){
			$('#arbol')
			.html('')
			.jstree('destroy')
			.on('changed.jstree', function (e, data) {
				$("#padre").val(data.instance.get_node(data.selected[0]).id);
				//aplicacion.id = data.instance.get_node(data.selected[0]).id;
			})

			.jstree({
				'core' : {
					//"animation" : 0,
				    "check_callback" : true,
				    "themes" : { "stripes" : true },
				    'force_text' : true,
					"multiple" : true,
					'data' : r
				},
				"types" : {
					"default" : {
						"icon" : "fa fa-folder-o",
						"valid_children" : ["default","file"]
					},
					"todo" : {
						"icon" : "fa fa-sitemap",
						"valid_children" : []
					},
					"arch" : {
						"icon" : "fa fa-file-text-o",
						"valid_children" : []
					},
					"metodo" : {
						"icon" : "fa fa-gears",
						"valid_children" : []
					}
				},
				"checkbox" : {
					//"keep_selected_style" : false
			    },
				"plugins" : [
					"search", "checkbox", "types"
				]
			});

			$arbol = $('#arbol').jstree(true);
		}
	});
}