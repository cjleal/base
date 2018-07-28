var elementSelect, dataElement = {};
$(function() {
	$(".skin-square input[type='checkbox']").iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%'
	});

	$("#guardar").click(function(){
		$.post($url + 'guardar', formData('#formulario'), function(r){
			aviso(r);
		});
	});

	$("#tabla").change(function(){
		$.get($url + 'campos/' + $("#tabla").val(), function(r){
			$("#modelo").html('').append(tmpl('tmpl-field', r.campos));

			$("#modelo").sortable({
				//handle: ".fa-bars",
				//cancel: ":not(.fa-bars)",
				//placeholder: "form-group col-lg-3 col-md-4 col-sm-6 col-xs-12 ui-state-highlight"
			});

			$("#modelo").disableSelection();

			addRelacion(r.relaciones);
		});
	});

	//$("#tabla").val('clientes').change();

	$("#modelo").on('dblclick', '.form-group', function(){
		elementSelect = $(this);

		dataElement = JSON.parse(elementSelect.attr('data-prop'));

		$("#modal-propiedades").modal('show');
		
		$.each(dataElement, function(id, valor){
			$("#" + id, "#modal-propiedades").val(valor);
		});

		$("#required", "#modal-propiedades").prop('checked', false);
		$("#validate", "#modal-propiedades").val('');
		$.each(dataElement.validate, function(id, valor){
			if (valor == 'required'){
				$("#required", "#modal-propiedades").prop('checked', true);
				return;
			}
			
			var prop = valor.substring(0, valor.indexOf(":"));

			if (prop == ''){
				$("#validate", "#modal-propiedades").val(valor);
				return;
			}

			var valor = valor.substring(valor.indexOf(":") + 1);

			$("#" + prop, "#modal-propiedades").val(valor);
		});

	});

	$("#btn-guardar").click(function(){
		dataElement.validate = new Array();
		if (dataElement.options == undefined){
			dataElement.options = new Array();
		}
		//console.log(dataElement);
		$('.form-group select, .form-group input', '#modal-propiedades').each(function(i){
			var id = this.id,
				value = $(this).val();

			switch (id){
				case 'required':
					if ($(this).prop('checked')){
						dataElement.validate.push('required');
					}
					return;
				case 'validate':
					if (value != '')
						dataElement.validate.push(value);
					return;
				case 'min':
					if (value != '')
						dataElement.validate.push('min:' + value);
					return;
				case 'max':
					if (value != '')
						dataElement.validate.push('max:' + value);
					return;
				case 'unique':
					if (value != '')
						dataElement.validate.push('unique:' + value);
					return;
				case 'regex':
					if (value != '')
						dataElement.validate.push('regex:' + value);
					return;
				case 'date_format':
					if (value != '')
						dataElement.validate.push('date_format:' + value);
					return;
				case 'data':
					value = value.split(',');
					return;
			}

			dataElement[id] = value;
		});

		elementSelect.after(tmpl('tmpl-field', [dataElement]));

		elementSelect.remove();

		$("#modal-propiedades").modal('hide');
	});

	$("#agregar_campo").click(function(){
		var data = [{
			"id": "nuevo",
			"name": "nuevo",
			"type": "text",
			"label": "Nuevo",
			"placeholder": "Nuevo",
			"cont_class": "col-lg-3 col-md-4 col-sm-6 col-xs-12",
			"required": true,
			"validate": ["required"]
		}];

		$("#modelo").append(tmpl('tmpl-field', data));

		return false;
	});

	$("tbody", "#relaciones").sortable({
		axis: "y",
		handle: "td.tr-move",
		cancel: "td:not(.tr-move)",
		placeholder: "ui-state-highlight"
	});

	$("tbody", "#relaciones").disableSelection();

	$(".fa-plus", "#relaciones").click(function(){
		addRelacion();

		return false;
	});

	$('#relaciones').on('click', 'i.fa-times', function(){
		var tbody = $(this).parents('tbody');
			padre = $(this).parents('tr');

		if (tbody.length){
			padre.remove();
		}else{
			bootbox.confirm("&iquest;Esta Seguro que Desea Eliminar Todas las Relaciones?", function(result) {
				if (!result){
					return;
				}

				$("#relaciones tbody tr").remove();
			});
		}
	});

	$("#id", "#modal-propiedades").keyup(function(){
		$("#name", "#modal-propiedades").val(this.value);
	});

	$("#type", "#modal-propiedades").change(function(){
		switch($(this).val()){
			case 'textarea':
				$("#cont_class", "#modal-propiedades").val('col-sm-12');
				break;

			case 'textarea':
				$("#cont_class", "#modal-propiedades").val('col-sm-12');
				break;

		}
	});
});

function formData(form){
	var data = {};

	$('input, select', form).each(function(){
		var ele = $(this),
			name = ele.attr('name'),
			value = ele.is(':checkbox') ? 
					(ele.prop('checked') ? '1' : '0') : 
					ele.val();

		if (name == undefined || name == 'options'){
			return;
		}

		if (/\[\]$/.test(name)){
			if (typeof(data[name]) == 'undefined'){
				data[name] = [];
			}

			data[name].push(value);
		}else{
			data[name] = value;
		}
	});

	data.campos = [];
	$('.form-group', '#modelo').each(function(){
		var prop = JSON.parse($(this).attr('data-prop'));

		if (prop.cont_class == 'col-lg-3 col-md-4 col-sm-6 col-xs-12'){
			delete(prop.cont_class);
		}

		data.campos.push(prop);
	});

	return data;
}

function addRelacion(data){
	var data = data || [{
		'relacion' 		: '',
		'model' 		: '',
		'foreign_key' 	: '',
		'local_key' 	: ''
	}];

	$("tbody", "#relaciones").html('');

	$.get($url + 'modelos', function(r){
		for (var i = data.length - 1; i >= 0; i--) {
			data[i].models = r;
		}

		$("tbody", "#relaciones").append(tmpl('tmpl-relacion', data));
	});
}