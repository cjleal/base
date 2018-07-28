var aplicacion, $form, tabla;
$.ajaxSetup({
	headers: { 
		'X-CSRF-TOKEN' : $('meta[name=csrf-token]').attr('content')
	},
	complete:function(x,e,o){
		$("#cargando").animate({opacity:0}, {queue:false, complete:function(){
			$(this).css({display: 'none'});
		}});
	},
	error: function(r){
		var res = r.responseJSON, html = "";

		for (var i in res) {
			html += res[i].join("<br />") + "<br />";
		}

		new PNotify({
			title: 'Error de validacion',
			text: html,
			type: 'error',
			hide: true
		});
	},
	timeout: 0,
	cache: false
});
$(function() {
	aplicacion = new app('formulario', {
		'limpiar' : function(){
			tabla.fnDraw();
			$('.combinar').html("");
			$('.table1 tbody tr').each(function(x, y){
				if(x > 1)
					$(this).remove();

				$('input[name="color[]"]').css('background-color', '#ffffff');

			});
		},
		'buscar' : function(r){
			
			eliminarFilasTablaPrinc();
			$.each(r.colores_prendas_princ, function(ll, v){
				var $rgv = "(" + v.r + ", " + v.g + ", " + v.b +" ) ";
				
				if(ll > 0){
					var $fila = $("#tabla-clon-principal tr");
					$('input[name="color[]"]', $fila).val(v.hexadecimal);
					$('.table1 .agregarColor').click();
					$( ".colores input[name='color[]" ).eq(ll).css('background-color', 'rgb' + $rgv).prop( "readonly", "readonly" );
				}else{
					$('input[name="color[]"]', '').val(v.hexadecimal);
					$( ".colores input[name='color[]" ).eq(ll).css('background-color', 'rgb' + $rgv).prop( "readonly", "readonly" );
				}
			});
			if(typeof r.colores_prendas_sec !== 'undefined' ){
				$('div.combinar').html(r.colores_prendas_sec);
				$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
					$(this).css('background-color', ev.color.toHex());
				});
				$('.table2').on('click', '.agregar', function(){
					var $fila = $("#tabla-clon tr").clone(),
					    prenda_id = $(this).attr('prenda');

					$(this).parents('.table2').find('tbody').append($fila);

					$("input[name='descripcionCombina[][]']", $fila).attr("name", "descripcionCombina[" + prenda_id + "][]");
					
					$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
					    $(this).css('background-color', ev.color.toHex());
					});
				});
				$('.table2').on('click', '.eliminar', function(){
					var table = $(this).parents('table2');
					
					if (table.find('tbody tr').length == 1){
						return;
					}
					$(this).parents('tr').remove();
				});
				$('.example-getting-started').multiselect();
				if(typeof r.check !== 'undefined' ){
					$.each(r.check, function(l, v){
						$('input[type="checkbox"][value="'+v.id+'"]', 'table[idprenda="'+v.prenda_sec_id+'"]').click();
					})
				}
			}
		}
	});

	$form = aplicacion.form;

	tabla = datatable('#tabla', {
		ajax: $url + "datatable",
		columns: [
					{"data": "nombre", "name": "tipo_prenda.descripcion"},
					{"data": "descripcion", "name": "descripcion"}
				]
	});
	
	$('#tabla').on("click", "tbody tr", function(){
		aplicacion.buscar(this.id);
	});
	$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
		$(this).css('background-color', ev.color.toHex());
	});
	$("#prenda_princ_id").on('change', function(){
		if($(this).val() ==='')
			return false;
		buscarPrendasAsociadas($(this).val());
	});
	$('#prenda_princ_id').change(function(){
		$('.agregarColor').attr('id_principal', $(this).val());
	});
	$('.table1').on('click', '.agregarColor', function(){
		var $fila = $("#tabla-clon-principal tr").clone();
		$(this).parents('.table1').find('tbody').append($fila);
		$($fila).attr('class', 'colores');
		$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
		    $(this).css('background-color', ev.color.toHex());
		});
	});
	$('.table1').on('click', '.eliminarPrincipal', function(){
		var table = $(this).parents('table1');
		if ($('.table1').find('tbody tr').length == 2){
			return ;
		}
		$(this).parents('tr').remove();
	});
	$('label[for=" []"]').remove();

	$('#prenda_princ_id').on('change', function(){
		aplicacion.selectCascada($(this).val(), 'tipoprendadetalle_id','RelacionDetalleRopa');
	});

});
function eliminarFilasTablaPrinc(){
	$('.table1 tbody tr').each(function(x, y){
		if(x > 1)
			$(this).remove();

		$('input[name="color[]"]').css('background-color', '#ffffff');

	});
	
}
function buscarPrendasAsociadas($id){
	$.ajax($url + 'buscarPrendasAsociadas', {
		type: "POST",
		data: {
			prenda_principal : $id
		},   
		success: function(r){
			if (r.s === "n"){
				$("#boton").prop("disable", false);
				aviso('No se puedo Autenticar', 'error');
				return false;
			}
			$('div.combinar').html(r.tbody);
			$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
				$(this).css('background-color', ev.color.toHex());
			});
			$('.table2').on('click', '.agregar', function(){
				var $fila = $("#tabla-clon tr").clone(),
				    prenda_id = $(this).attr('prenda');

				$(this).parents('.table2').find('tbody').append($fila);

				$("input[name='descripcionCombina[][]']", $fila).attr("name", "descripcionCombina[" + prenda_id + "][]");
				
				$('.colorpicker-default').colorpicker().on('changeColor', function(ev){
				    $(this).css('background-color', ev.color.toHex());
				});
			});
			$('.table2').on('click', '.eliminar', function(){
				var table = $(this).parents('table2');
				
				if (table.find('tbody tr').length == 1){
					return;
				}
				$(this).parents('tr').remove();
			});
			 $('.example-getting-started').multiselect();
		},
		error : function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR, textStatus, errorThrown);
		}
	});

}