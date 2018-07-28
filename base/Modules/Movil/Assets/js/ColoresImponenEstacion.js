var aplicacion, $form, tabla;
$(function() {
	aplicacion = new app('formulario', {
		'antes' : function(evnt){
			if (evnt == 'limpiar') {
				return false;
			}
		},
		'limpiar' : function(){
			tabla.fnDraw();
		}
	});

	$form = aplicacion.form;

	tabla = datatable('#tabla', {
		ajax: $url + "datatable",
		columns: [{"data":"descripcion","name":"descripcion"},{"data":"estaciones_id","name":"estaciones_id"}]
	});
	
	$('.table').on('click', '.agregar', function(){
		var $fila = $("#tabla-clon tr").clone(),
		    estaciones_id = $(this).attr('id_estacion');

		$(this).parents('.table').find('tbody').append($fila);

		$("input[name='descripcion[]']", $fila).attr("name", "descripcion[" + estaciones_id + "][]");

		$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
		    $(this).css('background-color', ev.color.toHex());
		});
	});

	//$('tbody tr', '.table').remove();

	$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
		$(this).css('background-color', ev.color.toHex());
	}).each(function(){
		$(this).css('background-color', this.value);
	});

	$('.table').on('click', '.eliminar', function(){
		var table = $(this).parents('table');
		
		if (table.find('tbody tr').length == 1){
			return;
		}
		$(this).parents('tr').remove();
	});

	
});