function update_calendar() {
	var month = $('#calendar_mes').val();
	var year = $('#calendar_anio').val();

	var idUsuario = $('#sltRepreCalendario').val();
	var ids = $('#hdnIds').val();
	var repre = $('#hdnIdsFiltroUsuarios').val();
	if (idUsuario === undefined) {
		idUsuario = '';
	}
	var repreNombres = $('#hdnNombresFiltroUsuarios').val();

	if(repre !=''){
		var unrepre = repre.substring(0, repre.length - 1);
		ids = unrepre.replace(/,/g,"','");
	}

	var valores = 'month=' + month + '&year=' + year + '&idUsuario=' + idUsuario + '&ids=' + ids + '&repre=' + repre;

	set_date(year + "-" + (month < 10 ? "0" : "") + month + '-' + "01");

	cargandoCalendario3();

	$.ajax({
		url: 'calendario/setvalue.php',
		type: "GET",
		data: valores,
		success: function (datos) {
			$("#calendario_dias").html(datos);
		}
	});
}

function updatePreviousCalendar() {
	var month = $('#calendar_mes').val();
	var year = $('#calendar_anio').val();

	terminaPintarBullets = 0;


	if (month == 1) {
		year--;
		month = 12;
	} else {
		month--;
	}
	$('#calendar_mes').val(month);
	$('#calendar_anio').val(year);
	update_calendar3(month, year);

	set_date(year + "-" + (month < 10 ? "0" : "") + month + '-' + "01");
	cargandoCalendario3();
}

function updateNextCalendar() {
	var month = $('#calendar_mes').val();
	var year = $('#calendar_anio').val();
	if (month == 12) {
		year++;
		month = 1;
	} else {
		month++;
	}
	$('#calendar_mes').val(month);
	$('#calendar_anio').val(year);
	update_calendar3(month, year);
	set_date(year + "-" + (month < 10 ? "0" : "") + month + '-' + "01");
	cargandoCalendario3();
}

function update_calendar2(fecha) {
	var res = fecha.split("-");
	var year = res[0];
	var month = res[1];
	var day = res[2];

	var idUsuario = $('#sltRepreCalendario').val();
	var ids = $('#hdnIds').val();
	var repre = $('#hdnIdsFiltroUsuarios').val();
	if (idUsuario === undefined) {
		idUsuario = '';
	}
	var repreNombres = $('#hdnNombresFiltroUsuarios').val();

	if(repre !=''){
		var unrepre = repre.substring(0, repre.length - 1);
		ids = unrepre.replace(/,/g,"','");
	}

	var valores = 'month=' + month + '&year=' + year + '&idUsuario=' + idUsuario + '&ids=' + ids + '&repre=' + repre;

	set_date(year + "-" + month + '-' + day);

	cargandoCalendario3();

	$.ajax({
		url: 'calendario/setvalue.php',
		type: "GET",
		data: valores,
		success: function (datos) {
			$("#calendario_dias").html(datos);
		}
	});
}

function update_calendar3(mes, anio) {
	var month = mes;
	var year = anio;

	var idUsuario = $('#sltRepreCalendario').val();
	var ids = $('#hdnIds').val();
	var repre = $('#hdnIdsFiltroUsuarios').val();
	if (idUsuario === undefined) {
		idUsuario = '';
	}
	var repreNombres = $('#hdnNombresFiltroUsuarios').val();

	if(repre !=''){
		var unrepre = repre.substring(0, repre.length - 1);
		ids = unrepre.replace(/,/g,"','");
	}
	

	var valores = 'month=' + month + '&year=' + year + '&idUsuario=' + idUsuario + '&ids=' + ids + '&repre=' + repre;

	//alert(ids);

	$.ajax({
		url: 'calendario/setvalue.php',
		type: "GET",
		data: valores,
		success: function (datos) {
			$("#calendario_dias").html(datos);
			$("#calendario").waitMe("hide");
		}
	});

}

