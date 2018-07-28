$(function() {
	$('#formulario').submit(function() { 
		$(this).ajaxSubmit({ 
			'url' : $url + 'actualizar',
			'data' : {
				'_method': 'put'
			},
			'type' : 'POST',
			'success': function(r){
				aviso(r);
			}          
		}); 
		return false; 
	});

	$('#formulario_clave').submit(function() { 
		$(this).ajaxSubmit({ 
			'url' : $url + 'clave',
			'data' : {
				'_method': 'put'
			},
			'type' : 'POST',
			'success': function(r){
				aviso(r);

				$('#password, #password_confirmation').val('');
			}
		}); 
		return false; 
	});
	//fin de contraseña

	$("#upload_link").on('click', function(e){
	    e.preventDefault();
	    $("#upload:hidden").trigger('click');
	});

	$("#upload").on('change', function(){
	    $('#formulario_imagen').ajaxSubmit({ 
			'url' : $url + 'cambio',
			'type' : 'POST',
			'success': function(r){
				aviso(r);
				var foto = r.foto + '?_=' + (new Date().getTime());
				$("#foto").attr('src', foto);
				$(".img-circle", ".top-menu").attr('src', foto);
			}
		});
	});

	
});