$(function(){
	$('#guardar', '#botonera').on('click', function(){
		$.post($url + 'guardar', formData('#formulario'), function(r){
			aviso(r);

			if (typeof r === 'string' || r.s === 'n'){
				return;
			}
		});
	});

	$("#formulario").submit(function(){
		return false;
	});

	$("#fields thead i.fa-plus").click(function(){
		addField();
	}).click();

	$("tbody", "#fields").sortable({
		axis: "y",
		handle: "td.tr-move",
		cancel: "td:not(.tr-move)",
		placeholder: "ui-state-highlight"
	});

	$("tbody", "#fields").disableSelection();

	$('#fields').on('click', 'i.fa-times', function(){
		var tbody = $(this).parents('tbody');
			padre = $(this).parents('tr');

		if (tbody.length){
			padre.remove();
		}else{
			bootbox.confirm("&iquest;Esta Seguro que Desea Eliminar Todos los Campos?", function(result) {
				if (!result){
					return;
				}

				$("#fields tbody tr").remove();

				$("#fields thead i.fa-plus").click();
			});
		}
	});

	$(".skin-square input[type='checkbox']").iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%'
	});
});

function addField(){
	$("tbody", "#fields").append(tmpl("tmpl-table-field"));

	$fila = $("tbody tr:last", "#fields");

	$("input[type='checkbox']", $fila).iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%'
	});

	$('select', $fila).change(function(){
		var valor = $(this).val(),
			valorLength = '',
			$fila = $(this).parent().parent();

		switch(valor){
			case 'string':
				valorLength = '50';
				break;
		}

		$("input[name='length[]']", $fila).val(valorLength);
	});
}

function addIncrements(){
	$("tbody", "#fields").prepend(tmpl("tmpl-table-field"));

	$fila = $("tbody tr:first", "#fields");

	$("input[name='name[]']", $fila).val('id');
	$('select', $fila).val('increments');
	$("input[name='pk[]']", $fila).prop('checked', true);
}

function addTimestamps(){
	$("tbody", "#fields").append(tmpl("tmpl-table-field"));

	$fila = $("tbody tr:last", "#fields");

	$("input[name='name[]']", $fila).val('Timestamps').prop('readonly', true);
	$("input", $fila).prop('readonly', true);
	$('select', $fila).val('timestamps').css('pointer-events','none');
}

function addSoftDelete(){
	$("tbody", "#fields").append(tmpl("tmpl-table-field"));

	$fila = $("tbody tr:last", "#fields");

	$("input[name='name[]']", $fila).val('SoftDelete').prop('readonly', true);
	$("input", $fila).prop('readonly', true);
	$('select', $fila).val('softDeletes').css('pointer-events','none');
}

function formData(form){
	var data = {};

	$('input, select', form).each(function(){
		var ele = $(this),
			name = ele.attr('name'),
			value = ele.is(':checkbox') ? 
					(ele.prop('checked') ? '1' : '0') : 
					ele.val();
		if (/\[\]$/.test(name)){
			if (typeof(data[name]) == 'undefined'){
				data[name] = [];
			}

			data[name].push(value);
		}else{
			data[name] = value;
		}
	});

	return data;
}