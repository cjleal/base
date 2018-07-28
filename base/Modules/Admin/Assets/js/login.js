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

var app = {
	form : '',
	nombre : '',
	password : '',

	init : function(){
		this.form = $("#formulario");
		this.nombre = $("input[name='nombre']", this.form).val('').focus();
		this.password = $("input[name='password']", this.form).val('');
		this.recordar = $("input[name='recordar']", this.form);
		
		$("button", this.form).click(this.validarAuth);

		this.form.submit(function(){
			return false;
		});

		$(".form-control", this.form).keypress(this.validarAuth);
	},

	validarAuth : function(e){
		if(e.type == 'click' || e.which == 13){
			app._validarAuth();
		}
	},

	_validarAuth : function(){
		if (this.nombre.val() === ''){
			this.nombre.focus();
		}else if (this.password.val() === ''){
			this.password.focus();
		}else{
			this.buscar();
		}
	},

	buscar : function(){
		$("#boton").prop("disable", true);
		
		$.ajax($url + 'validar',{
			type: "POST",
			data: {
				usuario : this.nombre.val(),
				password : this.password.val(),
				recordar : this.recordar.prop("checked")
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

				if (r.s === "s"){
					//location.reload();
					location.href = $url.replace(/\/+$/,'');
				}
				
				return false;
			}
		});
	}
	
};
$("label.btnrecuperar").on("click", function(){
		console.log("--->");
	});
app.init();
