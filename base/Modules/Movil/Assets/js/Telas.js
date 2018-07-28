var aplicacion, $form, tabla, $archivo_actual = '', $archivos = {}, cordenadasImagen;
$(function() {
	aplicacion = new app('formulario', {
		'antes': function(accion){
			$("#archivos").val(jsonToString($archivos));
		},
		'limpiar' : function(){
			tabla.fnDraw();

			$archivos = {};

			$("table tbody tr", "#fileupload").remove();
		},
		'buscar':function(r){
			$("#fileupload").css("display", 'block');
			$(".estacion_id").val($("#estacion_id").val());
			$(".tela_id").val(r.id);
			if (typeof r.files !== "undefined" ){
				$("table tbody", "#fileupload").html(tmpl("template-download", r));
				$("table tbody .fade", "#fileupload").addClass('in');
			}
			var archivos = r.files;
			$archivos = {};
			if (typeof archivos !== "undefined"){
				for(var i in archivos){
					$archivos[archivos[i].id] = archivos[i].data;
				}
			}
		},
		'eliminar':function(r){
			aviso(r.msj);
		}

	});

	$form = aplicacion.form;
	$('#fileupload').fileupload({
		url: $url + 'subir',
		disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
		maxFileSize: 999000,
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
		
	}).bind('fileuploaddone', function (e, data) {
		var archivo = data.result.files[0];
		var estacion_id = $("#estacion_id").val();
		$archivos[archivo.id] = archivo.data;
		$("input[name='rutas']").val(estacion_id);

	});
	$('#fileupload').on('click', '.btn-danger', function(evn){
		evn.preventDefault();
		delete $archivos[$(this).parents('tr').data('id')];
	});
	tabla = datatable('#tabla', {
		ajax: $url + "datatable",
		columns: [{"data":"descripcion","name":"descripcion"},{"data":"estacion_id","name":"estacion_id"}]
	});
	
	$('#tabla').on("click", "tbody tr", function(){
		aplicacion.buscar(this.id);
	});
});
function dataImagen(cordenadas){
	cordenadasImagen = cordenadas;
}

function stringToJson(str){
	return $.parseJSON(str);
}

function jsonToString(json){
	return JSON.stringify(json);
}