<?php
include "conexion.php";
$conex = $conn;
include('calendario/calendario.php');
$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
$arrCiclo = sqlsrv_fetch_array(sqlsrv_query($conn, "select c_name from cycles where getdate() between start_date and finish_date "));
$cicloActivo = $arrCiclo['c_name'];
$idUsuario = $_GET['idUser'];
?>
<!DOCTYPE html>
	<head>
		<link href="jquery-ui.css" rel="stylesheet">
		<link href="css/smart.css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<!--<script type="text/javascript" src="js/jquery-1.3.1.min.js"></script>-->
		<script type="text/javascript" src="calendario/js/jquery.functions.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyD-tf5PgJGx6iHEtZ-4W0ynr-Fgfzarch0"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		
		<script type="text/javascript" src="fusioncharts/js/fusioncharts.js"></script>
		<script type="text/javascript" src="fusioncharts/js/themes/fusioncharts.theme.fint.js"></script>
		
		<script>
			$(document).ready(function(){
				/* menu principal*/
				
				function aparece(div){
					$('#divInicio').hide();
					$('#divPersonas').hide();
					$('#divInstituciones').hide();
					$('#divCalendario').hide();
					$('#divCiclos').hide();
					$('#divMensajes').hide();
					$('#divInventario').hide();
					$('#divReportes').hide();
					$('#divGeo').hide();
					$('#divConfig').hide();
					$('#divDocumentos').hide();
					$('#divAprobaciones').hide();
					$('#'+div).show();
					if(div == 'divGeo'){
						lat = $('#txtLatitud').val();
						lon = $('#txtLongitud').val();
						initialize(lat, lon);				
					}
					$('#lblTimer').text('2:00:00');
				}
				
				$('#imgHome').click(function(){
					aparece('divInicio');
				});
				
				$('#imgPersonas').click(function(){
					var pagina = $('#hdnPaginaPersonas').val();
					var ids = $('#hdnIds').val();
					var hoy = $('#hdnHoy').val();
					nuevaPagina(pagina,hoy,ids,'' );
					aparece('divPersonas');
				});
				
				$('#imgInstotuciones').click(function(){
					aparece('divInstituciones');
				});
				
				$('#imgCalendario').click(function(){
					aparece('divCalendario');
				});
				
				$('#imgCalendarioMercado').click(function(){
					aparece('divCiclos');
				});
				
				$('#imgMensajes').click(function(){
					aparece('divMensajes');
				});
				
				$('#imgInventario').click(function(){
					aparece('divInventario');
				});
				
				$('#imgReportes').click(function(){
					aparece('divReportes');
				});
				
				$('#imgGeo').click(function(){
					load_map('mapa');
					aparece('divGeo');
				});
				
				$('#imgConfig').click(function(){
					aparece('divConfig');
				});
				
				$('#anterior').click(function(){
					alert('aqui mero');
				});
				
				$('#imgDocumentosEntregados').click(function(){
					aparece('divDocumentos');
				});
				
				$('#imgAprobaciones').click(function(){
					ids = $('#hdnIds').val();
					aparece('divAprobaciones');
					$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids});
				});
				
				/* fin menu prindipal*/
				
				/* inicio */
				
				$('#btnExportarPlan').click(function(){
					var ancho = 350;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var idUser = $('#hdnIdUser').val();
					var ventana = window.open("exportarExcel.php?idUser="+idUser, "vtnExcel", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				
				$('#btnImprimirPlan').click(function(){
					var ancho = 350;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var idUser = $('#hdnIdUser').val();
					var ventana = window.open("imprimirPlanes.php?idUser="+idUser, "vtnExcel", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				
				$('#sltRutas').on('change', function(){
					$('#divTablaPlanes').load('ajax/cargarPlanesRuta.php',{idRuta:$('#sltRutas').val(), idUsuario:$('#hdnIdUser').val()});
				});
				
				/* fin de inicio */
				
				/*personas*/
				
				$('#lstProductoDatosPersonales').change(function(){
					tablas = $("#hdnTablasPrescripciones").val();
					arrTablas = tablas.split(',');
					for(var i=0;i<arrTablas.length-1;i++){
						if($('#lstProductoDatosPersonales').val() == 0){
							$('#tbl'+arrTablas[i]).show();
						}else{
							if(arrTablas[i] == $('#lstProductoDatosPersonales').val()){
								$('#tbl'+arrTablas[i]).show();
							}else{
								$('#tbl'+arrTablas[i]).hide();
							}
						}
					}
					tabla = $('#lstProductoDatosPersonales').val();
					if(tabla == 0){
						$('#tabla1').show();
						$('#tabla2').show();
						$('#tabla3').show();
					}else{
						$('#tabla1').hide();
						$('#tabla2').hide();
						$('#tabla3').hide();
						$('#tabla'+tabla).show();
					}
				});
				
				$('#imgAgregarPersona').click(function(){
					var idUsusario = $('#hdnIdUser').val();
					//abrirVentanaPersona('persona.php?idUsuario='+idUsusario,500,900);
					$('#divPersona').show();
					$('#divCapa3').show();
					$('#divRespuesta').load("ajax/cargarPersonaNueva.php",{idUsusario:idUsusario});
				});
				
				$('#imgFiltrar2').click(function(){
					$('#imgFiltrar').show();
					$('#trFiltros2').show('slow');
					$('#imgFiltrar2').hide();
				});
				
				$('#imgFiltrar').click(function(){
					$('#imgFiltrar').hide();
					$('#trFiltros2').hide('slow');
					$('#imgFiltrar2').show();
					$('#btnLimpiarFiltros').click();
				});
				
				$('#lstYearMuestra').change(function(){
					$("#divMuestraMedica").load("ajax/cargarMuestra.php",{year:$('#lstYearMuestra').val(),idPersona:$('#hdnIdPersona').val()});
				});
				
				$('#imgAgregarPlan').click(function(){
					var idPersona = $('#hdnIdPersona').val();
					$('#divPlanes').show();
					$('#divCapa3').show('slow');
					$("#divRespuesta").load("ajax/cargarPlan.php", {idPersona:idPersona});
				});
				
				$('#imgAgregarVisita').click(function(){
					var idPersona = $('#hdnIdPersona').val();
					var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
					var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
					$("#divVisitas").show();
					$("#divCapa3").show("slow");
					$("#divRespuesta").load("ajax/cargarVisita.php",{idPersona:idPersona,lat:lat,lon:lon});
					
				});
				
				$('#btnLimpiarFiltros').click(function(){
					$('#sltTipoPersonaFiltro').val('');
					$('#txtNombreFiltro').val('');
					$('#txtApellidosFiltro').val('');
					$('#sltSexoFiltro').val('');
					$('#sltEspecialidadFiltro').val('');
					$('#sltCategoriaFiltro').val('');
					$('#txtInstitucionFiltro').val('');
					$('#txtDireccionFiltro').val('');
					$('#txtDelegacionFiltro').val('');
					$('#txtEstadoFiltro').val('');
				});
				
				$('#btnImprimirMuestraMaterialPersonas').click(function(){
					var ancho = 350;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var idPersona = $('#hdnIdPersona').val()
					var year = $('#lstYearMuestra').val();
					var ventana = window.open("imprimirMuestraMaterial.php?idPersona="+idPersona+"&year="+year, "vtnExcel", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				
				$('#lstYearVisitas').change(function(){
					var idPersona = $('#hdnIdPersona').val();
					var idUsuario = $('#hdnIdUser').val();
					var year = $('#lstYearVisitas').val();
					$('#divRespuesta').load("ajax/cargarVisitas.php",{idPersona:idPersona,year:year,idUsuario:idUsuario});
				});
				
				$('#lstYearPlanes').change(function(){
					var idPersona = $('#hdnIdPersona').val();
					var idUsuario = $('#hdnIdUser').val();
					var year = $('#lstYearPlanes').val();
					$('#divRespuesta').load("ajax/cargarPlanes.php",{idPersona:idPersona,year:year,idUsuario:idUsuario});
				});
				
				$('#btnAprobacionesPers').click(function(){
					$('#divAprobacionesPers').show();
					$('#over').show(500);
					$('#fade').show(500);
					$('#btnPendienteAprobacionesPers').click();
				});
				
				$('#btnPendienteAprobacionesPers').click(function(){
					var idUsuario = $('#hdnIdUser').val();
					$('#btnPendienteAprobacionesPers').addClass("seleccionado");
					$("#btnAceptadoAprobacionesPers").removeClass("seleccionado");
					$("#btnRechazadoAprobacionesPers").removeClass("seleccionado");
					$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:1});
				});
				
				$('#btnAceptadoAprobacionesPers').click(function(){
					var idUsuario = $('#hdnIdUser').val();
					$('#btnPendienteAprobacionesPers').removeClass("seleccionado");
					$("#btnAceptadoAprobacionesPers").addClass("seleccionado");
					$("#btnRechazadoAprobacionesPers").removeClass("seleccionado");
					$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:2});
				});
				
				$('#btnRechazadoAprobacionesPers').click(function(){
					var idUsuario = $('#hdnIdUser').val();
					$('#btnPendienteAprobacionesPers').removeClass("seleccionado");
					$("#btnAceptadoAprobacionesPers").removeClass("seleccionado");
					$("#btnRechazadoAprobacionesPers").addClass("seleccionado");
					$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:3});
				});
				
				$('#btnAprobacionesInst').click(function(){
					$('#divAprobacionesInst').show();
					$('#over').show(500);
					$('#fade').show(500);
					$('#btnPendienteAprobacionesInst').click();
				});
				
				$('#btnAceptarBaja').click(function(){
					var idPersonaBaja = $('#hdnIdPersonaBaja').val();
					var idUsuario = $('#hdnIdUser').val(); 
					var motivo = $('#sltMotivoBaja').val()
					var comentarios = $('#txtComentariosBaja').val();
					if(motivo == ''){
						alert('Debe seleccionar un motivo!!!');
						return;
					}
					
					if(comentarios == ''){
						alert('Debe ingresar un comentario!!!');
						return;
					}
					$('#divRespuesta').load("ajax/eliminarMedico.php",{idPersona:idPersonaBaja,idUsuario:idUsuario,motivo:motivo,comentarios:comentarios});
					$('#hdnIdPersonaBaja').val('');
					$('#sltMotivoBaja').val('');
					$('#txtComentariosBaja').val('');
					$('#cerrarInformacion').click();
				});
				
				$('#btnCancelarBaja').click(function(){
					$('#hdnIdPersonaBaja').val('');
					$('#sltMotivoBaja').val('');
					$('#txtComentariosBaja').val('');
					$('#cerrarInformacion').click();
				});
				
				/* persona */
				
				$("#btnSleccionaInst").click(function(){
					$("#divBusqueda").show();
					$("#tblDatosInst").hide();
					palabra = $("#txtBusqueda").val('');
					$("#divRespuesta").load("ajax/instFiltradas.php",{palabra:'',idUsuario:'<?= $idUsuario ?>'});
				});
				
				$('#txtBusqueda').keyup(function() {
					palabra = $("#txtBusqueda").val();
					//alert(palabra);
					$("#divRespuesta").load("ajax/instFiltradas.php",{palabra:palabra,idUsuario:'<?= $idUsuario ?>'});
				});
				
				$('#btnCancelarPersonaNuevo').click(function(){
					$('#divPersona').hide();
					$('#divCapa3').hide();
					limpiaPersonaNuevo();
				});
				
				$('#btnCerrarBuscarInst').click(function(){
					$("#divBusqueda").hide();
					$("#tblDatosInst").show();
				});
				
				$('#btnGuardarPersonaNuevo').click(function(){
					
					if($('#sltTipoPersonaNuevo').val() == '0'){
						alert("Seleccione el tipo de persona!!!");
						$('#sltTipoPersonaNuevo').focus();
						return;
					}
					if($('#txtNombrePersonaNuevo').val() == ''){
						alert("Ingrese el nombre de la persona!!!");
						$('#txtNombrePersonaNuevo').focus();
						return;
					}
					if($('#txtPaternoPersonaNuevo').val() == ''){
						alert("Ingrese el apellido paterno de la persona!!!");
						$('#txtPaternoPersonaNuevo').focus();
						return;
					}
					if($('#sltEspecialidadPersonaNuevo').val() == '0'){
						alert("Seleccione la especialidad de la persona!!!");
						$('#sltEspecialidadPersonaNuevo').focus();
						return;
					}
					if($('#txtNombreInstPersonaNuevo').val() == ''){
						alert("Seleccione una institución!!!");
						$('#txtNombreInstPersonaNuevo').focus();
						return;
					}
					
					idInst = $('#hdnIdInstPersonaNuevo').val();
					tipoPersona = $('#sltTipoPersonaNuevo').val();
					nombre = $('#txtNombrePersonaNuevo').val();
					paterno = $('#txtPaternoPersonaNuevo').val();
					materno = $('#txtMaternoPersonaNuevo').val();
					sexo = $('#sltSexoPersonaNuevo').val();
					especialidad = $('#sltEspecialidadPersonaNuevo').val();
					subespecialidad = $('#sltSubEspecialidadPersonaNuevo').val();
					pacientesSemana = $('#sltPacientesSemanaPersonaNuevo').val();
					honorarios = $('#sltHonorariosPersonaNuevo').val();
					fecha = $('#txtFechaNacimientoPersonaNuevo').val();
					categoria = $('#sltCategoriaPersonaNuevo').val();
					cedula = $('#txtCedulaPersonaNuevo').val();
					frecuencia = $('#sltFrecuenciaPersonaNuevo').val();
					dificultadVisita = $('#txtDificultadVisitaPersonaNuevo').val();
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
					
					/*pasatiempo*/
					var cuanto = $('#hdnPasatiempo').val();
					var pasatiempoSelec = '';
					for(i=1;i<cuanto;i++){
						if($('#chk'+i).prop('checked')){
							pasatiempoSelec += $('#chk'+i).val() + ",";
						}
					}
					
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
					
					$("#divRespuesta").load("ajax/guardarPersona.php",{
						idPersona: $("#hdnIdPersona").val(),
						tipoPersona:tipoPersona,
						nombre:nombre,
						paterno:paterno,
						materno:materno,
						sexo:sexo,
						especialidad:especialidad,
						subespecialidad:subespecialidad,
						pacientesSemana:pacientesSemana,
						honorarios:honorarios,
						fecha:fecha,
						categoria:categoria,
						cedula:cedula,
						frecuencia:frecuencia,
						dificultadVisita:dificultadVisita,
						liderOpinion:liderOpinion,
						botiquin:botiquin,
						iguala:iguala,
						idInst:idInst,
						telefono:telefono,
						telefono1:telefono1,
						email:email,
						corto:corto,
						largo:largo,
						generales:generales,
						idUsuario:'<?= $idUsuario ?>',
						interior:numInterior,
						pasatiempo:pasatiempoSelec,
						horario:horario,
						lunesComentarios:lunesComentarios,
						martesComentarios:martesComentarios,
						miercolesComentarios:miercolesComentarios,
						juevesComentarios:juevesComentarios,
						viernesComentarios:viernesComentarios,
						sabadoComentarios:sabadoComentarios,
						domingoComentarios:domingoComentarios,
						abierto1:abierto1,
						abierto2:abierto2,
						abierto3:abierto3,
						telPersonal:telPersonal,
						mailPersonal:mailPersonal,
						torre:torre,
						piso:piso,
						consultorio:consultorio,
						departamento:departamento
					});
				});
				
				/* fin de persona */
				
				/*fin de personas*/
				
				
				
				/*instituciones */
				
				$('#imgAgregarInstitucion').click(function(){
					var ancho = 900;
					var alto = 600;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var idUsuario = $('#hdnIdUser').val();
					var ventana = window.open("institucion.php?idUsuario="+idUsuario, "vtnInstitucion", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				});
				
				$('#imgCancelarInstituciones').click(function(){
					$('#divFiltrosInstituciones').hide();
				});
				
				$('#btnAgregarDepartamento').click(function(){
					var ancho = 900;
					var alto = 400;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("departamento.php", "vtnInstitucion", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				});
				
				$('#imgAgregarPersonaInstituciones').click(function(){
					var ancho = 900;
					var alto = 500;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("persona.php", "vtnPersona", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				});
				
				
				$('#btnAgregarPersonaDatosInstituciones').click(function(){
					//$('#imgAgregarPersona').click();
					var idUsusario = $('#hdnIdUser').val();
					var idInst = $('#hdnIdInst').val();
					abrirVentanaPersona('persona.php?idUsuario='+idUsusario+'&idInst='+idInst,500,900);
				});
				
				$('#btnAgregarPersonaDepartamentoDatosInstituciones').click(function(){
					//$('#imgAgregarPersona').click();
					var ventana;
					var ancho = 1000;
					var alto = 300;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					if(!ventana || ventana.closed){
						ventana = window.open("agregarPersonas.php", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
					}else{
						ventana.focus( );
					}
				});
				
				$('#imgAgregarPlanInstituciones').click(function(){
					/*var ancho = 600;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);*/
					idInst = $('#hdnIdInst').val();
					idUsuario = $('#hdnIdUser').val();
					//var ventana = window.open("planesInst.php?idInst="+idInst+"&idUsuario="+idUsuario, "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
					$('#divPlanesInst').show();
					$('#divCapa3').show('slow');
					$('#divRespuesta').load("ajax/cargarPlanInst.php",{idInst:idInst,idUsuario:idUsuario});
				});
				
				$('#btnAgregarVisitaInstituciones').click(function(){
					var idInst = $('#hdnIdInst').val();
					idUsuario = $('#hdnIdUser').val();
					var ancho = 800;
					var alto = 600;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					//var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
					//var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
					var lat = '0.0';
					var lon = '0.0';
					/*var ventana = window.open("visitasInst.php?idInst="+idInst+"&lat="+lat+"&lon="+lon+"&idUsuario="+idUsuario, "vtnVisitas", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
					ventana.focus();*/
					$('#divRespuesta').load("ajax/cargarVisitaInst.php", {idInst:idInst,lat:lat,lon:lon,idUsuario:idUsuario});
					$('#divVisitasInst').show();
					$('#divCapa3').show('slow');
				});
				
				$('#imgFiltrar2Inst').click(function(){
					$('#trFiltrosInst').show('slow');
					$('#imgFiltrar2Inst').hide();
					$('#imgFiltrarInst').show();
				});
				
				$('#imgFiltrarInst').click(function(){
					$('#trFiltrosInst').hide('slow');
					$('#imgFiltrar2Inst').show();
					$('#imgFiltrarInst').hide();
				});
				
				$('#btnLimpiarFiltrosInst').click(function(){
					$('#sltTipoInstFiltro').val('');
					$('#txtNombreInstFiltro').val('');
					$('#txtCalleInstFiltro').val('');
					$('#txtColoniaInstFiltro').val('');
					$('#txtCiudadInstFiltro').val('');
					$('#txtEstadoFiltro').val('');
					$('#txtCPInstFiltro').val('');
				});
				
				$('#lstYearPlanesInst').change(function(){
					var idInst = $('#hdnIdInst').val();
					var idUsuario = $('#hdnIdUser').val();
					var year = $('#lstYearPlanesInst').val();
					$('#divRespuesta').load("ajax/cargarPlanesInst.php",{idInst:idInst,year:year,idUsuario:idUsuario});
				});
				
				$('#lstYearVisitasInst').change(function(){
					var idInst = $('#hdnIdInst').val();
					var idUsuario = $('#hdnIdUser').val();
					var year = $('#lstYearVisitasInst').val();
					$('#divRespuesta').load("ajax/cargarVisitasInst.php",{idInst:idInst,year:year,idUsuario:idUsuario});
				});
				
				$('#btnPendienteAprobacionesInst').click(function(){
					var idUsuario = $('#hdnIdUser').val();
					$('#btnPendienteAprobacionesInst').addClass("seleccionado");
					$("#btnAceptadoAprobacionesInst").removeClass("seleccionado");
					$("#btnRechazadoAprobacionesInst").removeClass("seleccionado");
					$('#divRespuesta').load("ajax/cargarAprobacionesInst.php",{idUsuario:idUsuario,estatus:1});
				});
				
				$('#btnAceptadoAprobacionesInst').click(function(){
					var idUsuario = $('#hdnIdUser').val();
					$('#btnPendienteAprobacionesInst').removeClass("seleccionado");
					$("#btnAceptadoAprobacionesInst").addClass("seleccionado");
					$("#btnRechazadoAprobacionesInst").removeClass("seleccionado");
					$('#divRespuesta').load("ajax/cargarAprobacionesInst.php",{idUsuario:idUsuario,estatus:2});
				});
				
				$('#btnRechazadoAprobacionesInst').click(function(){
					var idUsuario = $('#hdnIdUser').val();
					$('#btnPendienteAprobacionesInst').removeClass("seleccionado");
					$("#btnAceptadoAprobacionesInst").removeClass("seleccionado");
					$("#btnRechazadoAprobacionesInst").addClass("seleccionado");
					$('#divRespuesta').load("ajax/cargarAprobacionesInst.php",{idUsuario:idUsuario,estatus:3});
				});
				
				/*$("#btnGuardarPlanInst").click(function(){
					idPlan = $("#hdnIdPlanInst").val();
					fecha_plan = $("#txtFechaPlanInst").val();
					hora_plan = $("#lstHoraPlanInst").val() + ':' + $("#lstMinutosPlanInst").val();
					codigo_plan = $("#lstCodigoPlanInst").val();
					objetivo_plan = $("#objetivoPlanInst").val();
					idInst = $("#hdnIdInst").val();
					idUsuario = $("#hdnIdUser").val();
					pantalla = $("#hdnPantallaPlan").val();
					$("#divRespuesta").load("ajax/guardarPlanInst.php",{idPlan:idPlan,fechaPlan:fecha_plan,horaPlan:hora_plan,codigoPlan:codigo_plan,objetivoPlan:objetivo_plan,idInst:idInst,idUsuario:idUsuario,pantalla:pantalla});
				});
				
				$("#btnCancelarPlanInst").click(function(){
					$('#divPlanesInst').hide();
					$('#divCapa3').hide();
				});*/
				
				/*$("#btnReportarInst").click(function(){
					id_plan = $("#hdnId").val();
					fecha_plan = $("#fecha").val();
					hora_plan = $("#lstHora").val() + ':' + $("#lstMinutos").val();
					codigo_plan = $("#lstCodigoPlan").val();
					objetivo_plan = $("#objetivoPlan").val();
					idInst = $("#hdnInst").val();
					idUsuario = $("#hdnUsuario").val();
					vis = 1;
					pantalla = $("#hdnPantalla").val();
					$("#divRespuesta").load("ajax/guardarPlanInst.php",{idPlan:id_plan,fechaPlan:fecha_plan,horaPlan:hora_plan,codigoPlan:codigo_plan,objetivoPlan:objetivo_plan,idInst:idInst,idUsuario:idUsuario,vis:vis,pantalla:pantalla});
				});
				
				$('#btnEncuestaVisitasInst').click(function(){
					$('#divEncuesta').show();
					$('#tabs').hide();
				})
				
				$('#btnCancelarEncuestaVisitasInst').click(function(){
					$('#divEncuesta').hide();
					$('#tabs').show();
				});
				
				$('#txtFechaVisitasInst').change(function(){
					fecha = $('#fecha').val();
					ayo = fecha.substring(6,10);
					mes = fecha.substring(3,5);
					dia = fecha.substring(0,2);
					$('#fecha').val(ayo + '-' + mes + '-' + dia);
				});
				
				$('#btnSiguienteVisitaInst').click(function(){
					var ancho = 600;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("planesInst.php?idInst=", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				
				$('#btnGuardarVisitasInst').click(function (){
					var fecha = $('#fecha').val();
					var hora = $('#lstHora').val()+':'+$('#lstMinutos').val();
					var codigoVisita = $('#lstCodigoVisita').val();
					//var visitaAcompa = $('#lstVisitaAcompa').val();
					var comentariosVisita = $('#txtComentariosVisita').val();
					var infoSiguienteVisita = $('#txtInfoSiguienteVisita').val();
					var comentariosMedico = $('#txtComentariosMedico').val();
					var productosSeleccionados = '';
					var porcentajesProductosSeleccionados = '';
					var productosPromocionados = '';
					var cantidadProductosPromocionados = '';
					var lat = '';
					var lon = '';
					
					if(codigoVisita == 0){
						alert("Debe Seleccionar el código de la visita!!!");
						$('#lstCodigoVisita').focus();
						return;
					}
					if(comentariosVisita == ''){
						alert("Debe ingresar los comentarios de la visita");
						$("#txtComentariosVisita").focus();
						return;
					}
					for(var i=1;i<11;i++){
						if($("#lstProducto"+i).size() > 0){
							if($("#lstProducto"+i).val() != '00000000-0000-0000-0000-000000000000'){
								productosSeleccionados += $("#lstProducto"+i).val()+"|";
								porcentajesProductosSeleccionados += $("#txtProdPos"+i).val()+"|";
							}else{
								break;
							}
						}
					}
					if($("#hdnTotalPromociones").val() > 0){
						for(var j=1;j<$("#hdnTotalPromociones").val();j++){
							if($("#text"+j).val() > 0){
								productosPromocionados += $("#hdnIdProducto"+j).val()+"|";
								cantidadProductosPromocionados += $("#text"+j).val()+"|";
							}
						}
					}
					
					visitaAcompa = '';
					for(i=1;i<$("#hdnTotalChecks").val();i++){
						if($('#acompa'+i).prop('checked')){
							visitaAcompa += $('#acompa'+i).val() + ";";
						}
					}
					
					pantalla = $('#hdnPantalla').val();
					
					firma = $('#hdnFima').val();
					
					idVisita = $('#hdnIdVisita').val();
					idPlan = $('#hdnIdPlan').val();
					idInst = $('#hdnIdInst').val();
					idUsuario = $('#hdnIdUsuario').val();
					
					$("#respuesta").load("ajax/guardaVisitaInst.php",{idVisita:idVisita,idPlan:idPlan,idInst:idInst,idUsuario:idUsuario,fecha:fecha,hora:hora,codigoVisita:codigoVisita,visitaAcompa:visitaAcompa,comentariosVisita:comentariosVisita,infoSiguienteVisita:infoSiguienteVisita,comentariosMedico:comentariosMedico,productosSeleccionados:productosSeleccionados,productosPromocionados:productosPromocionados,cantidadProductosPromocionados:cantidadProductosPromocionados,lat:lat,lon:lon,pantalla:pantalla,porcentajesProductosSeleccionados:porcentajesProductosSeleccionados,firma:firma});
				});
				
				$("#btnCancelarVisitasInst").click(function(){
					$("#divVisitasInst").hide();
					$("#divCapa3").hide();
				});
				
				$("#btnGuardarFirmaVisitasInst").click(function(){
					var canvas = document.getElementById('firma');
					var dataURL = canvas.toDataURL();
					$("#hdnFima").val(dataURL);
					console.log(dataURL);
				});*/
				
				/*fin de instituciones*/
				
				/* calendario */
				
				$('#btnAgregarProductosSupervision').click(function(){
					abrirVentanaPersona('productos.php', 450, 500)
				});
				
				$('#txtBuscarPersona').keyup(function() {
					palabra = $("#txtBuscarPersona").val();
					dia = $("#hdnDiaCalendario").val();
					fecha = $("#hdnFechaCalendario").val();
					$("#divRespuesta").load("ajax/persFiltradas.php",{palabra:palabra,idUsuario:$('#hdnIdUser').val(),dia:dia,fecha:fecha});
				});
				
				$('#txtBuscarInst').keyup(function() {
					palabra = $("#txtBuscarInst").val();
					dia = $("#hdnDiaCalendario").val();
					fecha = $("#hdnFechaCalendario").val();
					$("#divRespuesta").load("ajax/InstFiltradas.php",{palabra:palabra,idUsuario:$('#hdnIdUser').val(),dia:dia,fecha:fecha});
				});
				
				$("#chkSeleccionarTodo").change(function(){
					total = $("#hdnTotalChecks").val();
					for(i=1;i<=total;i++){
						$('#repre'+i).prop('checked',$("#chkSeleccionarTodo").prop('checked'));
					}
				});
				
				$('#btnGuardarPeriodo').click(function(){
					$('#tdFechaOtrasActividades').show();
				});
				
				$('#btnPlanCalendario').click(function(){
					$("#btnPlanCalendario").addClass("seleccionado");
					$("#btnVisitaCalendario").removeClass("seleccionado");
					fecha = $("#hdnFechaCalendario").val();
					idUsuario = $('#sltRepreCalendario').val();
					var ids = $('#hdnIds').val();
					$('#divCalendarioCambia').load("ajaxCalendario.php", {fecha:fecha, idUsuario:idUsuario,planVisita:'plan',ids:ids});
				});
				
				$('#btnVisitaCalendario').click(function(){
					$("#btnPlanCalendario").removeClass("seleccionado");
					$("#btnVisitaCalendario").addClass("seleccionado");
					fecha = $("#hdnFechaCalendario").val();
					idUsuario = $('#sltRepreCalendario').val();
					var ids = $('#hdnIds').val();
					$('#divCalendarioCambia').load("ajaxCalendario.php", {fecha:fecha, idUsuario:idUsuario,planVisita:'visita',ids:ids});
				});
				
				$('#btnRuteo').click(function(){
					$('#divBuscarPersonas').hide();
					$('#divReportarOtrasActividades').hide();
					$('#divBuscarInst').hide();
					$('#divRuteo').show();
					$('#over').show(500);
					$('#fade').show(500);
					load_map('map_canvas3');
					fecha = $("#hdnFechaCalendario").val();
					idUsuario = $('#hdnIdUser').val();
					if($('#btnPlanCalendario').hasClass('seleccionado')){
						planVisita = 'plan';
						$('#btnPlanRuteo').addClass("seleccionado");
						$("#btnVisitaRuteo").removeClass("seleccionado");
					}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
						planVisita = 'visita';
						$('#btnPlanRuteo').removeClass("seleccionado");
						$("#btnVisitaRuteo").addClass("seleccionado");
					}
					$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
				});
				
				$('#btnCancelarOtrasActividades').click(function(){
					for(i=1;i<=$('#hdnTotalChkOA').val();i++){
						$('#chkOA'+i).attr('checked', false);
						$('#txtOA'+i).val('');
					}
					$('#txtTotalActividades').val('');
					$('#cerrarInformacion').click();
				});
				
				$('#btnGuardarOtrasActividades').click(function(){
					var actividades = '';
					var horas;
					var totalChkOA = $('#hdnTotalChkOA').val();
					var totalHoras = $('#txtTotalActividades').val();
					var idUsuario = $('#hdnIdUser').val();
					var fecha = $('#txtFechaReportarOtrasActividades').val();
					var fechaFin = $('#txtFechaReportarOtrasActividadesFin').val();
					var comentarios = $('#txtComentariosOtrasActividades').val();
					if(totalHoras > 8){
						alert("el máximo a reportar son 8 Hrs.");
						return true;
					}
					for(i=1;i<=totalChkOA;i++){
						if($('#chkOA'+i).prop('checked')){
							if(actividades == ''){
								actividades = $('#chkOA'+i).val();
								horas = $('#txtOA'+i).val();
							}else{
								actividades += ',' + $('#chkOA'+i).val();
								horas += "," + $('#txtOA'+i).val();
							}
						}
					}
					$('#divRespuesta').load("ajax/guardaOtrasActividades.php",{fecha:fecha,idUsuario:idUsuario,comentarios:comentarios,actividades:actividades,horas:horas,fechaFin:fechaFin});
					$('#btnCancelarOtrasActividades').click();
				});
				
				$('#sltRepreCalendario').change(function(){
					idRepre = $('#sltRepreCalendario').val();
					var ids = $('#hdnIds').val();
					if($('#btnPlanCalendario').hasClass('seleccionado')){
						planVisita = 'plan';
					}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
						planVisita = 'visita';
					}
					$('#divCalendarioCambia').load("ajaxCalendario.php", {fecha:$('#hdnFechaCalendario').val(), idUsuario:idRepre, planVisita:planVisita,ids:ids});
				});
      
				/* fin de calendario */
				
				/* planes */
				$("#btnGuardarPlan").click(function(){
					id_plan = $("#hdnIdPlan").val();
					fecha_plan = $("#fechaPlan").val();
					hora_plan = $("#lstHoraPlan").val() + ':' + $("#lstMinutosPlan").val();
					codigo_plan = $("#lstCodigoPlan").val();
					objetivo_plan = $("#objetivoPlan").val();
					pantalla = $("#hdnPantallaPlan").val();
					idPersona = $("#hdnIdPersona").val();
					idUsuario = $("#hdnIdUser").val();
					$("#divRespuesta").load("ajax/guardarPlan.php",{idPlan:id_plan,fechaPlan:fecha_plan,horaPlan:hora_plan,codigoPlan:codigo_plan,objetivoPlan:objetivo_plan,idPersona:idPersona,idUsuario:idUsuario,pantalla:pantalla});
				});
				
				$("#btnCancelarPlan").click(function(){
					$("#divPlanes").hide();
					$("#divCapa3").hide();
					//window.close();
				});
				
				$("#btnReportarPlan").click(function(){
					id_plan = $("#hdnIdPlan").val();
					fecha_plan = $("#fecha").val();
					hora_plan = $("#lstHora").val() + ':' + $("#lstMinutos").val();
					codigo_plan = $("#lstCodigoPlan").val();
					objetivo_plan = $("#objetivoPlan").val();
					vis = 1;
					pantalla = $("#hdnPantalla").val();
					idPersona = $("#hdnIdPersona").val();
					idUsuario = $("#hdnIdUser").val();
					$("#divRespuesta").load("ajax/guardarPlan.php",{idPlan:id_plan,fechaPlan:fecha_plan,horaPlan:hora_plan,codigoPlan:codigo_plan,objetivoPlan:objetivo_plan,idPersona:idPersona,vis:vis,pantalla:pantalla,idUsuario:idUsuario});
				});
				/* fin de planes */
				
				/* visitas */
				
				$('#btnEncuesta').click(function(){
					$('#divEncuesta').show();
					$('#tabsVisitas').hide();
				});
				
				$('#btnCancelarEncuesta').click(function(){
					$('#divEncuesta').hide();
					$('#tabsVisitas').show();
				});
				
				$('#txtFechaVisita').change(function(){
					fecha = $('#txtFechaVisita').val();
					ayo = fecha.substring(6,10);
					mes = fecha.substring(3,5);
					dia = fecha.substring(0,2);
					$('#txtFechaVisita').val(ayo+'-'+mes+'-'+dia);
				});
				
				$('#btnSiguienteVisita').click(function(){
					var ancho = 600;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					//var ventana = window.open("planes.php?idPersona=", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				
				$('#btnCancelarVisitas').click(function(){
					$('#divVisitas').hide();
					$('#divCapa3').hide();
				});
				
				$('#btnGuardarVisitas').click(function (){
					//idVisita:'',
					//idPlan:'',
					var idPers = $('#hdnIdPersona').val();
					var idUsuario = $('#hdnIdUser').val();
					var fecha = $('#txtFechaVisita').val();
					var hora = $('#lstHoraVisita').val()+':'+$('#lstMinutosVisita').val();
					var codigoVisita = $('#lstCodigoVisita').val();
					//var visitaAcompa = $('#lstVisitaAcompa').val();
					var comentariosVisita = $('#txtComentariosVisita').val();
					var infoSiguienteVisita = $('#txtInfoSiguienteVisita').val();
					var comentariosMedico = $('#txtComentariosMedico').val();
					var productosSeleccionados = '';
					var porcentajesProductosSeleccionados = '';
					var productosPromocionados = '';
					var cantidadProductosPromocionados = '';
					var lat = '';
					var lon = '';
					if(codigoVisita == 0){
						alert("Debe Seleccionar el código de la visita!!!");
						$('#lstCodigoVisita').focus();
						return;
					}
					if(comentariosVisita == ''){
						alert("Debe ingresar los comentarios de la visita");
						$("#txtComentariosVisita").focus();
						return;
					}
					
					for(var i=1;i<11;i++){
						if($("#lstProducto"+i).size() > 0){
							if($("#lstProducto"+i).val() != '00000000-0000-0000-0000-000000000000'){
								productosSeleccionados += $("#lstProducto"+i).val()+"|";
								porcentajesProductosSeleccionados += $("#txtProdPos"+i).val()+"|";
							}else{
								break;
							}
						}
					}
					if($("#hdnTotalPromociones").val() > 0){
						for(var j=1;j<$("#hdnTotalPromociones").val();j++){
							if($("#text"+j).val() > 0){
								productosPromocionados += $("#hdnIdProducto"+j).val()+"|";
								cantidadProductosPromocionados += $("#text"+j).val()+"|";
								existencia = $("#hdnExistencia"+j).val();
								maximo = $("#hdnMaximo"+j).val();
								cantidad = $("#text"+j).val();
								if(parseInt(cantidad, 10) > parseInt(existencia, 10)){
									alert("Solo tienes "+existencia+" piezas de ese producto!!!");
									return;
								}
								if(parseInt(cantidad, 10) > parseInt(maximo, 10)){
									alert("Sólo puede entregar "+maximo+" piezas de ese producto!!!");
									return;
								}
							}
						}
					}
					//$('#btnGuardar').hide();
					/*if(visitaAcompa == 0){
						visitaAcompa = '00000000-0000-0000-0000-000000000000';
					}*/
					visitaAcompa = '';
					for(i=1;i<$("#hdnTotalChecksVisitas").val();i++){
						if($('#acompa'+i).prop('checked')){
							visitaAcompa += $('#acompa'+i).val() + ";";
						}
					}
					//alert(visitaAcompa);
					pantalla = '';
					
					firma = $('#hdnFirma').val();
					
					$("#divRespuesta").load("ajax/guardaVisitaPersona.php",{idVisita:'',idPlan:'',idPers:idPers,idUsuario:idUsuario,fecha:fecha,hora:hora,codigoVisita:codigoVisita,visitaAcompa:visitaAcompa,comentariosVisita:comentariosVisita,infoSiguienteVisita:infoSiguienteVisita,comentariosMedico:comentariosMedico,productosSeleccionados:productosSeleccionados,productosPromocionados:productosPromocionados,cantidadProductosPromocionados:cantidadProductosPromocionados,lat:lat,lon:lon,pantalla:pantalla,porcentajesProductosSeleccionados:porcentajesProductosSeleccionados,firma:firma});
				});
				
				
				
				/* fin visitas */
				
				/*ruteo */
				
				$('#btnPlanRuteo').click(function(){
					$("#btnPlanRuteo").addClass("seleccionado");
					$("#btnVisitaRuteo").removeClass("seleccionado");
					fecha = $("#hdnFechaRuteo").val();
					idUsuario = $('#hdnIdUser').val();
					planVisita = 'plan';
					$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
				});
				
				$('#btnVisitaRuteo').click(function(){
					$("#btnVisitaRuteo").addClass("seleccionado");
					$("#btnPlanRuteo").removeClass("seleccionado");
					fecha = $("#hdnFechaRuteo").val();
					idUsuario = $('#hdnIdUser').val();
					planVisita = 'visita';
					$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
				});
				
				$('#sltMesCal').change(function(){
					month = $('#sltMesCal').val();
					year = $('#sltYearCal').val();
					day = $('#hdnFechaRuteo').val().split("-")[2];
					$('#calendarioRuteo').load("ajax/cambiaCalendarioRuteo.php",{month:month,year:year,day:day});
				});
				
				$('#sltYearCal').change(function(){
					month = $('#sltMesCal').val();
					year = $('#sltYearCal').val();
					day = $('#hdnFechaRuteo').val().split("-")[2];
					$('#calendarioRuteo').load("ajax/cambiaCalendarioRuteo.php",{month:month,year:year,day:day});
				});
				
				/* fin ruteo */
				
				/********* radar ********/
				
				$('#btnLocalizarFiltro').click(function(){
					idUsuario = $('#hdnIdUser').val();
					km = $('#sltKilometros').val();
					vis = $('#sltVisitas').val();
					esp = $('#sltEspecialidadRadar').val();
					tipo = $('#sltTipo').val();
					tipoIns = $('#sltTipoInst').val();
					lat = $('#txtLatitud').val();
					lon = $('#txtLongitud').val();
					$('#divRespuesta').load("ajax/marcadoresGeolocalizacion.php",{km:km,planVisita:vis,esp:esp,tipo:tipo,tipoIns:tipoIns,lat:lat,lon:lon,idUsuario:idUsuario});
				});
				
				/*******termina radar *********/
				
				/***********inventario*********/

				$('#btnPendienteAprobacion').click(function(){
					$('#btnPendienteAprobacion').addClass("seleccionado");
					$("#btnAceptadoInv").removeClass("seleccionado");
					$("#btnRechazadoInv").removeClass("seleccionado");
					$("#divRespuesta").load("ajax/cargarInventario.php",{pendiente:'0',idUsuario:$('#hdnIdUser').val()});
				});
				
				$('#btnAceptadoInv').click(function(){
					$('#btnPendienteAprobacion').removeClass("seleccionado");
					$("#btnAceptadoInv").addClass("seleccionado");
					$("#btnRechazadoInv").removeClass("seleccionado");
					$("#divRespuesta").load("ajax/cargarInventario.php",{pendiente:'1',idUsuario:$('#hdnIdUser').val()});
				});
				
				$('#btnRechazadoInv').click(function(){
					$('#btnPendienteAprobacion').removeClass("seleccionado");
					$("#btnAceptadoInv").removeClass("seleccionado");
					$("#btnRechazadoInv").addClass("seleccionado");
					$("#divRespuesta").load("ajax/cargarInventario.php",{pendiente:'2',idUsuario:$('#hdnIdUser').val()});
				});
				
				$('#btnAjustarInv').click(function(){
					var idProdForm = $("#hdnIdProductoAjuste").val();
					var ancho = 380;
					var alto = 290;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventanaAjusteInv = window.open("ajusteMaterial.php?idProdForm="+idProdForm, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
					ventanaAjusteInv.focus();
				});
				$('#btnAceptarAjusteInv').click(function(){
					var idProdForm = $("#hdnIdProductoAjuste").val();
					$("#divRespuesta").load("ajax/guardaAjusteMaterial.php",{idProdForm:idProdForm,cantidad:'',catidadAceptada:'',motivo:'',movimiento:'aceptado'});
				});
				$('#btnCancelarAjusteInv').click(function(){
					var idProdForm = $("#hdnIdProductoAjuste").val();
					$("#divRespuesta").load("ajax/guardaAjusteMaterial.php",{idProdForm:idProdForm,cantidad:'',catidadAceptada:'',motivo:'',movimiento:'rechazado'});
				});
				
				/***********termina inventario*********/
				
				$('#imgCancelar').click(function(){
					$('#trFiltros').hide();
				});
				
				$('#imgBuscar').click(function(){
					if($('#trSubmenuPerosnas').is (':visible')){
						$('#trSubmenuPerosnas').hide('slow');
					}else{
						$('#trSubmenuPerosnas').show('slow');
					}
				});
				
				$('#imgCerrar').click(function(){
					$('#trSubmenuPerosnas').hide('slow');
				});
				
				$('#imgFlechaAtras').click(function(){
					$('#divPersonasDatos').hide();
					$('#divPersonas').show();
				});
				
				$('#imgFiltrar').click(function(){
					$('#trFiltros').show();
				});
				
				$('#cerrarInformacion').click(function(){
					$('#over').hide();
					$('#fade').hide();
					$('#divDatosPersonales').hide();
					$('#divDatosInstituciones').hide();
					$('#divPlanearOtrasActividades').hide();
					$('#divMensaje').hide();
					$('#divSupervision').hide();
					$('#divReportarOtrasActividades').hide();
					$('#divBuscarPersonas').hide();
					$('#divBuscarInst').hide();
					$('#divRuteo').hide();
					$('#divConfirmacionInventario').hide();
					$('#divAprobacionesPers').hide();
					$('#divAprobacionesInst').hide();
					$('#divAprobacion').hide();
					$('#divMotivoBaja').hide();
				});
				
				$('#imgAbrirOpcionesCalendario').click(function(){
					$('#tblOpcionesCalendario').show(1000);
					$('#imgAbrirOpcionesCalendario').hide();
					$('#imgCerrarOpcionesCalendario').show();
				});
				
				$('#imgCerrarOpcionesCalendario').click(function(){
					$('#tblOpcionesCalendario').hide(1000);
					$('#imgAbrirOpcionesCalendario').show();
					$('#imgCerrarOpcionesCalendario').hide();
				});
				
				/*ciclos*/
				
				$('#imgAgregaCiclo').click(function(){
					$('#divDatosPersonales').hide();
					$('#divDatosInstituciones').hide();
					$('#divPlanearOtrasActividades').show();
					$('#divMensaje').hide();
					$('#over').show(10);
					$('#fade').show(10);
				});
				
				/*fin de ciclos*/
				
				/*mensajes*/
				$('#imgApareceBuscar').click(function(){
					$('#trBuscarMensajes').show(1000);
					$('#imgOcultaBuscar').show();
					$('#imgApareceBuscar').hide();
				});
				
				$('#imgOcultaBuscar').click(function(){
					$('#trBuscarMensajes').hide(1000);
					$('#imgOcultaBuscar').hide();
					$('#imgApareceBuscar').show();
				});
				
				$('#btnNuevoMensje').click(function(){
					$('#divDatosPersonales').hide();
					$('#divDatosInstituciones').hide();
					$('#divPlanearOtrasActividades').hide();
					$('#divMensaje').show();
					$('#over').show(1000);
					$('#fade').show(1000);
				});
				
				$('#imgContactos').click(function(){
					var ancho = 350;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("contactos.php", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				/*fin mensajes*/
				
				/*aprobaciones*/
				$('#btnAceptarAprobacion').click(function(){
					idUser = $('#hdnIdUser').val();
					idPersApproval = $('#hdnIdPersApproval').val();
					idInstApproval = $('#hdnIdInstApproval').val();
					if(idPersApproval != ''){
						$("#divRespuesta").load("ajax/persAprobacion.php",{idPersApproval:idPersApproval,idUser:idUser});
					}else if(idInstApproval != ''){
						$("#divRespuesta").load("ajax/instAprobacion.php",{idInstApproval:idInstApproval,idUser:idUser});
					}
					$("#cerrarInformacion").click();
					$('#imgAprobaciones').click();
				});
				
				$('#btnRechazarAprobacion').click(function(){
					$("#divMotivoRechazo").show('slow');
					$('#imgAprobaciones').click();
				});
				
				$("#btnAceptarRechazo").click(function(){
					var motivo = $("#sltMotivoRechazo").val();
					idPersApproval = $('#hdnIdPersApproval').val();
					idInstApproval = $('#hdnIdInstApproval').val();
					idUser = $('#hdnIdUser').val();
					if(motivo == '00000000-0000-0000-0000-000000000000'){
						alert('Debe seleccionar un motivo!!!');
						return true;
					}
					if(idPersApproval != ''){
						tabla = 'personas';
					}else if(idInstApproval != ''){
						tabla = 'inst';
					}
					$("#divRespuesta").load("ajax/rechazoAprobacion.php",{idPersApproval:idPersApproval,idInstApproval:idInstApproval,motivo:motivo,idUsuario:idUser,tabla:tabla});
					$("#divMotivoRechazo").hide();
					$("#cerrarInformacion").click();
					$('#imgAprobaciones').click();
				});
				
				$("#btnCancelarRechazo").click(function(){
					$("#sltMotivoRechazo").val('');
					$("#divMotivoRechazo").hide('slow');
				});
				
				$("#sltRutasAprobacionesGerentePers").change(function (){
					ids = $('#hdnIds').val();
					ruta = $("#sltRutasAprobacionesGerentePers").val();
					estatus = '';
					if($("#btnPendienteAprobacionesGerentePers").hasClass('seleccionado')){
						estatus = 1;
					}else if($("#btnAceptadoAprobacionesGerentePers").hasClass('seleccionado')){
						estatus = 2;
					}else if($("#btnRechazadoAprobacionesGerentePers").hasClass('seleccionado')){
						estatus = 3;
					}
					$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:estatus});
				});
				$("#btnPendienteAprobacionesGerentePers").click(function(){
					ruta = $("#sltRutasAprobacionesGerentePers").val();
					$("#btnPendienteAprobacionesGerentePers").addClass("seleccionado");
					$("#btnAceptadoAprobacionesGerentePers").removeClass("seleccionado");
					$("#btnRechazadoAprobacionesGerentePers").removeClass("seleccionado");
					$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:'1'});
				});
				$("#btnAceptadoAprobacionesGerentePers").click(function(){
					ruta = $("#sltRutasAprobacionesGerentePers").val();
					$("#btnPendienteAprobacionesGerentePers").removeClass("seleccionado");
					$("#btnAceptadoAprobacionesGerentePers").addClass("seleccionado");
					$("#btnRechazadoAprobacionesGerentePers").removeClass("seleccionado");
					$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:'2'});
				});
				$("#btnRechazadoAprobacionesGerentePers").click(function(){
					ruta = $("#sltRutasAprobacionesGerentePers").val();
					$("#btnPendienteAprobacionesGerentePers").removeClass("seleccionado");
					$("#btnAceptadoAprobacionesGerentePers").removeClass("seleccionado");
					$("#btnRechazadoAprobacionesGerentePers").addClass("seleccionado");
					$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:'3'});
				});
				
				/*if($('#btnPlanCalendario').hasClass('seleccionado')){
					planVisita = 'plan';
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					planVisita = 'visita';
				}*/
				
				/* fin aprobaciones */
				
				
				
				$('#lkMapa').click(function(){
					load_map('map_canvas');
					$('#txtCanvas').val('map_canvas');
				});
				
				$('#lkMapaInstituciones').click(function(){
					load_map('map_canvas2');
					$('#txtCanvas').val('map_canvas2');
				});
				
				$('#lkVisitas').click(function(){
					if($('#lblLatitudPersonas').text() == '' && $('#lblLongitudPersonas').text() == ''){
						load_map('map_canvas2');
						$('#txtCanvas').val('map_canvas2');
					}
				});
				
				$('#imgLogout').click(function(){
					$(location).attr('href','index.php');
				});
				
				/*configuracion*/
				
				$('#btnGuardarClave').click(function(){
					var clave = $('#txtClave').val();
					var repClave = $('#txtRepetirClave').val();
					var idUsuario = $('#hdnIdUser').val();
					if(clave != repClave){
						alert('Las contraseñas no coinciden');
						return;
					}
					$('#divRespuesta').load("ajax/actualizaClave.php",{clave:clave,idUsuario:$('#hdnIdUser').val()});
				});
				
				$('#btnCancelarClave').click(function(){
					$('#txtClave').val('');
					$('#txtRepetirClave').val('');
					$('#seguridad').hide();
				});
				
				/*fin de configuracion*/
				
			});	/********termina document ready  *********/	

			var vtnPersona = false;
			function abrirVentanaPersona(url, alto, ancho){					
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				if(! vtnPersona || vtnPersona.closed){
					vtnPersona = window.open(url, "vtnPersona", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				}else{
					vtnPersona.focus();
				}
			}
			
			/*calendario*/
			function abreSupervision(){
				$('#divSupervision').show();
				$('#over').show(500);
				$('#fade').show(500);
			}
			
			function abreOtrasActividades(dia,fecha){
				date = fecha.substring(8,10)+'/'+fecha.substring(5,7)+'/'+fecha.substring(0,4);
				$('#divBuscarPersonas').hide();
				$('#divBuscarInst').hide();
				$('#divReportarOtrasActividades').show();
				$('#over').show(500);
				$('#fade').show(500);
				$('#txtFechaReportarOtrasActividades').val(date);
			}
			
			function abreBuscarPersona(dia,fecha){
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					if(fecha < '<?= date("Y-m-d") ?>'){
						alert('No puede planear en fechas atrasadas!!!');
						return;
					}
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					if(fecha > '<?= date("Y-m-d") ?>'){
						alert('No puede reportar en esa fecha!!!');
						return;
					}
				}
				$("#hdnDiaCalendario").val(dia);
				$("#txtBuscarPersona").val('');
				$("#divRespuesta").load("ajax/persFiltradas.php",{palabra:'',idUsuario:$('#hdnIdUser').val(),dia:dia,fecha:fecha});
				$('#divBuscarPersonas').show();
				$('#over').show(500);
				$('#fade').show(500);
			}
			
			function abreBuscarInst(dia, fecha){
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					if(fecha < '<?= date("Y-m-d") ?>'){
						alert('No puede planear en fechas atrasadas!!!');
						return;
					}
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					if(fecha > '<?= date("Y-m-d") ?>'){
						alert('No puede reportar en esa fecha!!!');
						return;
					}
				}
				$("#hdnDiaCalendario").val(dia);
				$("#txtBuscarPersona").val('');
				$("#divRespuesta").load("ajax/instFiltradas.php",{palabra:'',idUsuario:$('#hdnIdUser').val(),dia:dia,fecha:fecha});
				$('#divBuscarPersonas').hide();
				$('#divReportarOtrasActividades').hide();
				$('#divBuscarInst').show();
				$('#over').show(500);
				$('#fade').show(500);
			}
			
			function persSeleccionada(nombre, dia){
				$('#tbl'+dia).append('<tr><td>P: '+nombre+'</td></tr>');
				$('#cerrarInformacion').click(); 
			}
			
			function nuevoPlan(idPersona, dia, fecha){
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					var ancho = 600;
					var alto = 550;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("planes.php?idPersona="+idPersona+"&fechaPlan="+fecha+"&pantalla=cal", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					var ancho = 800;
					var alto = 600;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
					var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
					var ventana = window.open("visitas.php?idPersona="+idPersona+"&lat="+lat+"&lon="+lon+"&pantalla=cal", "vtnVisitas", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				}
				ventana.focus();
				$('#cerrarInformacion').click(); 
			}
			
			function nuevoPlanInst(idInst, dia, fecha){
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					var ancho = 600;
					var alto = 550;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("planesInst.php?idInst="+idInst+"&fechaPlan="+fecha+"&idUsuario="+$('#hdnIdUser').val()+"&pantalla=cal", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					var ancho = 800;
					var alto = 600;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
					var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
					var ventana = window.open("visitasInst.php?idInst="+idInst+"&lat="+lat+"&lon="+lon+"&fechaPlan="+fecha+"&idUsuario="+$('#hdnIdUser').val()+"&pantalla=cal", "vtnVisitas", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				}
				ventana.focus();
				$('#cerrarInformacion').click(); 
			}
			
			function traePlanesVisitasDia(dia, fecha){
				$("#divRespuesta").load("ajax/planesVisitasCalendario.php",{dia:dia,fecha:fecha,idUsuario:$('#hdnIdUser').val()});
			}
			
			function muestraOtrasActividades(id){
				$("#divRespuesta").load("ajax/planesVisitasCalendario.php",{dia:dia,fecha:fecha,idUsuario:$('#hdnIdUser').val()});
			}
				
			//traePlanesVisitasDia('DiaUno', '2017-09-08');
	/**/
			/*fin de calendario*/
			
			/*mapa*/
			
			/*mi ubicacion*/
			var options = {
				enableHighAccuracy: true,
				timeout: 5000,
				maximumAge: 0
			};

			function success(pos) {
				var crd = pos.coords;

				console.log('Your current position is:');
				console.log('Latitude : ' + crd.latitude);
				console.log('Longitude: ' + crd.longitude);
				console.log('More or less ' + crd.accuracy + ' meters.');
				lat = crd.latitude;
				lon = crd.longitude;
				console.log(lat + ' ::: ' + lon);
			};

			function error(err) {
				console.warn('ERROR(' + err.code + '): ' + err.message);
			};
				
			navigator.geolocation.getCurrentPosition(success, error, options);
			/**************/
			
			
			var map;
			var infoWindow = null;
			var geocoder = new google.maps.Geocoder();
			var marker = new google.maps.Marker();
			var infowindow = new google.maps.InfoWindow();
			
			function getDireccion(lat, lng){
				var latlng = new google.maps.LatLng(lat, lng);
				geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							map.fitBounds(results[0].geometry.viewport);
							marker.setMap(map);
							marker.setPosition(latlng);
							$('#addressF').val(results[0].formatted_address);
							infowindow.setContent(results[0].formatted_address);
							infowindow.open(map, marker);
							google.maps.event.addListener(marker, 'click', function(){
								infowindow.setContent(results[0].formatted_address);
								infowindow.open(map, marker);
							});
						} else {
							alert('No results found');
						}
					} else {
						alert('Geocoder failed due to: ' + status);
					}
				});
			}
			
			function openInfoWindow(marker) {
				var markerLatLng = marker.getPosition();
				$('#txtLatitud').val(markerLatLng.lat());
				$('#txtLongitud').val(markerLatLng.lng());
				$('#txtLatitudInstituciones').val(markerLatLng.lat());
				$('#txtLongitudInstituciones').val(markerLatLng.lng());
				$('#lblLatitudPersonas').text('Latitud: '+markerLatLng.lat());
				$('#lblLongitudPersonas').text('Longitud: '+markerLatLng.lng());
				getDireccion(markerLatLng.lat(), markerLatLng.lng());
			}
			
			function load_map(map) {
				//alert(map);
<?php
				$ip  = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
				$url = "http://freegeoip.net/json/$ip";
				$ch  = curl_init();

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$data = curl_exec($ch);
				curl_close($ch);

				if ($data) {
					$location = json_decode($data);

					$lat = $location->latitude;
					$lon = $location->longitude;

					$sun_info = date_sun_info(time(), $lat, $lon);
					//print_r($sun_info);
				}
?>
				var myLatlng = new google.maps.LatLng(<?= $lat ?>, <?= $lon ?>);
				var myOptions = {
					zoom: 4,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map = new google.maps.Map($("#"+map).get(0), myOptions);
				//map = new google.maps.Map($("#map_canvas2").get(0), myOptions);
				//map2 = new google.maps.Map($('#map_convas2').get(0), myOptions);
				var address = 'Insurgentes sur 670';
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({ 'address': address}, geocodeResult);
				$('#lblLatitudPersonas').text('Latitud: <?= $lat ?>');
				$('#lblLongitudPersonas').text('Longitud: <?= $lon ?>');
				$('#txtLatitud').val('<?= $lat ?>');
				$('#txtLongitud').val('<?= $lon ?>');
			}
			
			 
			/*$('#search').live('click', function() {
				// Obtenemos la dirección y la asignamos a una variable
				var address = $('#address').val();
				// Creamos el Objeto Geocoder
				var geocoder = new google.maps.Geocoder();
				// Hacemos la petición indicando la dirección e invocamos la función
				// geocodeResult enviando todo el resultado obtenido
				geocoder.geocode({ 'address': address}, geocodeResult);
			});*/
			
		
			
			/*$('#searchInstituciones').live('click', function() {
				// Obtenemos la dirección y la asignamos a una variable
				var address = $('#addressInstituciones').val();
				// Creamos el Objeto Geocoder
				var geocoder = new google.maps.Geocoder();
				// Hacemos la petición indicando la dirección e invocamos la función
				// geocodeResult enviando todo el resultado obtenido
				geocoder.geocode({ 'address': address}, geocodeResult);
			});*/
			 
			function geocodeResult(results, status) {
				var canvas = $('#txtCanvas').val();
				// Verificamos el estatus
				if (status == 'OK') {
					// Si hay resultados encontrados, centramos y repintamos el mapa
					// esto para eliminar cualquier pin antes puesto
					var mapOptions = {
						center: results[0].geometry.location,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					
					map = new google.maps.Map($("#"+canvas).get(0), mapOptions);
					// fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
					map.fitBounds(results[0].geometry.viewport);
					// Dibujamos un marcador con la ubicación del primer resultado obtenido
					var markerOptions = { position: results[0].geometry.location, draggable: true }
					var marker = new google.maps.Marker(markerOptions);
					marker.setMap(map);
					
					infoWindow = new google.maps.InfoWindow();
			
					google.maps.event.addListener(marker, 'dragend', function(){
						openInfoWindow(marker);
					});
					
				} else {
					// En caso de no haber resultados o que haya ocurrido un error
					// lanzamos un mensaje con el error
					alert("Geocoding no tuvo éxito debido a: " + status);
				}
			}
			/* fin de mapa*/
			
			/*radar*/
			/*function initialize() {
				var marcadores = [
					['León', 42.603, -5.577],
					['Salamanca', 40.963, -5.669],
					['Zamora', 41.503, -5.744]
				];
				
				var map = new google.maps.Map(document.getElementById('mapa'), {
					zoom: 7,
					center: new google.maps.LatLng(41.503, -5.744),
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				
				var infowindow = new google.maps.InfoWindow();
				var marker, i;
				for (i = 0; i < marcadores.length; i++) {  
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
						map: map
					});
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infowindow.setContent(marcadores[i][0]);
							infowindow.open(map, marker);
						}
					})(marker, i));
				}
			}*/
			/*termina radar*/
			
			/*personas */
			function presentaDatos(id, div, medico, especialidad){
				$('#divDatosPersonales').hide();
				$('#divDatosInstituciones').hide();
				$('#divPlanearOtrasActividades').hide();
				$('#divMensaje').hide();
				$('#'+div).show();
				$('#over').show(500);
				$('#fade').show(500);
				$('#lblEspecialidad1').text(especialidad);
				$('#lblNombreMedico1').text(medico);
				$('#lblEspecialidad2').text(especialidad);
				$('#lblNombreMedico2').text(medico);
				$('#lblEspecialidad3').text(especialidad);
				$('#lblNombreMedico3').text(medico);
				$('#lblEspecialidad4').text(especialidad);
				$('#lblNombreMedico4').text(medico);
				$('#lblEspecialidad5').text(especialidad);
				$('#lblNombreMedico5').text(medico);
				$('#lblEspecialidad6').text(especialidad);
				$('#lblNombreMedico6').text(medico);
				$('#lblEspecialidad7').text(especialidad);
				$('#lblNombreMedico7').text(medico);
				$('#lblEspecialidad8').text(especialidad);
				$('#lblNombreMedico8').text(medico);
				$('#lblEspecialidad9').text(especialidad);
				$('#lblNombreMedico9').text(medico);
				$('#lblEspecialidad10').text(especialidad);
				$('#lblNombreMedico10').text(medico);
				$('#lblEspecialidad11').text(especialidad);
				$('#lblNombreMedico11').text(medico);
				if(div == 'divDatosPersonales'){
					$("#divRespuesta").load("ajax/cargarPersona.php",{id:id});
				}
				if(div == 'divDatosInstituciones'){
					$("#divRespuesta").load("ajax/cargarInstitucion.php",{id:id,idUsuario:$('#hdnIdUser').val()});
				}
			}
			
			function nuevaPagina(pagina,hoy,ids,visitados){
				$('#hdnPaginaPersonas').val(pagina);
				tipoPersona = $('#sltTipoPersonaFiltro').val();
				nombre = $('#txtNombreFiltro').val();
				apellidos = $('#txtApellidosFiltro').val();
				sexo = $('#sltSexoFiltro').val();
				especialidad = $('#sltEspecialidadFiltro').val();
				categoria = $('#sltCategoriaFiltro').val();
				inst = $('#txtInstitucionFiltro').val();
				dir = $('#txtDireccionFiltro').val();
				del = $('#txtDelegacionFiltro').val();
				estado = $('#txtEstadoFiltro').val();
				$("#tbPersonas").load("ajax/cargarTablaPersonas.php",{pagina:pagina,hoy:hoy,ids:ids,visitados:visitados,tipoPersona:tipoPersona,nombre:nombre,apellidos:apellidos,sexo:sexo,especialidad:especialidad,categoria:categoria,inst:inst,dir:dir,del:del,estado:estado});
			}
			
			function exportarExcelPersonas(hoy,ids){
				var ancho = 350;
				var alto = 450;
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				visitados = $('#hdnFiltrosExportar').val();
				tipoPersona = $('#sltTipoPersonaFiltro').val();
				nombre = $('#txtNombreFiltro').val();
				apellidos = $('#txtApellidosFiltro').val();
				sexo = $('#sltSexoFiltro').val();
				especialidad = $('#sltEspecialidadFiltro').val();
				categoria = $('#sltCategoriaFiltro').val();
				inst = $('#txtInstitucionFiltro').val();
				dir = $('#txtDireccionFiltro').val();
				del = $('#txtDelegacionFiltro').val();
				estado = $('#txtEstadoFiltro').val();
				variables = "hoy="+hoy+"&ids="+ids+"&visitados="+visitados+"&tipoPersona="+tipoPersona+"&nombre="+nombre+"&apellidos="+apellidos+"&sexo="+sexo+"&especialidad="+especialidad+"&categoria="+categoria+"&inst="+inst+"&dir="+dir+"&del="+del+"&estado="+estado;
				var ventanaExportarPersonas = window.open("exportarExcelPersonas.php?"+variables, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				ventanaExportarPersonas.focus();
			}
			
			function muestraPlan(idPlan){
				$("#divCapa3").show('slow');
				$("#divPlanes").show();
				$("#divRespuesta").load("ajax/cargarPlan.php", {idPlan:idPlan});
			}
			
			function muestraVisita(idVisita, idPlan){
				/*var ancho = 630;
				var alto = 550;
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				var ventanaVisita = window.open("visitas.php?idVisita="+idVisita+"&idPlan="+idPlan, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				ventanaVisita.focus();*/
				//alert(idVisita);
				$("#divCapa3").show('slow');
				$("#divVisitas").show();
				$("#divRespuesta").load("ajax/cargarVisita.php", {idVisita:idVisita,idPlan:idPlan});
			}
			
			function eliminarArchivo(idArchivo){
				if(confirm('¿Esta seguro de querer eliminar este archivo?')){
					$('#divSubirDocumentosDatosPersonales').load("ajax/eliminaArchivo.php",{idArchivo:idArchivo, idPersona:$('#hdnIdPersona').val(), idUsuario:$('#hdnIdUser').val()});
				}
			}
			
			/*function cargaMuestra(){
				alert($('#lstYear').val());
				$('#divMuestraMedica').load("ajax/cargarMuestra.php",{year:$('#lstYear').val(),idPersona:$('#hdnIdPersona').val()});
			}*/
			
			function editarPersona(idPersona){
				var idUsusario = $('#hdnIdUser').val();
				$('#divPersona').show();
				$('#divCapa3').show('slow');
				$('#divRespuesta').load("ajax/cargarPersonaNueva.php",{idUsuario:idUsusario,idPersona:idPersona});
			}
			
			function eliminarPersona(idPersona){
				$('#hdnIdPersonaBaja').val(idPersona);
				$('#divMotivoBaja').show();
				$('#over').show(500);
				$('#fade').show(500);
			}
			
			function limpiaPersonaNuevo(){	
				$('#hdnIdInstPersonaNuevo').val('');
				$('#sltTipoPersonaNuevo').val('');
				$('#txtNombrePersonaNuevo').val('');
				$('#txtPaternoPersonaNuevo').val('');
				$('#txtMaternoPersonaNuevo').val('');
				$('#sltSexoPersonaNuevo').val('');
				$('#sltEspecialidadPersonaNuevo').val('');
				$('#sltSubEspecialidadPersonaNuevo').val('');
				$('#sltPacientesSemanaPersonaNuevo').val('');
				$('#sltHonorariosPersonaNuevo').val('');
				$('#txtFechaNacimientoPersonaNuevo').val('');
				$('#sltCategoriaPersonaNuevo').val('');
				$('#txtCedulaPersonaNuevo').val('');
				$('#sltFrecuenciaPersonaNuevo').val('');
				$('#txtDificultadVisitaPersonaNuevo').val('');
				$('#sltLiderOpinionPersonaNuevo').val('');
				$('#sltBotiquinPersonaNuevo').val('');
				$('#txtIgualaPersonaNuevo').val('');
				$('#txtTelefonoInstPersonaNuevo').val('');
				$('#txtTelefono1InstPersonaNuevo').val('');
				$('#txtEmailInstPersonaNuevo').val('');
				$('#txtCorto').val('');
				$('#txtLargo').val('');
				$('#txtGenerales').val('');
				$('#txtNumIntInstPersonaNuevo').val('');
				$('#txtCampoAbierto1PersonaNuevo').val('');
				$('#txtCampoAbierto2PersonaNuevo').val('');
				$('#txtCampoAbierto3PersonaNuevo').val('');
				$('#txtTelPersonalPersonaNuevo').val('');
				$('#txtCorreoPersonalPersonaNuevo').val('');
				$('#txtTorrePersonaNuevo').val('');
				$('#txtPisoPersonaNuevo').val('');
				$('#txtConsultorioPersonaNuevo').val('');
				$('#txtDepartamentoPersonaNuevo').val('');
				
				/*pasatiempo*/
				var cuanto = $('#hdnPasatiempo').val();
				for(i=1;i<cuanto;i++){
					$('#chkPasatiempoPersonaNuevo'+i).attr('checked');
				}
					
				$('#chkLunesAm').attr('checked', false);
				$('#chkLunesPm').attr('checked', false);
				$('#chkLunesTodo').attr('checked', false);
				$('#chkLunesPrevia').attr('checked', false);
				$('#chkLunesFijo').attr('checked', false);

				$('#chkMartesAm').attr('checked', false);
				$('#chkMartesPm').attr('checked', false);
				$('#chkMartesTodo').attr('checked', false);
				$('#chkMartesPrevia').attr('checked', false);
				$('#chkMartesFijo').attr('checked', false);

				$('#chkMiercolesAm').attr('checked', false);
				$('#chkMiercolesPm').attr('checked', false);
				$('#chkMiercolesTodo').attr('checked', false);
				$('#chkMiercolesPrevia').attr('checked', false);
				$('#chkMiercolesFijo').attr('checked', false);

				$('#chkJuevesAm').attr('checked', false);
				$('#chkJuevesPm').attr('checked', false);
				$('#chkJuevesTodo').attr('checked', false);
				$('#chkJuevesPrevia').attr('checked', false);
				$('#chkJuevesFijo').attr('checked', false);

				$('#chkViernesAm').attr('checked', false);
				$('#chkViernesPm').attr('checked', false);
				$('#chkViernesTodo').attr('checked', false);
				$('#chkViernesPrevia').attr('checked', false);
				$('#chkViernesFijo').attr('checked', false);

				$('#chkSabadoAm').attr('checked', false);
				$('#chkSabadoPm').attr('checked', false);
				$('#chkSabadoTodo').attr('checked', false);
				$('#chkSabadoPrevia').attr('checked', false);
				$('#chkSabadoFijo').attr('checked', false);

				$('#chkDomingoAm').attr('checked', false);
				$('#chkDomingoPm').attr('checked', false);
				$('#chkDomingoTodo').attr('checked', false);
				$('#chkDomingoPrevia').attr('checked', false);
				$('#chkDomingoFijo').attr('checked', false);
				
				$('#txtLunesComentarios').val('');
				$('#txtMartesComentarios').val('');
				$('#txtMiercolesComentarios').val('');
				$('#txtJuevesComentarios').val('');
				$('#txtViernesComentarios').val('');
				$('#txtSabadoComentarios').val('');
				$('#txtDomingoComentarios').val('');
			}
			
			/*persona */
			
			function instSeleccionada(id){
				$("#divBusqueda").hide();
				$("#tblDatosInst").show();
				$("#divRespuesta").load("ajax/cargarInstSeleccionada.php",{idInst:id});
			}
			
			function pad (str, max) {
				str = str.toString();
				return str.length < max ? pad("0" + str, max) : str;
			}
			
			function actualizaFechaCumple(){
				var dia = $("#sltDia").val();
				var mes = $("#sltMes").val();
				var anio = $("#sltAnio").val();
				$("#fecha").val(anio+'-'+pad(mes, 2)+'-'+pad(dia, 2));
			}
			
			/*fin de persona*/
			
			/* fin personas */

			function criteriosReporte(variables){
				var criterios = variables.split(',');
				$('#fecIni').hide();
				$('#fecFin').hide();
				$('#repre').hide();
				$('#producto').hide();
				$('#status').hide();
				$('#periodo').hide();
				for(var i=0; i<criterios.length; i++){
					$('#'+criterios[i]).show();
				}
			}
			
			/*instituciones*/
			
			function nuevaPaginaInst(pagina,hoy,ids,visitados){
				var tipo = $('#sltTipoInstFiltro').val();
				var nombre = $('#txtNombreInstFiltro').val();
				var calle = $('#txtCalleInstFiltro').val();
				var colonia = $('#txtColoniaInstFiltro').val();
				var ciudad = $('#txtCiudadInstFiltro').val();
				var estado = $('#txtEstadoFiltro').val();
				var cp = $('#txtCPInstFiltro').val();
				$("#tbTodas").load("ajax/cargarTablaInst.php",{pagina:pagina,hoy:hoy,ids:ids,tipo:tipo,nombre:nombre,calle:calle,colonia:colonia,ciudad:ciudad,estado:estado,cp:cp,visitados:visitados});
			}
			
			function muestraPlanInst(idPlan){
				$("#divPlanesInst").show();
				$("#divCapa3").show('slow');
				$("#divRespuesta").load("ajax/cargarPlanInst.php", {idPlan:idPlan});
			}
			
			function muestraVisitaInst(idVisita, idPlan){
				var ancho = 630;
				var alto = 550;
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				var ventanaVisita = window.open("visitasInst.php?idVisita="+idVisita+"&idPlan="+idPlan, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				ventanaVisita.focus();
			}
			
			function editarInst(idInst){
				var idUsusario = $('#hdnIdUser').val();
				abrirVentanaPersona('institucion.php?idUsuario='+idUsusario+"&idInst="+idInst,500,900);
			}
			
			function eliminarInst(idInst){
				if(confirm('Desea eliminar la Institución!!!')){
					var idUsuario = $('#hdnIdUser').val();
					$('#divRespuesta').load('ajax/eliminarInst.php',{idInst:idInst,idUsuario:idUsuario});
				}
			}
			
			function exportarExcelInst(hoy,ids){
				var ancho = 350;
				var alto = 450;
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				visitados = $('#hdnFiltrosExportarInst').val();
				tipoInst = $('#sltTipoInstFiltro').val();
				nombreInst = $('#txtNombreInstFiltro').val();
				calleInst = $('#txtCalleInstFiltro').val();
				coloniaInst = $('#txtColoniaInstFiltro').val();
				ciudadInst = $('#txtCiudadInstFiltro').val();
				estadoInst = $('#txtEstadoFiltro').val();
				cpInst = $('#txtCPInstFiltro').val();
				
				variables = "hoy="+hoy+"&ids="+ids+"&visitados="+visitados+"&tipoInst="+tipoInst+"&nombreInst="+nombreInst+"&calleInst="+calleInst+"&coloniaInst="+coloniaInst+"&ciudadInst="+ciudadInst+"&estadoInst="+estadoInst+"&cpInst="+cpInst;
				var ventanaExportarInst = window.open("exportarExcelInst.php?"+variables, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				ventanaExportarInst.focus();
			}
			
			/*function muestraVisitaInst(idVisita, idPlan){
				//alert(idVisita);
				var ancho = 800;
				var alto = 600;
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				var lat = '0.0';
				var lon = '0.0';
				var idInst = $('#hdnIdInst').val();
				var idUsuario = $('#hdnIdUsuario').val();
				var ventana = window.open("visitasInst.php?idPlan="+idPlan+"&idInst="+idInst+"&idUsuario="+idUsuario+"&lat="+lat+"&lon="+lon+"&idVisita="+idVisita, "vtnVisitas", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				ventana.focus();
			}*/
			
			/*function validaCantidadVisitasInst(texto, existencia, maximo){
				cantidad = $("#text"+texto).val();
				if(cantidad > existencia){
					alert("Solo tienes "+existencia+" piezas de ese producto!!!");
					return true;
				}
				if(cantidad > maximo){
					alert("Sólo puede entregar "+maximo+" piezas de ese producto!!!");
					return true;
				}
			}
			
			function existenciaVisitasInst(existencia){
				alert('Existencia: '+existencia);
			}
			
			function llenaSiguienteVisitasInst(combo){
				var productosSeleccionados = '';
				for(var i=1;i<combo+1;i++){
					productosSeleccionados += $("#lstProducto"+i).val()+",";
				}
				$("#resp").load("ajax/llenaCombo.php",{combo:combo,productos:productosSeleccionados});
			}
			
			var expandedVisitasInst = false;

			function showCheckboxesVisitasInst() {
				var checkboxes = document.getElementById("checkboxes");
				if (!expanded) {
					checkboxes.style.display = "block";
					expanded = true;
				} else {
					checkboxes.style.display = "none";
					expanded = false;
				}
			}
			*/
			/* termina inst */
			
			/* calendario*/
			
			function sumaHoras(){
				suma = 0;

				for(i=1;i<=$('#hdnTotalChkOA').val();i++){
					if($('#chkOA'+i).prop('checked') && $('#txtOA'+i).val() != ''){
						suma += parseFloat($('#txtOA'+i).val());
					}
				}
				
				if(suma > 8){
					alert('La suma no puede exceder de 8 hrs.');
				}
				//alert(suma);
				$('#txtTotalActividades').val(suma);
			}
			
			var expanded = false;

			function showCheckboxes() {
				var checkboxes = document.getElementById("checkboxes");
				if (!expanded) {
					checkboxes.style.display = "block";
					expanded = true;
				} else {
					checkboxes.style.display = "none";
					expanded = false;
				}
			}
			
			function actualizaCalendario(){
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					planVisita = 'plan';
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					planVisita = 'visita';
				}
				var ids = $('#hdnIds').val();
				var fecha = $('#hdnFechaCalendario').val();
				var idRepre = $('#sltRepreCalendario').val();
				$('#divCalendarioCambia').load("ajaxCalendario.php", {fecha:fecha, idUsuario:$idRepre,planVisita:planVisita,ids:ids});
			}
			
			function actualizaCalendarioBoton(planVisita){
				var ids = $('#hdnIds').val();
				var idRepre = $('#sltRepreCalendario').val();
				$('#divCalendarioCambia').load("ajaxCalendario.php", {fecha:$('#hdnFechaCalendario').val(), idUsuario:idRepre,planVisita:planVisita,ids:ids});
			}
			
			function actualizaCalendarioSelect(){
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					planVisita = 'plan';
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					planVisita = 'visita';
				}
				var ids = $('#hdnIds').val();
				var idRepre = $('#sltRepreCalendario').val();
				$('#divCalendarioCambia').load("ajaxCalendario.php", {fecha:$('#hdnFechaCalendario').val(), idUsuario:idRepre,planVisita:planVisita,ids:ids});
			}
			
			function muestraRuteo(){
				$('#divBuscarPersonas').hide();
					$('#divReportarOtrasActividades').hide();
					$('#divBuscarInst').hide();
					$('#divRuteo').show();
					$('#over').show(500);
					$('#fade').show(500);
					load_map('map_canvas3');
					fecha = $("#hdnFechaCalendario").val();
					idUsuario = $('#hdnIdUser').val();
					if($('#btnPlanCalendario').hasClass('seleccionado')){
						planVisita = 'plan';
						$('#btnPlanRuteo').addClass("seleccionado");
						$("#btnVisitaRuteo").removeClass("seleccionado");
					}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
						planVisita = 'visita';
						$('#btnPlanRuteo').removeClass("seleccionado");
						$("#btnVisitaRuteo").addClass("seleccionado");
					}
					$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
			}
			
			/* termina calendario*/
			
			/* visitas */
			
			function validaCantidad(texto, existencia, maximo){
				cantidad = $("#text"+texto).val();
				if(cantidad > existencia){
					alert("Solo tienes "+existencia+" piezas de ese producto!!!");
					return true;
				}
				if(cantidad > maximo){
					alert("Sólo puede entregar "+maximo+" piezas de ese producto!!!");
					return true;
				}
			}
			
			function existencia(existencia){
				alert('Existencia: '+existencia);
			}
			
			function llenaSiguiente(combo){
				var productosSeleccionados = '';
				for(var i=1;i<combo+1;i++){
					productosSeleccionados += $("#lstProducto"+i).val()+",";
				}
				$("#divRespuesta").load("ajax/llenaCombo.php",{combo:combo,productos:productosSeleccionados});
			}
			
			var expanded = false;

			function showCheckboxes() {
				var checkboxes = document.getElementById("checkboxesVisitas");
				if (!expanded) {
					checkboxes.style.display = "block";
					expanded = true;
				} else {
					checkboxes.style.display = "none";
					expanded = false;
				}
			}
			
			function agregaDesVisAcompa(texto, check){
				var textoChk = '';
				if($('#'+check).prop('checked')){
					if($("#sltMultiSelect").text() == 'Selecciona'){
						textoChk = texto + ";";
					}else{
						textoChk = $("#sltMultiSelect").text() + texto + ";";
					}
				}else{
					textoChk = $("#sltMultiSelect").text().replace(texto + ";", '');
				}
				$("#sltMultiSelect").text(textoChk);
			}
			
			function limpiarChecksVisitaAcompa(pantalla){
				if(pantalla == 'personas'){
					var checks = $('#hdnTotalChecksVisitas').val();
					checks = (checks*1)+1;
					for(i=1;i<checks;i++){
						$('#acompa'+i).attr('checked', false);
					}
					$('#checkboxesVisitas').hide();
					$('#sltMultiSelect').val('Selecciona');
				}
			}
			/* fin visitas */
			
			/* ruteo */
			var markersRuteo = new Array();
			var lineasRuteo = new Array();
			
			function deleteMarkersRuteo(markers, lineas){
				for(i=0;i<markers.length;i++){
					markers[i].setMap(null);
				}
				for(i=0;i<lineas.length;i++){
					lineas[i].setMap(null);
				}
				markersRuteo.length = 0;
				lineasRuteo.length = 0;
			}
			
			function cambiaMesRuteo(){
				month = $('#sltMesCal').val();
				year = $('#sltYearCal').val();
				day = $('#hdnFechaRuteo').val().split("-")[2];
				$('#calendarioRuteo').load("ajax/cambiaCalendarioRuteo.php",{month:month,year:year,day:day});
			}
			
			function traePlanVisitasRuteo(day,month,year){
				fecha = year+'-'+month+'-'+day;
				$('#hdnFechaRuteo').val(fecha);
				idUsuario = $('#hdnIdUser').val();
				if($('#btnPlanRuteo').hasClass('seleccionado')){
					planVisita = 'plan';
				}else if($('#btnVisitaRuteo').hasClass('seleccionado')){
					planVisita = 'visita';
				}
				$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
			}
			/* termina ruteo */
			
			/*radar*/
			
			var markersRadar = new Array();
			
			function initialize(lat, lon) {
				//alert('i: ' + lat + ' ' + lon);
				var latlng = new google.maps.LatLng(lat, lon);
				var mapOptions = {
					zoom: 16,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
				}
				var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
				//setMarkers(map, marcadores);
			}
			
			function deleteMarkersRadar(markers){
				for(i=0;i<markers.length;i++){
					markers[i].setMap(null);
				}
				markersRuteo.length = 0;
			}
 
			/*termina radar*/
			
			/*inventario*/
			
			function traeEntradasSalidas(idProducto){
				idUsuario = $('#hdnIdUser').val(); 
				$('#divRespuesta').load("ajax/cargarEntradasSalidas.php",{idProducto:idProducto,idUsuario:idUsuario});
			}
			
			function ajuste(idProducto, fecha, producto){
				$('#divConfirmacionInventario').show();
				$('#over').show(500);
				$('#fade').show(500);
				$('#lblProductoAjuste').text(fecha + ' ' + producto);
				$('#hdnIdProductoAjuste').val(idProducto);
			}
			
			/* fin de inventario*/
			
			/*aprobaciones*/
			
			function muestraAprobacion(id, tipo){
				//alert(id);
				if(tipo == 'p'){
					$('#hdnIdPersApproval').val(id);
					$('#hdnIdInstApproval').val('');
				}else if(tipo == 'i'){
					$('#hdnIdInstApproval').val(id);
					$('#hdnIdPersApproval').val('');
				}
				
				$('#divAprobacion').show();
				$('#over').show(500);
				$('#fade').show(500);
				$('#divRespuesta').load('ajax/cargarAprobacion.php',{id:id,tipo:tipo});
			}
			
			/*fin aprobaciones*/
			
			/* timer */
				
			timer = setTimeout('temporizador()', 1000); 
			function temporizador() { 
				var hora = $('#lblTimer').text();
				var arrHora = hora.split(":");
				var horas = arrHora[0];
				var minutos = arrHora[1];
				var segundos = arrHora[2];
				if(segundos == 0){
					segundos = 59;
					if(minutos == 0){
						minutos = 59;
						horas = horas - 1;
					}else{
						minutos = minutos - 1;
					}
				}else{
					segundos = segundos - 1;
				}
				if(minutos < 10){
					minutos = '0' + minutos;
					minutos = minutos.slice(-2);
				}
				if(segundos < 10){
					segundos = '0' + segundos;
				}
				$('#lblTimer').text(horas+':'+minutos+':'+segundos);
				if(horas == 0 && minutos == 0 && segundos == 0){
					alert('Tu sesión ha expirado.');
					$('#imgLogout').click();
				}else{
					timer = setTimeout("temporizador()", 1000);
				}
				 
			}

			/* termna timer */
			
			/* firma */
			estoyDibujando = false;
			function comenzar(){
				lienzo = document.getElementById('canvasFirmaVisitas');
				ctx = lienzo.getContext('2d');
				ctx.translate(-455, -440);
				//ctx.clear();
				//Dejamos todo preparado para escuchar los eventos
				document.addEventListener('mousedown',pulsaRaton,false);
				document.addEventListener('mousemove',mueveRaton,false);
				document.addEventListener('mouseup',levantaRaton,false);
			}
			
			function limpiar(){
				lienzo = document.getElementById('canvasFirmaVisitas');
				ctx = lienzo.getContext('2d');
				ctx.clearRect(455, 440, lienzo.width, lienzo.height);
				document.getElementById("hdnFirma").value = ''; 
				//alert('limpiar');
			}

			function pulsaRaton(capturo){
				estoyDibujando = true;
				//Indico que vamos a dibujar
				ctx.beginPath();

				//Averiguo las coordenadas X e Y por dónde va pasando el ratón
				ctx.moveTo(capturo.clientX,capturo.clientY);
				//alert(capturo.clientX + " " + capturo.clientY);
			}

			function mueveRaton(capturo){
				if(estoyDibujando){
					//indicamos el color de la línea
					ctx.strokeStyle='#000';
					//Por dónde vamos dibujando
					ctx.lineTo(capturo.clientX,capturo.clientY);
					ctx.stroke();
				}
			}

			function levantaRaton(capturo){
				//Indico que termino el dibujo
				ctx.closePath();
				estoyDibujando = false;
			}
			
			function guardarFirma(){
				var canvasFirmaVisitas = document.getElementById('canvasFirmaVisitas');
				var dataURL = canvasFirmaVisitas.toDataURL();
				document.getElementById("hdnFirma").value = dataURL; 
			}
			
			/* fin firma */
		</script>
	</head>
	<body onload="comenzar();">
		<input id="txtCanvas" type="hidden" value="" />
		
		<center>
			<div id="divMenu">
				<table id="tblMenu" width="1300px" border="0">
					<tr align="center">
						<td>
							<img src="imagenes/logo.png" width="200px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/inicio.png" title="Inicio" id="imgHome" width="60px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/personas.png" title="Personas" id="imgPersonas" width="60px"/>
						</td>						
						<td>
							<img class="zoomIt" src="iconos/instituciones.png" title="Instituciones" id="imgInstotuciones" width="60px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/calendario.png" title="Calendario" id="imgCalendario" width="60px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/localizador.png" title="Geolocalización" id="imgGeo" width="60px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/documentos_entregados.png" width="60px" title="Documentos Entregados" id="imgDocumentosEntregados"/>							
						</td>
						<td>
							<img class="zoomIt" src="iconos/stock.png" title="Inventario" id="imgInventario" width="60px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/reportes.png" width="60px" id="imgReportes" title="Reportes"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/configuracion.png" id="imgConfig" width="60px" title="Configuración"/>
						</td>
<?php
						$queryTipoUsuario = "select * from users where rec_stat = 0 and user_snr = '".$_GET['idUser']."'";
						$rsTipoUsuario = sqlsrv_query($conn, $queryTipoUsuario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
						if(sqlsrv_num_rows($rsTipoUsuario) > 0){
							while($row = sqlsrv_fetch_array($rsTipoUsuario)){
								$tipoUsuario = $row['USER_TYPE'];
								$rutaEtiqueta = $row['LNAME']." ".$row['FNAME'];
							}
							if($tipoUsuario != 4){
?>
								<td>
									<img class="zoomIt" src="iconos/aprobaciones.png" id="imgAprobaciones" width="60px" title="Aprobaciones"/>
								</td>
<?php
							}
						}
?>
						<td>
							<img class="zoomIt" src="iconos/logout.png" id="imgLogout" width="60px" title="Cerrar Sesión"/>
						</td>
						<td valign="bottom">
							<div id="divTimer" align="right">
								<b><label id="lblRuta"><?= $rutaEtiqueta ?></label></b><br>
								Ciclo: <?= substr($cicloActivo, 0, 5).substr($cicloActivo, 8, 2) ?><br>
								<label id="lblTimer">2:00:00</label>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="13">
							<hr>
						</td>
					</tr>
				</table>
			</div>			
			
			<div id="divRespuesta"></div>
			
			<div id="divInicio" style="display:block;">
				<?php include "inicio.php"; ?>
			</div>
			
			<div id="divInstituciones" style="display:none;">
				<?php include "instituciones.php" ?>
			</div>
			
			<div id="divPersonas" style="display:none;" >
				<?php include "personas.php"; ?>
			</div>

			<div id="divCalendario" style="display:none">
				<?php include "calendario.php"; ?>
			</div>
			
			<div id="divCiclos" style="display:none;">
				<?php include "ciclos.php"; ?>
			</div>
			
			<div id="divMensajes" style="display:none;">
				<?php include "mensajes.php"; ?>
			</div>
			
			<div id="divInventario" style="display:none;">
				<?php include "inventario.php"; ?>
			</div>
			
			<div id="divReportes" style="display:none;">
				<?php include "reportes.php"; ?>
			</div>
			
			<div id="divGeo" style="display:none;">
				<table width="100%">
					<tr>
						<td>
							<table border="0">
								<tr>
									<td>
										<img src="iconos/localizador.png" title="Inicio" class="imgTitulo"/>
									</td>
									<td class="nombreModulos">
										Localizador
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<td colspan="2">
					<hr>
				</td>
				<div id="mapa" style="width:1290px;height:460px;"></div>
				<table width="70%">
					<tr>
						<td class="negrita">
							Kilómetros<br>
							<select id="sltKilometros">
								<option value="0.5">0.5</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="20">20</option>
							</select>
						</td>
						<td class="negrita">
							Visitas<br>
							<select id="sltVisitas">
								<option value=""></option>
								<option value="0">No visitados</option>
								<option value="1">Visitados</option>
							</select>
						</td>
						<td class="negrita">
							Especialidad<br>
							<select id="sltEspecialidadRadar">
								<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
								$rsEsp = llenaCombo($conn, 19, 1);
								while($esp = sqlsrv_fetch_array($rsEsp)){
									/*if($especialidad == $esp['id']){
										echo '<option value="'.$esp['id'].'" selected>'.$esp['nombre'].'</option>';
									}else{*/
										echo '<option value="'.$esp['id'].'">'.$esp['nombre'].'</option>';
									//}
								}
?>
							</select>
						</td>
						<td class="negrita">
							Tipo<br>
							<select id="sltTipo">
								<option value=""></option>
								<option value="p">Personas</option>
								<option value="i">Instituciones</option>
							</select>
						</td>
						<td class="negrita">
							Tipo Institución<br>
							<select id="sltTipoInst">
<?php
							$rsTipoInst = sqlsrv_query($conn, "select * from INST_TYPE");
							while($tipoInst = sqlsrv_fetch_array($rsTipoInst)){
								echo '<option value="'.$tipoInst['INST_TYPE'].'">'.$tipoInst['NAME'].'</option>';
							}
?>
							</select>
							<?php print_r($tipoInst); ?>
						</td>
						<td>
							<button id="btnLocalizarFiltro" type="button">
								Filtrar
							</button>
						</td>
					</tr>
				</table>
			</div>
			
			<div id="divConfig" style="display:none;">
				<?php include "cambioPassword.php" ?>
			</div>
			
			<div id="divDocumentos" style="display:none;">
				<?php include "documentosEntregados.php" ?>
			</div>
			
			<div id="divAprobaciones" style="display:none;">
				<?php include "aprobaciones.php" ?>
			</div>
		</center>
			
			<div id="divCapa3">
			
				<div id="divPlanes">
					<?php include "planes.php"; ?>
				</div>
				
				<div id="divVisitas" style="display:none;">
					<?php include "visitas.php"; ?>
				</div>
				
				<div id="divPersona" style="display:none;">
					<?php include "persona.php"; ?>
				</div>
				
				<div id="divPlanesInst" style="display:none;">
					<?php include "planesInst.php"; ?>
				</div>
				
				<div id="divVisitasInst" style="display:none;">
					<?php include "visitasInst.php"; ?>
				</div>
				
			</div>

			<!-- lightbox -->
			<div id="over" class="overbox" style="height:600px;" >
			
				<div align="right">
					<img src="iconos/close.png" id="cerrarInformacion" width="30px" title="Cerrar" />
					<div id="divDatosPersonales" style="display:none;">
						<?php include "datosPersonales.php"; ?>
					</div>
					<div id="divDatosInstituciones"  style="display:none;">
						<?php include "datosInstituciones.php"; ?>
					</div>
					<div id="divPlanearOtrasActividades" style="display:none;">
						<?php include "planearOtrasActividades.php"; ?>
					</div>
					<div id="divMensaje" style="display:none;">
						<?php include "mensaje.php"; ?>
					</div>
					<div id="divSupervision" style="display:none;">
						<?php include "supervision.php"; ?>
					</div>
					<div id="divReportarOtrasActividades" style="display:none;">
						<?php include "OtrasActividades.php"; ?>
					</div>
					<div id="divBuscarPersonas" style="display:none;">
						<?php include "buscarPersona.php"; ?>
					</div>
					<div id="divBuscarInst" style="display:none;">
						<?php include "buscarInst.php"; ?>
					</div>
					<div id="divRuteo" style="display:none;">
						<?php include "ruteo.php"; ?>
					</div>
		
					<div id="divConfirmacionInventario" style="display:none">
						<input type="hidden" id="hdnIdProductoAjuste" value="" />
						<center>
							<br><br><br>
							<table bgcolor="#FFFFFF" id="tblAjustInv" align="center">
								<tr>
									<td colspan="3" align="center">
										<h2>Manejo de Existencias</h2>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<hr />
									</td>
								</tr>
								<tr>
									<td colspan="3" align="center">
										<h3><label id="lblProductoAjuste"></label></h3>
										<br><br>
									</td>
								</tr>
								<tr>
									<td align="center"><button id="btnAjustarInv">Ajustar</button></td>
									<td align="center"><button id="btnAceptarAjusteInv">Aceptar</button></td>
									<td align="center"><button id="btnCancelarAjusteInv">Rechazar</button></td>
								</tr>
							</table>
						</center>
					</div>
		
					<div id="divAprobacionesPers" style="display:none;">
						<?php include "aprobacionesPers.php" ?>
					</div>
					<div id="divAprobacionesInst" style="display:none;">
						<?php include "aprobacionesInst.php" ?>
					</div>
		
					<div id="divAprobacion" style="display:none;text-align;center;">
						<div style="width:800px;height:400px;text-align:left;">
							<div id="divContAprobacion" >
								</br>
								&nbsp;&nbsp;&nbsp;
								<button id="btnAceptarAprobacion">Aceptar</button>
								<button id="btnRechazarAprobacion">Rechazar</button><br>
								<input type="hidden" id="hdnIdPersApproval" />
								<input type="hidden" id="hdnIdInstApproval" />
								<center>
									<div id="divMotivoRechazo" style="width:300px;height:80px;display:none;" >
										<br>
										<b>Motivo:</b> 
										<select id="sltMotivoRechazo">
											<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
	<?php
										$rsMotivoRechazo = llenaCombo($conn, 297, 516);
										while($regMotivoRechazo = sqlsrv_fetch_array($rsMotivoRechazo)){
											echo '<option value="'.$regMotivoRechazo['id'].'">'.$regMotivoRechazo['nombre'].'</option>';
										}
	?>
										</select><br><br>
										<input type="button" value="Aceptar" id="btnAceptarRechazo">
										<input type="button" value="Cancelar" id="btnCancelarRechazo">
									</div>
								</center>
								<hr>
								<div style="height:400px;overflow:auto;text-align:left;">
									<table border="0" id="tblAprobacion" width="100%" >
									</table>
								</div>
							</div>
						</div>
					</div>
	
					<div id="divMotivoBaja" style="display:none;text-align:center;">
						<div style="width:1200px;height:400px;text-align:center;">
							<center>
								<div id="divContMotivoBaja" >
									<center>
									<input type="hidden" id="hdnIdPersonaBaja" value="" />
									<table width="80%" cellspacing="5">
										<tr>
											<td colspan="2" align="center"><h2>Eliminar Médico</h2><hr></td>
										</tr>
										<tr>
											<td class="negrita">Motivo de la baja:</td>
											<td> 
												<select id="sltMotivoBaja">
													<option value="">Seleccione</option>
<?php
													$rsMotivoBaja = llenaCombo($conn, 14, 264);
													while($regMotivoBaja = sqlsrv_fetch_array($rsMotivoBaja)){
														echo '<option value="'.$regMotivoBaja['id'].'">'.utf8_encode($regMotivoBaja['nombre']).'</option>';
													}
?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="negrita">Comentarios adicionales:</td>
											<td><textarea id="txtComentariosBaja" rows="4" cols="30"></textarea></td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<button id="btnAceptarBaja">Aceptar</button>
												<!--<button id="btnCancelarBaja">Cancelar</button>-->
											</td>
										</tr>
									</table></center>
								</div>
							</center>
						</div>
					</div>
				</div>
			</div>
			<div id="fade" class="fadebox">&nbsp;</div>
			<!-- fin de lightbox -->
		<!--</center>-->
		<script src="external/jquery/jquery.js"></script>
		<script src="jquery-ui.js"></script>
		<script>
			$(function(){
				$("#formuploadajax").on("submit", function(e){
					e.preventDefault();
					var f = $(this);
					var formData = new FormData(document.getElementById("formuploadajax"));
					formData.append("dato", "valor");
					formData.append("idPersona", $("#hdnIdPersona").val());
					formData.append("idUsuario", $('#hdnIdUser').val());
					//formData.append(f.attr("name"), $(this)[0].files[0]);
					$.ajax({
						url: "recibe.php",
						type: "post",
						dataType: "html",
						data: formData,
						cache: false,
						contentType: false,
						processData: false
					})
					.done(function(res){
						//$("#divSubirDocumentosDatosPersonales").html(res);
						$("#divRespuesta").html(res);
					});
				});
			});
	
			$( "#divGridInstituciones" ).tabs();
			$( "#tabFiltros" ).tabs();
			$( "#tabFiltros2" ).tabs();
			$( "#tabFiltrosInstituciones" ).tabs();
			$( "#tabsInstituciones" ).tabs();
			$( "#tabsDepartamentos").tabs();
			$( "#tabsInventario" ).tabs();
			$( "#tabSupervision" ).tabs();
			$( "#tabOtrasActividades" ).tabs();
			$( "#divAprobacionesGerente").tabs();
			$( "#divGridPersonas").tabs();
			$( "#tabsVisitas" ).tabs();
			$( "#tabsPersona" ).tabs();
			$( "#tabsVisitasInst" ).tabs();
			
			$(function () {
				$("#txtFechaInicioInstituciones").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			$(function () {
				$("#txtFechaTerminoInstituciones").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			$(function () {
				$("#txtFechaOtrasActividades").datepicker({
					changeMonth: true,
					changeYear: true
				});
			});
			
			$(function () {
				$("#txtFechaOtrasActividadesFinal").datepicker({
					changeMonth: true,
					changeYear: true
				});
			});
			
			$(function () {
				$("#txtFechaInicioReportes").datepicker({
					changeMonth: true,
					changeYear: true
				});
			});
			
			$(function () {
				$("#txtFechaFinReportes").datepicker({
					changeMonth: true,
					changeYear: true
				});
			});
			
			$(function () {
				$("#txtFechaSupervision").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			$(function () {
				$("#txtFechaSiguienteSupervision").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			$(function () {
				$("#txtFechaConclusiones").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			/*$(function () {
				$("#txtFechaReportarOtrasActividades").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});*/
			
			$(function () {
				$("#txtFechaReportarOtrasActividadesFin").datepicker({
					changeMonth: true,
					changeYear: false
				});
			});
			
			$(function(){
				$("#txtFechaPlan").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			$(function(){
				$("#txtFechaVisita").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
			$(function(){
				$("#txtFechaPlanInst").datepicker({
					changeMonth: false,
					changeYear: false
				});
			});
			
		</script>
	</body>
</html>