$(function() {

	// cargar datos del formulario
	
	$.ajax({
		url : $url + 'buscar',
		data: {
			'id' : $('#id').val()
		},
		success : function(r){
			console.log(r);
 			$('#apellido').val(r[0]['apellido']);
 			$('#direccion').val(r[0]['direccion']);
 			$('#fb').val(r[0]['facebook']);
 			$('#instagram').val(r[0]['instagram']);
 			$('#tw').val(r[0]['twitter']);
 	 		$('#estado').val(r[0]['edo_civil']);
 			$('#sexo').val(r[0]['sexo']);
 			$('#id').val(r[0]['id']);
 			$('#ape').append(r[0]['apellido']);
		}
	});
	//fin cargar datos formulario
	
	//guardar datos
	var options = { 
			url : $url + 'actualizar',
			data : {
				'_method': 'put'
			},
			'type' : 'POST',
			success: function(e){
				
				var notice = new PNotify({
					text: e['msj'],
					hide: true,
					addclass: "stack-bottomright",
					stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25}
				});

				notice.get().click(function() {
				notice.remove();
				});

				setTimeout(function(){
					notice.remove();
				}, 5000);
			},
		 //clearForm: true             
		}; 

	$('#form1').submit(function() { 
		$(this).ajaxSubmit(options); 
		return false; 
	});
	//guardar datos ../

	//cambio de contraseña
	var options = { 
			url :'cambiar_clave/guardar',
			'type' : 'POST',
			success: function(e){
				
				var notice = new PNotify({
					text: e['msj'],
					hide: true,
					addclass: "stack-bottomright",
					stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25}
				});

				notice.get().click(function() {
				notice.remove();
				});

				setTimeout(function(){
					notice.remove();
				}, 5000);
			},
		 //clearForm: true             
		}; 

		$('#form2').submit(function() { 
			$(this).ajaxSubmit(options); 
			return false; 
		});
	//fin de contraseña

	$("#upload_link").on('click', function(e){
	    e.preventDefault();
	    $("#upload:hidden").trigger('click');
	});

	$("#upload").on('change', function(){
	var options2 = { 
			 url : $url + 'cambio',
			type : 'POST',
			success: function(e){
				console.log(e);
				var notice = new PNotify({
					text: e['msj'],
					hide: true,
					addclass: "stack-bottomright",
					stack: {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25}
				});

				notice.get().click(function() {
				notice.remove();
				});

				setTimeout(function(){
					notice.remove();
				}, 5000);
				location.reload();
			},
		 //clearForm: true             
		}; 
		
	$('#img').ajaxSubmit(options2); 
	
});
});