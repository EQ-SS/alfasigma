$('#btnGuardarPersonaNuevo2').click(function () {

					/*if($('#sltTipoPersonaNuevo').val() == '0'){
						alert("Seleccione el tipo de persona!!!");
						$('#sltTipoPersonaNuevo').focus();
						return;
					}*/
					/*var registroCorrecto = true;
					 
					
					if ($('#txtPaternoPersonaNuevo').val() == '') {
						//alert("Ingrese el apellido paterno de la persona!!!");
						//alertApellidoPaterno();
						alertFaltanDatos();
						$('#txtPaternoPersonaNuevo').addClass('invalid');
						$('#txtPaternoPersonaNuevo').focus();
						registroCorrecto = false;
					}
					if ($('#txtNombrePersonaNuevo').val() == '') {
						//alert("Ingrese el nombre de la persona!!!");
						//alertNombreMedico();
						alertFaltanDatos();
						$('#txtNombrePersonaNuevo').addClass('invalid');
						$('#txtNombrePersonaNuevo').focus();
						registroCorrecto = false;
					}
					if ($('#txtCedulaPersonaNuevo').val() == '') {
						//alert("Ingrese la cédula de la persona!!!");
						//alertCedulaMedico();
						alertFaltanDatos();
						$('#txtCedulaPersonaNuevo').addClass('invalid');
						$('#txtCedulaPersonaNuevo').focus();
						registroCorrecto = false;
					}
					if ($('#sltEspecialidadPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000') {
						//alert("Seleccione la especialidad de la persona!!!");
						//alertEspecialidadMedico();
						alertFaltanDatos();
						$('#sltEspecialidadPersonaNuevo').addClass('invalid-slt');
						$('#sltEspecialidadPersonaNuevo').focus();
					}
					if ($('#sltPacientesXSemanaPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000') {
						//alert("Seleccione los pacientes por semana de la persona!!!");
						//alertPacientesSemana();
						alertFaltanDatos();
						alertFaltanDatos();
						$('#sltPacientesXSemanaPersonaNuevo').addClass('invalid-slt');
						$('#sltPacientesXSemanaPersonaNuevo').focus();
						registroCorrecto = false;
					}
					if ($('#sltHonorariosPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000') {
						//alert("Seleccione los honorarios de la persona!!!");
						//alertHonorariosMedico();
						alertFaltanDatos();
						$('#sltHonorariosPersonaNuevo').addClass('invalid-slt');
						$('#sltHonorariosPersonaNuevo').focus();
						registroCorrecto = false;
					}
					if ($('#sltEstatusPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000') {
						//alert("Seleccione el estatus de la persona!!!");
						//alertEstatusMedico();
						alertFaltanDatos();
						$('#sltEstatusPersonaNuevo').addClass('invalid-slt');
						$('#sltEstatusPersonaNuevo').focus();
						registroCorrecto = false;
					}

					if ($('#txtNombreInstPersonaNuevo').val() == '' && $('#txtCalleInstPersonaNuevo').val() == '') {
						//alert("Seleccione una institución!!!");
						alertFaltanDatos();
						//alertInstitucionM();
						//$('#tabsPersona').tabs({ active: $('#tabs-2') });
						$('#txtNombreInstPersonaNuevo').addClass('invalid');
						$('#txtNombreInstPersonaNuevo').focus();
						registroCorrecto = false;
					}

					/*if($('#sltFrecuenciaPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000'){
						alert("Seleccione la frecuencia de la persona!!!");
						$('#sltFrecuenciaPersonaNuevo').focus();
						return;
					}*/

					/*if ($('#sltDiaFechaNacimientoPersonaNuevo').val() != null) {
						if ($('#sltMesFechaNacimientoPersonaNuevo').val() == null) {
							//alert('Debe seleccionar el mes de la fecha de nacimiento');
							alertFechaNacimientoMes();
							$('#sltMesFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
						if ($('#sltAnioFechaNacimientoPersonaNuevo').val() == null) {
							//alert('Debe seleccionar el año de la fecha de nacimiento');
							alertFechaNacimientoAnio();
							$('#sltAnioFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
					}

					if ($('#sltMesFechaNacimientoPersonaNuevo').val() != null) {
						if ($('#sltDiaFechaNacimientoPersonaNuevo').val() == null) {
							//alert('Debe seleccionar el día de la fecha de nacimiento');
							alertFechaNacimientoDia();
							$('#sltDiaFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
						if ($('#sltAnioFechaNacimientoPersonaNuevo').val() == null) {
							//alert('Debe seleccionar el año de la fecha de nacimiento');
							alertFechaNacimientoAnio();
							$('#sltAnioFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
					}
					if ($('#sltAnioFechaNacimientoPersonaNuevo').val() != null) {
						if ($('#sltDiaFechaNacimientoPersonaNuevo').val() == null) {
							//alert('Debe seleccionar el día de la fecha de nacimiento');
							alertFechaNacimientoDia();
							$('#sltDiaFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
						if ($('#sltMesFechaNacimientoPersonaNuevo').val() == null) {
							//alert('Debe seleccionar el mes de la fecha de nacimiento');
							alertFechaNacimientoMes();
							$('#sltMesFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
					}
					
					if(registroCorrecto){
						$('#txtPaternoPersonaNuevo').removeClass('invalid');
						$('#txtNombrePersonaNuevo').removeClass('invalid');
						$('#txtCedulaPersonaNuevo').removeClass('invalid');
						$('#sltEspecialidadPersonaNuevo').removeClass('invalid-slt');
						$('#sltPacientesXSemanaPersonaNuevo').removeClass('invalid-slt');
						$('#sltHonorariosPersonaNuevo').removeClass('invalid-slt');
						$('#sltEstatusPersonaNuevo').removeClass('invalid-slt');
						$('#txtNombreInstPersonaNuevo').removeClass('invalid');
					}
					
					if(!registroCorrecto){
						return;
					}*/

					if($('#txtPaternoPersonaNuevo').val() == ''){
						alert("Ingrese el apellido paterno de la persona!!!");
						$('#txtPaternoPersonaNuevo').focus();
						return;
					}
					if($('#txtNombrePersonaNuevo').val() == ''){
						alert("Ingrese el nombre de la persona!!!");
						$('#txtNombrePersonaNuevo').focus();
						return;
					}
					if($('#txtCedulaPersonaNuevo').val() == ''){
						alert("Ingrese la cédula de la persona!!!");
						$('#txtCedulaPersonaNuevo').focus();
						return;
					}
					if($('#sltEspecialidadPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000'){
						alert("Seleccione la especialidad de la persona!!!");
						$('#sltEspecialidadPersonaNuevo').focus();
						return;
					}
					if($('#sltPacientesXSemanaPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000'){
						alert("Seleccione los pacientes por semana de la persona!!!");
						$('#sltPacientesXSemanaPersonaNuevo').focus();
						return;
					}
					if($('#sltHonorariosPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000'){
						alert("Seleccione los honorarios de la persona!!!");
						$('#sltHonorariosPersonaNuevo').focus();
						return;
					}
					if($('#sltEstatusPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000'){
						alert("Seleccione el estatus de la persona!!!");
						$('#sltEstatusPersonaNuevo').focus();
						return;
					}
					
					if($('#txtNombreInstPersonaNuevo').val() == '' && $('#txtCalleInstPersonaNuevo').val() == ''){
						alert("Seleccione una institución!!!");
						$('#txtNombreInstPersonaNuevo').focus();
						return;
					}
					/*if($('#sltFrecuenciaPersonaNuevo').val() == '00000000-0000-0000-0000-000000000000'){
						alert("Seleccione la frecuencia de la persona!!!");
						$('#sltFrecuenciaPersonaNuevo').focus();
						return;
					}*/
					
					
					
					if($('#sltDiaFechaNacimientoPersonaNuevo').val() != null){
						if($('#sltMesFechaNacimientoPersonaNuevo').val() == null){
							alert('Debe seleccionar el mes de la fecha de nacimiento');
							$('#sltMesFechaNacimientoPersonaNuevo').focus();
							return;
						}
						if($('#sltAnioFechaNacimientoPersonaNuevo').val() == null){
							alert('Debe seleccionar el año de la fecha de nacimiento');
							$('#sltAnioFechaNacimientoPersonaNuevo').focus();
							return;
						}
					}
					
					if($('#sltMesFechaNacimientoPersonaNuevo').val() != null){
						if($('#sltDiaFechaNacimientoPersonaNuevo').val() == null){
							alert('Debe seleccionar el día de la fecha de nacimiento');
							$('#sltDiaFechaNacimientoPersonaNuevo').focus();
							return;
						}
						if($('#sltAnioFechaNacimientoPersonaNuevo').val() == null){
							alert('Debe seleccionar el año de la fecha de nacimiento');
							$('#sltAnioFechaNacimientoPersonaNuevo').focus();
							return;
						}
					}
					
					if($('#sltAnioFechaNacimientoPersonaNuevo').val() != null){
						if($('#sltDiaFechaNacimientoPersonaNuevo').val() == null){
							alert('Debe seleccionar el día de la fecha de nacimiento');
							$('#sltDiaFechaNacimientoPersonaNuevo').focus();
							return;
						}
						if($('#sltMesFechaNacimientoPersonaNuevo').val() == null){
							alert('Debe seleccionar el mes de la fecha de nacimiento');
							$('#sltMesFechaNacimientoPersonaNuevo').focus();
							return;
						}
					}

					idInst = $('#hdnIdInstPersonaNuevo').val();
					tipoPersona = $('#sltTipoPersonaNuevo').val();
					nombre = $('#txtNombrePersonaNuevo').val();
					paterno = $('#txtPaternoPersonaNuevo').val();
					materno = $('#txtMaternoPersonaNuevo').val();
					sexo = $('#sltSexoPersonaNuevo').val();
					especialidad = $('#sltEspecialidadPersonaNuevo').val();
					subespecialidad = $('#sltSubEspecialidadPersonaNuevo').val();
					pacientesSemana = $('#sltPacientesXSemanaPersonaNuevo').val();
					honorarios = $('#sltHonorariosPersonaNuevo').val();
					//fecha = $('#txtFechaNacimientoPersonaNuevo').val();
					fecha = $('#sltAnioFechaNacimientoPersonaNuevo').val() + '-' + $('#sltMesFechaNacimientoPersonaNuevo').val() + '-' + $('#sltDiaFechaNacimientoPersonaNuevo').val();
					categoria = $('#sltCategoriaPersonaNuevo').val();
					cedula = $('#txtCedulaPersonaNuevo').val();
					frecuencia = $('#sltFrecuenciaPersonaNuevo').val();
					dificultadVisita = $('#sltDificultadVisita').val();
					liderOpinion = $('#sltLiderOpinionPersonaNuevo').val();
					botiquin = $('#sltBotiquinPersonaNuevo').val();
					iguala = $('#txtIgualaPersonaNuevo').val();
					telefono = $('#txtTelefonoInstPersonaNuevo').val();
					telefono1 = $('#txtTelefono1InstPersonaNuevo').val();
					email = $('#txtEmailInstPersonaNuevo').val();
					corto = $('#txtCorto').val();
					largo = $('#txtLargo').val();
					generales = $('#txtGenerales').val();
					numInterior = $('#txtNumIntInstPersonaNuevo').val();
					abierto1 = $('#txtCampoAbierto1PersonaNuevo').val();
					abierto2 = $('#txtCampoAbierto2PersonaNuevo').val();
					abierto3 = $('#txtCampoAbierto3PersonaNuevo').val();
					telPersonal = $('#txtTelPersonalPersonaNuevo').val();
					mailPersonal = $('#txtCorreoPersonalPersonaNuevo').val();
					torre = $('#txtTorrePersonaNuevo').val();
					piso = $('#txtPisoPersonaNuevo').val();
					consultorio = $('#txtConsultorioPersonaNuevo').val();
					departamento = $('#txtDepartamentoPersonaNuevo').val();
					tipoTrabajo = $('#sltTipoTrabajoPersonaNuevo').val();
					puesto = $('#sltPuestoPersonaNuevo').val();
					estatusPersona = $('#sltEstatusPersonaNuevo').val();

					telPersonal2 = $('#txtTelPersonalPersonaNuevo2').val();
					mailPersonal2 = $('#txtCorreoPersonalPersonaNuevo2').val();
					celular = $('#txtCelularPersonaNuevo').val();

					prod1what = $('#sltProducto1PersonaNuevo').val();
					prod1w = $('#txtWProducto1').val();
					prod1h = $('#txtHProducto1').val();
					prod1a = $('#txtAProducto1').val();
					prod1t = $('#txtTProducto1').val();
					prod2what = $('#sltProducto2PersonaNuevo').val();
					prod2w = $('#txtWProducto2').val();
					prod2h = $('#txtHProducto2').val();
					prod2a = $('#txtAProducto2').val();
					prod2t = $('#txtTProducto2').val();
					prod3what = $('#sltProducto3PersonaNuevo').val();
					prod3w = $('#txtWProducto3').val();
					prod3h = $('#txtHProducto3').val();
					prod3a = $('#txtAProducto3').val();
					prod3t = $('#txtTProducto3').val();
					prod4what = $('#sltProducto4PersonaNuevo').val();
					prod4w = $('#txtWProducto4').val();
					prod4h = $('#txtHProducto4').val();
					prod4a = $('#txtAProducto4').val();
					prod4t = $('#txtTProducto4').val();
					prod5what = $('#sltProducto5PersonaNuevo').val();
					prod5w = $('#txtWProducto5').val();
					prod5h = $('#txtHProducto5').val();
					prod5a = $('#txtAProducto5').val();
					prod5t = $('#txtTProducto5').val();
					divmedico = $('#sltDivMedicoNuevo').val();
					lider = $('#sltLiderOpinionPersonaNuevo').val();
					paraestatales = $('#sltParaestatales').val();
					segmentacionflonorm = $('#sltSegmentacionFlonorPersonaNuevo').val();
					segmentacionvessel = $('#sltSegmentacionVesselPersonaNuevo').val();
					segmentacionzirfos = $('#sltSegmentacionZirfosPersonaNuevo').val();
					segmentacionateka = $('#sltSegmentacionAtekaPersonaNuevo').val();
					segmentacionganar = $('#sltPregunta1PersonaNuevo').val();
					segmentaciondesarrollar = $('#sltPregunta2PersonaNuevo').val();
					segmentaciondefender = $('#sltPregunta3PersonaNuevo').val();
					segmentacionevaluar = $('#sltPregunta4PersonaNuevo').val();

					tipoUsuario = $('#hdnTipoUsuario').val();
					ruta = $('#hdnRutaDatosPersonales').val();

					/*pasatiempo*/
					var cuanto = $('#hdnPasatiempoPersonaNuevo').val();
					var pasatiempoSelec = '';
					for (i = 1; i < cuanto; i++) {
						if ($('#chkPasatiempoPersonaNuevo' + i).prop('checked')) {
							pasatiempoSelec += $('#chkPasatiempoPersonaNuevo' + i).val() + ",";
						}
					}
					//alert(pasatiempoSelec);
					pasatiempoSelec = pasatiempoSelec.slice(0, -1);

					lunesam = $('#chkLunesAm').prop('checked') ? 1 : 0;
					lunespm = $('#chkLunesPm').prop('checked') ? 1 : 0;
					lunesTodo = $('#chkLunesTodo').prop('checked') ? 1 : 0;
					lunesPrevia = $('#chkLunesPrevia').prop('checked') ? 1 : 0;
					lunesFijo = $('#chkLunesFijo').prop('checked') ? 1 : 0;

					martesam = $('#chkMartesAm').prop('checked') ? 1 : 0;
					martespm = $('#chkMartesPm').prop('checked') ? 1 : 0;
					martesTodo = $('#chkMartesTodo').prop('checked') ? 1 : 0;
					martesPrevia = $('#chkMartesPrevia').prop('checked') ? 1 : 0;
					martesFijo = $('#chkMartesFijo').prop('checked') ? 1 : 0;

					miercolesam = $('#chkMiercolesAm').prop('checked') ? 1 : 0;
					miercolespm = $('#chkMiercolesPm').prop('checked') ? 1 : 0;
					miercolesTodo = $('#chkMiercolesTodo').prop('checked') ? 1 : 0;
					miercolesPrevia = $('#chkMiercolesPrevia').prop('checked') ? 1 : 0;
					miercolesFijo = $('#chkMiercolesFijo').prop('checked') ? 1 : 0;

					juevesam = $('#chkJuevesAm').prop('checked') ? 1 : 0;
					juevespm = $('#chkJuevesPm').prop('checked') ? 1 : 0;
					juevesTodo = $('#chkJuevesTodo').prop('checked') ? 1 : 0;
					juevesPrevia = $('#chkJuevesPrevia').prop('checked') ? 1 : 0;
					juevesFijo = $('#chkJuevesFijo').prop('checked') ? 1 : 0;

					viernesam = $('#chkViernesAm').prop('checked') ? 1 : 0;
					viernespm = $('#chkViernesPm').prop('checked') ? 1 : 0;
					viernesTodo = $('#chkViernesTodo').prop('checked') ? 1 : 0;
					viernesPrevia = $('#chkViernesPrevia').prop('checked') ? 1 : 0;
					viernesFijo = $('#chkViernesFijo').prop('checked') ? 1 : 0;

					sabadoam = $('#chkSabadoAm').prop('checked') ? 1 : 0;
					sabadopm = $('#chkSabadoPm').prop('checked') ? 1 : 0;
					sabadoTodo = $('#chkSabadoTodo').prop('checked') ? 1 : 0;
					sabadoPrevia = $('#chkSabadoPrevia').prop('checked') ? 1 : 0;
					sabadoFijo = $('#chkSabadoFijo').prop('checked') ? 1 : 0;

					domingoam = $('#chkDomingoAm').prop('checked') ? 1 : 0;
					domingopm = $('#chkDomingoPm').prop('checked') ? 1 : 0;
					domingoTodo = $('#chkDomingoTodo').prop('checked') ? 1 : 0;
					domingoPrevia = $('#chkDomingoPrevia').prop('checked') ? 1 : 0;
					domingoFijo = $('#chkDomingoFijo').prop('checked') ? 1 : 0;

					lunesComentarios = $('#txtLunesComentarios').val();
					martesComentarios = $('#txtMartesComentarios').val();
					miercolesComentarios = $('#txtMiercolesComentarios').val();
					juevesComentarios = $('#txtJuevesComentarios').val();
					viernesComentarios = $('#txtViernesComentarios').val();
					sabadoComentarios = $('#txtSabadoComentarios').val();
					domingoComentarios = $('#txtDomingoComentarios').val();

					horario = lunesam.toString() +
						lunespm.toString() +
						lunesTodo.toString() +
						lunesPrevia.toString() +
						lunesFijo.toString() +
						martesam.toString() +
						martespm.toString() +
						martesTodo.toString() +
						martesPrevia.toString() +
						martesFijo.toString() +
						miercolesam.toString() +
						miercolespm.toString() +
						miercolesTodo.toString() +
						miercolesPrevia.toString() +
						miercolesFijo.toString() +
						juevesam.toString() +
						juevespm.toString() +
						juevesTodo.toString() +
						juevesPrevia.toString() +
						juevesFijo.toString() +
						viernesam.toString() +
						viernespm.toString() +
						viernesTodo.toString() +
						viernesPrevia.toString() +
						viernesFijo.toString() +
						sabadoam.toString() +
						sabadopm.toString() +
						sabadoTodo.toString() +
						sabadoPrevia.toString() +
						sabadoFijo.toString() +
						domingoam.toString() +
						domingopm.toString() +
						domingoTodo.toString() +
						domingoPrevia.toString() +
						domingoFijo.toString();

					/*alert(horario);
					00000000000000000000000000000000000*/
					$("#divRespuesta").load("ajax/guardarPersona.php", {
						idPersona: $("#hdnIdPersona").val(),
						tipoPersona: tipoPersona,
						nombre: nombre,
						paterno: paterno,
						materno: materno,
						sexo: sexo,
						especialidad: especialidad,
						subespecialidad: subespecialidad,
						pacientesSemana: pacientesSemana,
						honorarios: honorarios,
						fecha: fecha,
						categoria: categoria,
						cedula: cedula,
						frecuencia: frecuencia,
						dificultadVisita: dificultadVisita,
						liderOpinion: liderOpinion,
						botiquin: botiquin,
						iguala: iguala,
						idInst: idInst,
						telefono: telefono,
						telefono1: telefono1,
						email: email,
						corto: corto,
						largo: largo,
						generales: generales,
						idUsuario: $('#hdnIdUser').val(),
						interior: numInterior,
						pasatiempo: pasatiempoSelec,
						horario: horario,
						lunesComentarios: lunesComentarios,
						martesComentarios: martesComentarios,
						miercolesComentarios: miercolesComentarios,
						juevesComentarios: juevesComentarios,
						viernesComentarios: viernesComentarios,
						sabadoComentarios: sabadoComentarios,
						domingoComentarios: domingoComentarios,
						abierto1: abierto1,
						abierto2: abierto2,
						abierto3: abierto3,
						telPersonal: telPersonal,
						mailPersonal: mailPersonal,
						torre: torre,
						piso: piso,
						consultorio: consultorio,
						departamento: departamento,
						tipoTrabajo: tipoTrabajo,
						puesto: puesto,
						tipoUsuario: tipoUsuario,
						ruta: ruta,
						prod1what: prod1what,
						prod1w: prod1w,
						prod1h: prod1h,
						prod1a: prod1a,
						prod1t: prod1t,
						prod2what: prod2what,
						prod2w: prod2w,
						prod2h: prod2h,
						prod2a: prod2a,
						prod2t: prod2t,
						prod3what: prod3what,
						prod3w: prod3w,
						prod3h: prod3h,
						prod3a: prod3a,
						prod3t: prod3t,
						prod4what: prod4what,
						prod4w: prod4w,
						prod4h: prod4h,
						prod4a: prod4a,
						prod4t: prod4t,
						prod5what: prod5what,
						prod5w: prod5w,
						prod5h: prod5h,
						prod5a: prod5a,
						prod5t: prod5t,
						divmedico: divmedico,
						lider: lider,
						paraestatales: paraestatales,
						segmentacionflonorm: segmentacionflonorm,
						segmentacionvessel: segmentacionvessel,
						segmentacionzirfos: segmentacionzirfos,
						segmentacionateka: segmentacionateka,
						segmentacionganar: segmentacionganar,
						segmentaciondesarrollar: segmentaciondesarrollar,
						segmentaciondefender: segmentaciondefender,
						segmentacionevaluar: segmentacionevaluar,
						estatusPersona: estatusPersona,
						telPersonal2: telPersonal2,
						mailPersonal2: mailPersonal2,
						celular: celular
					});

				});