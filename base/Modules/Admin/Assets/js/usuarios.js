var aplicacion, $form, tabla = false, to, $arbol;

$(function() {
	aplicacion = new app("formulario", {
		'antes' : function(accion){
			if (accion !== 'guardar') return;

			//$("#permisos", $form).val(data_arbol());
		},
		'limpiar' : function(){
			
			tabla.fnDraw();
			$("#foto").prop("src", imagenDefault);
			$('#usuario').prop('readonly', false);
		},
		'buscar' : function(r){
			
			$("#foto").prop("src", r.foto);
			$('#usuario').prop('readonly', true);
			

			
		}
	});

	$("#upload_link").on('click', function(e){
	    e.preventDefault();
	    $("#upload:hidden").trigger('click');
	});

	$form = aplicacion.form;

	
	$("#telefono", $form).mask("0999-999-9999");

	$('#input_buscar').keyup(function () {
		if(to) { clearTimeout(to); }
		to = setTimeout(function () {
			$arbol.search($('#input_buscar').val());
		}, 250);
	});
	
	tabla = datatable('#tabla', {
		ajax: $url + "datatable",
		columns: [
			{ data: 'usuario', name: 'usuario' },
			{ data: 'dni', name: 'dni' },
			{ data: 'nombre', name: 'nombre' }
		]
	});
	
	$('#tabla').on("click", "tbody tr", function(){
		aplicacion.buscar(this.id);
	});
});

