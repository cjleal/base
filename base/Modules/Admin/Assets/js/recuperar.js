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


$(function(){
	$("#formulario").submit(function(e){
		return false;
	});

	$("input[name='nombre']").keypress(function(e){
		if (e.which == 13){
			if ($(".enviar","#formulario").attr('accion') == ''){
				buscar();
			}
			else{
				Restablecer();
			}
		}
	});

	$(".enviar","#formulario").click(function(e){
		if ($(".enviar","#formulario").attr('accion') == ''){
			buscar();
		}
		else{

			Restablecer();
		}
	});
});

function buscar(){
	if ($(".enviar","#formulario").attr('accion') !== ''){
		return false;
	}
	$.ajax($url + 'validarNombreUsuario', {
		type: "POST",
		data: {
			usuario : $("input[name='nombre']").val()
		},
		success: function(r){
			if (r.s === "n"){
				$("#boton").prop("disable", false);

				new PNotify({
					title: 'No se puedo Autenticar',
					text: r.msj,
					type: 'error',
					hide: true
				});

				return false;
			}
			$('.preguntas').css('display', 'block');
			$('label', '.pregunta1').text(r.primera);
			$('label', '.pregunta2').text(r.segunda);
			$(".enviar","#formulario").attr('accion', 'respuestas');
			//location.href = $url.replace(/\/+$/,'');
			
			return false;
		},
		error : function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR, textStatus, errorThrown);
		}
	});
}
function Restablecer(){
	if ($(".enviar","#formulario").attr('accion') == 'bloqueado')
		return false;
	$.ajax($url + 'respuestaUsuario', {
		type: "POST",
		data: {
			usuario : $("input[name='nombre']").val(),
			respuesta_pri : $("input[name='respuesta_pri']").val(),
			respuesta_seg : $("input[name='respuesta_seg']").val()
		},   
		beforeSend: function(){
	     	$(".enviar","#formulario").attr('accion', 'bloqueado');
	    },
		success: function(r){
			$(".enviar","#formulario").attr('accion', 'respuesta');
			if (r.s === "n"){
				$("#boton").prop("disable", false);
				aviso('No se puedo Autenticar', 'error');
				return false;
			}
			alert(r.msj);
			location.href = r.ruta;
			return false;
		},
		error : function(jqXHR, textStatus, errorThrown){
			console.log(jqXHR, textStatus, errorThrown);
		}
	});
}