function set_date(date) {
	//alert(date);
	var dia = date.substring(8);
	$('#fecha').attr('value', date);
	if ($('#anterior').val() != '') {
		$('#day' + $('#anterior').val()).css("color", "#000000");
		$('#day' + $('#anterior').val()).css('font-weight', 'normal');
	}
	$('#day' + dia).css("color", "#0000FF");
	$('#day' + dia).css('font-weight', 'bold');


	$('#anterior').val(dia);
	if ($('#btnPlanCalendario').hasClass('seleccionado')) {
		planVisita = 'plan';
	} else if ($('#btnVisitaCalendario').hasClass('seleccionado')) {
		planVisita = 'visita';
	}
	$('#hdnFechaCalendario').val(date);
	var idUsuario = $('#sltRepreCalendario').val();
	//var idUsuario = $('#hdnIdUser').val();
	if ($('#hdnTipoUsuario').val == 4) {
		var ids = $('#hdnIds').val();
	} else {
		var ids = $('#hdnIds').val() + "','" + $('#hdnIdUser').val();
	}
	var repre = $('#hdnIdsFiltroUsuarios').val();
	if (idUsuario === undefined) {
		idUsuario = '';
	}
	var repreNombres = $('#hdnNombresFiltroUsuarios').val();
	var tipoUsuario = $('#hdnTipoUsuario').val();


	$('#divRespuesta').load("ajaxCalendario.php", {
		fecha: date,
		idUsuario: idUsuario,
		planVisita: planVisita,
		ids: ids,
		repre: repre,
		tipoUsuario: tipoUsuario,
		repreNombres: repreNombres
	}, function () {
		$("#divWeek").waitMe("hide");
		setTimeout(function () {
			$("#calendario").waitMe("hide");
		}, 1300);
	});
}

function show_calendar() {
	//div donde se mostrar� calendario
	//$('#calendario').toggle(); 
}

function semanaAntes() {
	var fechaGuardada = $('#fecha').attr('value');
	var fechaObj;
	if (fechaGuardada == null) {
		fechaObj = new Date();
	} else {
		fechaObj = new Date(fechaGuardada);
	}
	fechaObj.setDate(fechaObj.getDate() - 7);
	set_date(fechaObj.getFullYear() + "-" + (fechaObj.getMonth() < 9 ? "0" : "") + (fechaObj.getMonth() + 1) + "-" + (fechaObj.getDate() < 10 ? "0" : "") + fechaObj.getDate());
	var month = fechaObj.getMonth() + 1;
	var year = fechaObj.getFullYear();
	if ($('#calendar_mes').val() != month || $('#calendar_anio').val() != year) {
		$('#calendar_mes').val(month);
		$('#calendar_anio').val(year);
		update_calendar3(month, year);
	}
	cargandoCalendario2();
}

function semanaDespues() {
	var fechaGuardada = $('#fecha').attr('value');
	var fechaObj;
	if (fechaGuardada == null) {
		fechaObj = new Date();
	} else {
		fechaObj = new Date(fechaGuardada);
	}
	fechaObj.setDate(fechaObj.getDate() + 7);
	set_date(fechaObj.getFullYear() + "-" + (fechaObj.getMonth() < 9 ? "0" : "") + (fechaObj.getMonth() + 1) + "-" + (fechaObj.getDate() < 10 ? "0" : "") + fechaObj.getDate());
	var month = fechaObj.getMonth() + 1;
	var year = fechaObj.getFullYear();
	if ($('#calendar_mes').val() != month || $('#calendar_anio').val() != year) {
		$('#calendar_mes').val(month);
		$('#calendar_anio').val(year);
		update_calendar3(month, year);
	}
	cargandoCalendario2();
}

function rutasSlt() {
	$('.select-multi').SumoSelect({
		search: true,
		searchText: 'Buscar',
		placeholder: 'Seleccione una ruta',
		selectAll: true,
		okCancelInMulti: true
	});

	$('.btnOk').on('click', function () {
		$('#sltMultiSelectCal span').text($('#hdnNombresFiltroUsuarios').val());
		if ($('#btnPlanCalendario').hasClass('seleccionado')) {
			$('#btnPlanCalendario').click();
		} else if ($('#btnVisitaCalendario').hasClass('seleccionado')) {
			$('#btnVisitaCalendario').click();
		}
	});
}