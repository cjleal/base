var aplicacion, $form, tabla, fila;
$(function() {
	aplicacion = new app('formulario', {
		'buscar' : function(evnt){
			$('tbody tr', '.table').remove();
			$(evnt.Preguntas).each(function(x, y){
				fila = clonar();
				console.log(fila);
				$("input[name='descripcionP[]']", fila).val(y.descripcion);
			})
		},
		'limpiar' : function(){
			tabla.fnDraw();
			$('tbody tr', '.table').remove();
		}
	});

	$form = aplicacion.form;

	tabla = datatable('#tabla', {
		ajax: $url + "datatable",
		columns: [{"data":"descripcion","name":"descripcion"}]
	});
	
	$('#tabla').on("click", "tbody tr", function(){
		aplicacion.buscar(this.id);
	});
	$('.table').on('click', '.agregar', function(){
		clonar();
	});

	//$('tbody tr', '.table').remove();


	$('.table').on('click', '.eliminar', function(){
		var table = $(this).parents('table');
		/*
		if (table.find('tbody tr').length == 1){
			return;
		}
		*/
		$(this).parents('tr').remove();
	});
});
function clonar(){
	var $fila = $("#tabla-clon tr").clone();
	$('.table .agregar').parents('.table').find('tbody').append($fila);
	return $fila;
}