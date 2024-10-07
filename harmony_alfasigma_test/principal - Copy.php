<!DOCTYPE html>
<html>
<?php
	session_start();
	if(! isset($_SESSION["usuario"])){
		header("Location: index.php"); 
	}
	function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
           
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
       
        return $_SERVER['REMOTE_ADDR'];
	}

include "conexion.php";
$conex = $conn;
include('calendario/calendario.php');
$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
$arrCiclo = sqlsrv_fetch_array(sqlsrv_query($conn, "select name from cycles where '".date("Y-m-d")."' between start_date and finish_date "));
$cicloActivo = $arrCiclo['name'];
$idUsuario = $_GET['idUser'];
$ip = getRealIP();
$idUL = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as id from USER_LOGGING where USER_LOGGING_SNR = '00000000-0000-0000-0000-000000000000'"))['id'];

$qGrabaAcceso = "insert into USER_LOGGING (USER_LOGGING_SNR, user_snr, START_ACTION_TIME, rec_stat, version, IP_USED)
	values ('".$idUL."', '".$idUsuario."', getdate(), 0, 1, '".$ip."')";
if(! sqlsrv_query($conn, $qGrabaAcceso)){
	echo "error acceso: ".$qGrabaAcceso;
}
?>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Smart Scale</title>

	<!--<link href="jquery-ui.css" rel="stylesheet">-->

	<!-- Favicon-->
	<link rel="icon" type="image/x-icon" href="/favicon.ico">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	<link href="plugins/font-awesome/css/all.css" rel="stylesheet">
	
	<!-- Awensome Icons-->

	<script src="https://kit.fontawesome.com/07388dcdd4.js" crossorigin="anonymous"></script>	

	<!-- Bootstrap Core Css -->
	<link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- Bootstrap Select Css -->
	<link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

	<!-- Bootstrap Material Datetime Picker Css -->
	<link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

	<!-- Bootstrap DatePicker Css -->
	<link href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />

	<!-- Waves Effect Css -->
	<link href="plugins/node-waves/waves.css" rel="stylesheet" />

	<!--WaitMe Css-->
	<link href="plugins/waitme/waitMe.css" rel="stylesheet" />

	<!-- Animation Css -->
	<link href="plugins/animate-css/animate.css" rel="stylesheet" />

	<!-- Sweetalert Css -->
	<link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />

	<!-- Custom Css -->
	<link href="css/style.css" rel="stylesheet">

	<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
	<link href="css/themes/all-themes.css" rel="stylesheet" />
	<link href="css/smart.css" rel="stylesheet">

	<!--<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>-->
	<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="calendario/js/jquery.functions.js"></script>
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyD-tf5PgJGx6iHEtZ-4W0ynr-Fgfzarch0?v=2.0"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<!--Materialize-->
	<script src="plugins/materialize-css/js/materialize2.js"></script>

	<!-- JQuery DataTable Css -->
	<link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

	<!-- Signature-->
	<link href="plugins/signature-pad-master/assets/jquery.signaturepad.css" rel="stylesheet">

	<!--FusionCharts-->
	<script type="text/javascript" src="fusioncharts/js/fusioncharts.js"></script>
	<script type="text/javascript" src="fusioncharts/js/themes/fusioncharts.theme.fusion.js"></script>
	<script type="text/javascript" src="fusioncharts/js/themes/fusioncharts.theme.fint.js"></script>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
  
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>


	<script>

		var tabActivoInst = 0;
		var isMedorInst = 0;
		var terminaPintarBullets = 0;
		var muestraEditaPersona = 0;
		var idPersonaReactivar = '';
		var nombreInstOk = 0;
		var sinResultados = 0;
		var buscarMedico ='';

		$(document).ready(function(){
				
			/* menu principal*/
			
			function aparece(div){
				$('#divInicio').hide();
				$('#divPersonas').hide();
				$('#divInstituciones').hide();
				$('#divCalendario').hide();
				$('#divEvento').hide();
				$('#divInversionesLista').hide();
				$('#divCiclos').hide();
				$('#divMensajes').hide();
				$('#divInventario').hide();
				$('#divListados').hide();
				$('#divReportes').hide();
				$('#divGeo').hide();
				$('#divConfig').hide();
				$('#divDocumentos').hide();
				$('#divAprobaciones').hide();
				$('#divCopiarPlanes').hide();
				$('#divReporteador').hide();
				$('#divListador').hide();
				$('#'+div).show();
				if(div == 'divGeo'){
					lat = $('#txtLatitud').val();
					lon = $('#txtLongitud').val();
					initialize(lat, lon);				
				}
				$('#lblTimer').text('2:00:00');
			}
			
			$('#imgHome').click(function(){
				var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				var hoy = $('#hdnHoy').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				$('#divRespuesta').load("ajax/cargarPlanesInicio.php",{ids:ids,hoy:hoy,tipoUsuario:tipoUsuario});
				$('#grafica3').load("ajax/grafica3.php",{ids:ids,hoy:hoy});
				aparece('divInicio');
				
				$('.list').find('li').removeClass('active');
				$('#imgHome').addClass('active');
			});
			
			$('#imgPersonas').click(function(){
				var pagina = $('#hdnPaginaPersonas').val();
				var ids = $('#hdnIds').val();
				var hoy = $('#hdnHoy').val();
				$('#txtBuscarMedico').val('');
				aparece('divPersonas');

				$('.list').find('li').removeClass('active');
				$('#imgPersonas').addClass('active');

				$('#btnQuitarSeleccion').click();
				$('#btnEjecutarFiltroUsuarios').click();
				$('#btnEjecutarFiltro').click();
			});
			
			$('#imgInstituciones').click(function(){
				aparece('divInstituciones');
				$("#divGridInstituciones").tabs("option", "active", 0);
				tabActivoInst = 0;
				
				$('.list').find('li').removeClass('active');
				$('#imgInstituciones').addClass('active');

				$("#activeTabInst0").show();
				$("#activeTabInst1").hide();
				$("#activeTabInst2").hide();
				$("#inst1").click();

				$('#btnQuitarSeleccion').click();
				$('#btnEjecutarFiltroUsuarios').click();
				$('#btnEjecutarFiltroInst').click();
			});

			$('#imgHospitales').click(function(){
				tabActivoInst = 1;
				//aparece('divHospitales');
				aparece('divInstituciones');
				
				//var index = $('#tabs a[href="#simple-tab-2"]').parent().index();
				$("#divGridInstituciones").tabs("option", "active", 1);
				//$("#divGridInstituciones").tabs("select", 1);
				
				$("#cuentaFarmacia").hide();
				$("#tipoFarmacia").hide();
				$("#subtipoFarmacia").hide();
				$("#tipoHospitales").show();
				$("#subtipoHospital").show();
				$("#formatoHospital").show();
				
				$("#divFlonorm").hide();
				$("#divAteka").hide();
				$("#divZirfos").hide();
				$("#divEsoxx").hide();
				$("#divMayor").hide();
				$("#divVessel").hide();
				
				$('#sltCategoriaInstNueva').prop('disabled', true);
				$('#sltFrecuenciaInstNueva').prop('disabled', true);
				
				$('#perfilFarmacia').css("display","none");
				
				$('.list').find('li').removeClass('active');
				$('#imgHospitales').addClass('active');

				$('#txtBuscarMedico').val('');
				$('#txtBuscarHosp').val('');

				$("#activeTabInst0").hide();
				$("#activeTabInst1").show();
				$("#activeTabInst2").hide();
				$("#hosp1").click();

				$('#btnQuitarSeleccion').click();
				$('#btnEjecutarFiltroUsuarios').click();
				$('#btnEjecutarFiltroInst').click();

				var sizeScreen = $(window).height(); //959
				var sizeNavbar = $('.navbar').outerHeight(true); //90
				var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
				var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
				if(sizeHeaderInicio == 15){
					sizeHeaderInicio = sizeHeaderInst;
				}
				var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - 72 - 120;
				var heightCardInstList = heightInstList + 'px';
				$('.listaInstituciones').css('height', heightCardInstList);
				$('.listaInstituciones2').css('height', heightCardInstList);
			});

			$('#imgFarmacias').click(function(){
				tabActivoInst = 2;
				//aparece('divFarmacias');
				aparece('divInstituciones');
				$("#divGridInstituciones").tabs("option", "active", 2);
				
				$("#tipoHospitales").hide();
				$("#subtipoHospital").hide();
				$("#formatoHospital").hide();
				$("#cuentaFarmacia").show();
				$("#subtipoFarmacia").show();
				$("#tipoFarmacia").show();
				
				$("#divFlonorm").show();
				$("#divAteka").show();
				$("#divZirfos").show();
				$("#divEsoxx").show();
				$("#divMayor").show();
				$("#divVessel").show();
				
				$('#sltCategoriaInstNueva').prop('disabled', 'disabled');
				$('#sltFrecuenciaInstNueva').prop('disabled', 'disabled');
				
				$('#perfilFarmacia').css("display","block");
				
				$('.list').find('li').removeClass('active');
				$('#imgFarmacias').addClass('active');

				$("#activeTabInst0").hide();
				$("#activeTabInst1").hide();
				$("#activeTabInst2").show();
				$("#far1").click();

				$('#txtBuscarFar').val('');

				$('#btnQuitarSeleccion').click();
				$('#btnEjecutarFiltroUsuarios').click();
				$('#btnEjecutarFiltroInst').click();

				var sizeScreen = $(window).height(); //959
				var sizeNavbar = $('.navbar').outerHeight(true); //90
				var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
				var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
				if(sizeHeaderInicio == 15){
					sizeHeaderInicio = sizeHeaderInst;
				}
				var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - 72 - 120;
				var heightCardInstList = heightInstList + 'px';
				$('.listaInstituciones').css('height', heightCardInstList);
				$('.listaInstituciones2').css('height', heightCardInstList);
			});
			
			$('#imgCalendario').click(function(){
				aparece('divCalendario');
				$('#btnPlanCalendario').click();
				
				$('.list').find('li').removeClass('active');
				$('#imgCalendario').addClass('active');

				$('#btnQuitarSeleccion').click();
				$('#btnEjecutarFiltroUsuarios').click();
			});
			
			$('#imgEventos').click(function(){
				var idUser = $('#hdnIdUser').val();
				aparece('divEvento');
				
				$('.list').find('li').removeClass('active');
				$('#imgEventos').addClass('active');
				$('#divRespuesta').load("ajax/cargarEventos.php",{idUser:idUser}, function(){
					$('#inventario').waitMe("hide");
				});
			});
			
			$('#imgInversiones').click(function(){
				ids = $('#hdnIds').val();
				
				aparece('divInversionesLista');
				
				$('.list').find('li').removeClass('active');
				$('#imgInversiones').addClass('active');
				$('#divRespuesta').load("ajax/cargarInversiones.php",{ids:ids},function(){
					$('#inventario').waitMe("hide");
				});
			});
			
			$('#imgCalendarioMercado').click(function(){
				aparece('divCiclos');
			});
			
			$('#imgMensajes').click(function(){
				var idUser = $('#hdnIdUser').val();
				var tabMsj = 1; //entrada
				$('.list').find('li').removeClass('active');
				$('#imgMensajes').addClass('active');
				$('#tbMensajesEntrada').load("ajax/cargarMensajes.php",{idUser:idUser,tabMsj:tabMsj},function(){
					aparece('divMensajes');
				});
			});
			
			$('#imgInventario').click(function(){
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var	idUsuario = $('#hdnIdUser').val();
				var ids = $('#hdnIds').val();
				var pestana = $("#hdnPestana").val();
				var producto = $("#sltProductosInv").val();
				var repre = $("#hdnIdsFiltroUsuarios").val();
				var pendiente = '';
				
				aparece('divInventario');

				if($('#chkExistencia').is(':checked')) { 
					existencia = 1;
				}else{
					existencia = 0;
				}
				if($('#lblLoteExpirado').is(':checked')) { 
					expirado = 1;
				}else{
					expirado = 0;
				}
				cargandoInventario();
				$('#divRespuesta').load("ajax/actualizaInventario.php",
					{
						tipoUsuario:tipoUsuario,
						idUsuario:idUsuario,
						ids:ids,
						existencia:existencia,
						expirado:expirado
					},function()
					{
						$('#inventario').waitMe("hide");
					}
				);
				
				$('.list').find('li').removeClass('active');
				$('#imgInventario').addClass('active');
			});
			
			$('#chkExistencia').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				var pestana = $("#hdnPestana").val();
				var producto = $("#sltProductosInv").val();
				var repre = $("#hdnIdsFiltroUsuarios").val();
				var ids = $("#hdnIds").val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var pendiente = '';
				if (pestana == 'aprobacion') {
					if ($('#btnPendienteAprobacion').hasClass('seleccionado')) {
						pendiente = 0;
						$('#btnPendienteAprobacion').addClass("seleccionado");
						$('#btnAceptadoInv').removeClass("seleccionado");
						$('#btnRechazadoInv').removeClass("seleccionado");

					} else if ($('#btnAceptadoInv').hasClass('seleccionado')) {
						pendiente = 1;
						$('#btnPendienteAprobacion').removeClass("seleccionado");
						$('#btnAceptadoInv').addClass("seleccionado");
						$('#btnRechazadoInv').removeClass("seleccionado");

					} else if ($('#btnRechazadoInv').hasClass('seleccionado')) {
						pendiente = 2;
						$('#btnPendienteAprobacion').removeClass("seleccionado");
						$('#btnAceptadoInv').removeClass("seleccionado");
						$('#btnRechazadoInv').addClass("seleccionado");

					}
				}
				if($("#chkExistencia").is(":checked")){
					existencia = 1;
				}else{
					existencia = 0;
				}
				if($("#chkLoteExpirado").is(":checked")){
					expirado = 1;
				}else{
					expirado = 0;
				}
				cargandoInventario();
				$("#divRespuesta").load("ajax/cargarInventario.php",{pestana:pestana,producto:producto,repre:repre,ids:ids,pendiente:pendiente,idUsuario:idUsuario,existencia:existencia,tipoUsuario:tipoUsuario,expirado:expirado},function(){
					$('#inventario').waitMe("hide");
				});
			});

			$('#chkLoteExpirado').click(function(){
				
				var idUsuario = $('#hdnIdUser').val();
				var pestana = $("#hdnPestana").val();
				var producto = $("#sltProductosInv").val();
				var repre = $("#hdnIdsFiltroUsuarios").val();
				var ids = $("#hdnIds").val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var pendiente = '';
				if (pestana == 'aprobacion') {
					if ($('#btnPendienteAprobacion').hasClass('seleccionado')) {
						pendiente = 0;
						$('#btnPendienteAprobacion').addClass("seleccionado");
						$('#btnAceptadoInv').removeClass("seleccionado");
						$('#btnRechazadoInv').removeClass("seleccionado");

					} else if ($('#btnAceptadoInv').hasClass('seleccionado')) {
						pendiente = 1;
						$('#btnPendienteAprobacion').removeClass("seleccionado");
						$('#btnAceptadoInv').addClass("seleccionado");
						$('#btnRechazadoInv').removeClass("seleccionado");

					} else if ($('#btnRechazadoInv').hasClass('seleccionado')) {
						pendiente = 2;
						$('#btnPendienteAprobacion').removeClass("seleccionado");
						$('#btnAceptadoInv').removeClass("seleccionado");
						$('#btnRechazadoInv').addClass("seleccionado");

					}
				}
				if($("#chkExistencia").is(":checked")){
					existencia = 1;
				}else{
					existencia = 0;
				}
				if($("#chkLoteExpirado").is(":checked")){
					expirado = 1;
				}else{
					expirado = 0;
				}
				cargandoInventario();
				$("#divRespuesta").load("ajax/cargarInventario.php",{pestana:pestana,producto:producto,repre:repre,ids:ids,pendiente:pendiente,idUsuario:idUsuario,existencia:existencia,tipoUsuario:tipoUsuario,expirado:expirado},function(){
					$('#inventario').waitMe("hide");
				});
			});

			$('#imgReportes').click(function(){
				aparece('divReportes');
				
				$('.list').find('li').removeClass('active');
				$('#imgReportes').addClass('active');
			});

			$('#imgListados').click(function(){
				aparece('divListados');
				
				$('.list').find('li').removeClass('active');
				$('#imgListados').addClass('active');
			});
			
			$('#imgGeo').click(function(){
				load_map('mapa');
				aparece('divGeo');
				
				$('.list').find('li').removeClass('active');
				$('#imgGeo').addClass('active');
			});
			
			$('#imgConfig').click(function(){
				aparece('divConfig');
				$('.list').find('li').removeClass('active');
			});

			$('#goInicio').click(function(){
				var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				var hoy = $('#hdnHoy').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				$('#divRespuesta').load("ajax/cargarPlanesInicio.php",{ids:ids,hoy:hoy,tipoUsuario:tipoUsuario});
				$('#grafica3').load("ajax/grafica3.php",{ids:ids,hoy:hoy});
				aparece('divInicio');
				
				$('.list').find('li').removeClass('active');
				$('#imgHome').addClass('active');
			});
			
			$('#anterior').click(function(){
				//alert('aqui mero');
			});
			
			$('#imgDocumentosEntregados').click(function(){
				aparece('divDocumentos');
				
				$('.list').find('li').removeClass('active');
				$('#imgDocumentosEntregados').addClass('active');
			});
			
			$('#imgAprobaciones').click(function(){
				ids = $('#hdnIds').val();
				ruta = $('#sltRutasAprobacionesGerentePers').val();
				tipoMovimiento = $('#sltTipoMovimientoAprobacionesGte').val();
				aparece('divAprobaciones');
				$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,estatus:1,ruta:ruta,tipoMovimiento:tipoMovimiento});
				
				$('.list').find('li').removeClass('active');
				$('#imgAprobaciones').addClass('active');

				$("#btnPendienteAprobacionesGerentePers").click();
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

			$('#sltGte').on('change', function(){
				var idRuta2 = $('#sltGte').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				idUser = $('#hdnIdUser').val();
				hoy = $('#hdnHoy').val();
				cargandoGrafica1();
				$('#sltRutas').load('ajax/cargarGteInicio.php',{idRuta2:idRuta2, tipoUsuario:tipoUsuario, ids:ids});
				$('#sltRutas2').load('ajax/cargarGteInicio.php',{idRuta2:idRuta2, tipoUsuario:tipoUsuario, ids:ids});
				
				$('#divRespuesta').load('ajax/cargarPlanesInicio.php',{idRuta:idRuta2, ids:ids, tipoUsuario:tipoUsuario, hoy:hoy});
				$('#grafica3').load("ajax/grafica3.php",{idRuta:idRuta2,ids:ids,tipoUsuario:tipoUsuario,idUser:idUser},function(){
					$('.grafica1-body').waitMe('hide');
				});

			});
			
			$('#sltRutas').on('change', function(){
				idRuta = $('#sltRutas').val();
				ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				hoy = $('#hdnHoy').val();

				$("#tblListFrecCategoria").show();

				$("#showFreCat").show();
				$("#hideFreCat").hide();
				$("#showCobCat").show();
				$("#hideCobCat").hide();
				$("#showDisFichero").show();
				$("#hideDisFichero").hide();

				cargandoGrafica1();
				$('#divRespuesta').load('ajax/cargarPlanesInicio.php',{idRuta:idRuta, ids:ids, tipoUsuario:tipoUsuario, hoy:hoy});
				$('#grafica3').load("ajax/grafica3.php",{idRuta:idRuta,ids:ids},function(){
					$('.grafica1-body').waitMe('hide');
				});
			});

			var $selectInicio = $('#sltRutas,#sltRutas2');
				
			$selectInicio.change(function() {
				$selectInicio.val(this.value);
				idRuta = $('#sltRutas2').val();
				ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				hoy = $('#hdnHoy').val();
				$('#divRespuesta').load('ajax/cargarPlanesInicio.php',{idRuta:idRuta, ids:ids, tipoUsuario:tipoUsuario, hoy:hoy});
			}).change();

			$('#sltRutas2').on('change', function(){
				idRuta = $('#sltRutas2').val();
				ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();

				$('#containerFreCat').hide();
				$('#containerCobCat').hide();
				$('#containerDisFichero').hide();
				$('#containerCobFichero').hide();
				$('#containerDisQuintil').hide();

				$("#tblListFrecCategoria").show();
				$("#showFreCat").show();
				$("#hideFreCat").hide();
				$("#showCobCat").show();
				$("#hideCobCat").hide();
				$("#showDisFichero").show();
				$("#hideDisFichero").hide();
				$("#showCobFichero").show();
				$("#hideCobFichero").hide();
				$("#showDisQuintil").show();
				$("#hideDisQuintil").hide();
				$("#showCobQuintil").show();
				$("#hideCobQuintil").hide();

				tipoUsuario = $('#hdnTipoUsuario').val();
				hoy = $('#hdnHoy').val();

				if($('#btnDivGraficas2').hasClass('bullet_seleccionado')){
					cargandoGrafica2();
				}
				if($('#btnDivGraficas3').hasClass('bullet_seleccionado')){
					cargandoGrafica3();
				}
				if($('#btnDivGraficas4').hasClass('bullet_seleccionado')){
					cargandoGrafica4();
				}
				if($('#btnDivGraficas5').hasClass('bullet_seleccionado')){
					cargandoGrafica5();
				}

				$('#divRespuesta').load('ajax/cargarPlanesInicio.php',{idRuta:idRuta, ids:ids, tipoUsuario:tipoUsuario, hoy:hoy});
				$('#grafica1').load("ajax/grafica1.php",{idRuta:idRuta,ids:ids},function(){
					$('.grafica2-body').waitMe('hide');
				});
				$('#grafica5').load("ajax/grafica5.php",{idRuta:idRuta,ids:ids},function(){
					$('.grafica3-body').waitMe('hide');
				});
				$('#grafica7').load("ajax/grafica7.php",{idRuta:idRuta,ids:ids},function(){
					$('.grafica4-body').waitMe('hide');
				});
				$('#grafica9').load("ajax/grafica9.php",{idRuta:idRuta,ids:ids},function(){
					$('.grafica5-body').waitMe('hide');
				});
			});
			
			$('#btnDivGraficas1').click(function(){
				if(! $('#btnDivGraficas1').hasClass('bullet_seleccionado')){
					idRuta = $('#sltRutas').val();
					ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					var hoy = $('#hdnHoy').val();
					var tipoUsuario = $('#hdnTipoUsuario').val();
					$('#btnDivGraficas1').addClass("bullet_seleccionado");
					$('#btnDivGraficas2').removeClass("bullet_seleccionado");
					$('#btnDivGraficas3').removeClass("bullet_seleccionado");
					$('#btnDivGraficas4').removeClass("bullet_seleccionado");
					$('#btnDivGraficas5').removeClass("bullet_seleccionado");
					$('#grafica3').load("ajax/grafica3.php",{idRuta:idRuta,ids:ids},function(){
						$('.grafica1-body').waitMe('hide');
					});
					$('#tblGraficas1').show();
					$('#tblGraficas2').hide();
					$('#tblGraficas3').hide();
					$('#tblGraficas4').hide();
					$('#tblGraficas5').hide();
					$("#sltRepreInicio").hide();
					$("#sltRepreInicio").removeClass('slt-visible');
					$('#showSltGte').removeClass('display-flex');

				}
			});
			$('#btnDivGraficas2').click(function(){
				if(! $('#btnDivGraficas2').hasClass('bullet_seleccionado')){
					idRuta = $('#sltRutas2').val();
					ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					$('#btnDivGraficas1').removeClass("bullet_seleccionado");
					$('#btnDivGraficas2').addClass("bullet_seleccionado");
					$('#btnDivGraficas3').removeClass("bullet_seleccionado");
					$('#btnDivGraficas4').removeClass("bullet_seleccionado");
					$('#btnDivGraficas5').removeClass("bullet_seleccionado");

					cargandoGrafica2();
					$('#grafica1').load("ajax/grafica1.php",{idRuta:idRuta,ids:ids},function(){
						$('.grafica2-body').waitMe('hide');
					});
					$('#tblGraficas1').hide();
					$('#tblGraficas2').show();
					$('#tblGraficas3').hide();
					$('#tblGraficas4').hide();
					$('#tblGraficas5').hide();
					$("#sltRepreInicio").show();
					$("#sltRepreInicio").addClass('slt-visible');
					$('#showSltGte').addClass('display-flex');

					$('#containerFreCat').hide();
					$('#containerCobCat').hide();
				}
			});
			$('#btnDivGraficas3').click(function(){
				if(! $('#btnDivGraficas3').hasClass('bullet_seleccionado')){
					idRuta = $('#sltRutas2').val();
					ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					$('#btnDivGraficas1').removeClass("bullet_seleccionado");
					$('#btnDivGraficas2').removeClass("bullet_seleccionado");
					$('#btnDivGraficas3').addClass("bullet_seleccionado");
					$('#btnDivGraficas4').removeClass("bullet_seleccionado");
					$('#btnDivGraficas5').removeClass("bullet_seleccionado");

					cargandoGrafica3();
					$('#grafica5').load("ajax/grafica5.php",{idRuta:idRuta,ids:ids},function(){
						$('.grafica3-body').waitMe('hide');
					});
					$('#tblGraficas1').hide();
					$('#tblGraficas2').hide();
					$('#tblGraficas3').show();
					$('#tblGraficas4').hide();
					$('#tblGraficas5').hide();
					$("#sltRepreInicio").show();
					$("#sltRepreInicio").addClass('slt-visible');
					$('#showSltGte').addClass('display-flex');

					$('#containerDisFichero').hide();
					$('#containerCobFichero').hide();
				}
			});
			$('#btnDivGraficas4').click(function(){
				if(! $('#btnDivGraficas4').hasClass('bullet_seleccionado')){
					idRuta = $('#sltRutas2').val();
					ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					$('#btnDivGraficas1').removeClass("bullet_seleccionado");
					$('#btnDivGraficas2').removeClass("bullet_seleccionado");
					$('#btnDivGraficas3').removeClass("bullet_seleccionado");
					$('#btnDivGraficas4').addClass("bullet_seleccionado");
					$('#btnDivGraficas5').removeClass("bullet_seleccionado");

					cargandoGrafica4();
					$('#grafica7').load("ajax/grafica7.php",{idRuta:idRuta,ids:ids},function(){
						$('.grafica4-body').waitMe('hide');
					});
					$('#tblGraficas1').hide();
					$('#tblGraficas2').hide();
					$('#tblGraficas3').hide();
					$('#tblGraficas4').show();
					$('#tblGraficas5').hide();
					$("#sltRepreInicio").show();
					$("#sltRepreInicio").addClass('slt-visible');
					$('#showSltGte').addClass('display-flex');

					$('#containerDisQuintil').hide();
					$('#containerCobQuintil').hide();
				}
			});
			$('#btnDivGraficas5').click(function(){
				if(! $('#btnDivGraficas5').hasClass('bullet_seleccionado')){
					idRuta = $('#sltRutas2').val();
					ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					$('#btnDivGraficas1').removeClass("bullet_seleccionado");
					$('#btnDivGraficas2').removeClass("bullet_seleccionado");
					$('#btnDivGraficas3').removeClass("bullet_seleccionado");
					$('#btnDivGraficas4').removeClass("bullet_seleccionado");
					$('#btnDivGraficas5').addClass("bullet_seleccionado");

					cargandoGrafica5();
					$('#grafica9').load("ajax/grafica9.php",{idRuta:idRuta,ids:ids},function(){
						$('.grafica5-body').waitMe('hide');
					});
					$('#tblGraficas1').hide();
					$('#tblGraficas2').hide();
					$('#tblGraficas3').hide();
					$('#tblGraficas4').hide();
					$('#tblGraficas5').show();
					$("#sltRepreInicio").show();
					$("#sltRepreInicio").addClass('slt-visible');
					$('#showSltGte').addClass('display-flex');

				}
			});

			$("#showFreCat").click(function(){
				$("#tblListFrecCategoria").hide();
				$("#showFreCat").hide();
				$("#hideFreCat").show();
			});
			$("#hideFreCat").click(function(){
				$("#tblListFrecCategoria").show();
				$("#showFreCat").show();
				$("#hideFreCat").hide();
			});
			$("#showCobCat").click(function(){
				$("#tblListCobCat").hide();
				$("#showCobCat").hide();
				$("#hideCobCat").show();
			});
			$("#hideCobCat").click(function(){
				$("#tblListCobCat").show();
				$("#showCobCat").show();
				$("#hideCobCat").hide();
			});
			$("#showDisFichero").click(function(){
				$("#tblListDisFichero").hide();
				$("#showDisFichero").hide();
				$("#hideDisFichero").show();
			});
			$("#hideDisFichero").click(function(){
				$("#tblListDisFichero").show();
				$("#showDisFichero").show();
				$("#hideDisFichero").hide();
			});
			$("#showCobFichero").click(function(){
				$("#tblListCobFichero").hide();
				$("#showCobFichero").hide();
				$("#hideCobFichero").show();
			});
			$("#hideCobFichero").click(function(){
				$("#tblListCobFichero").show();
				$("#showCobFichero").show();
				$("#hideCobFichero").hide();
			});
			$("#showDisQuintil").click(function(){
				$("#tblListDisQuintil").hide();
				$("#showDisQuintil").hide();
				$("#hideDisQuintil").show();
			});
			$("#hideDisQuintil").click(function(){
				$("#tblListDisQuintil").show();
				$("#showDisQuintil").show();
				$("#hideDisQuintil").hide();
			});
			$("#showCobQuintil").click(function(){
				$("#tblListCobQuintil").hide();
				$("#showCobQuintil").hide();
				$("#hideCobQuintil").show();
			});
			$("#hideCobQuintil").click(function(){
				$("#tblListCobQuintil").show();
				$("#showCobQuintil").show();
				$("#hideCobQuintil").hide();
			});

			/* fin de inicio */
			
			/*personas*/
			
			/*$('#lstProductoDatosPersonales').change(function(){
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
			});*/

			$('#btnTodosPersonas').click(function(){
				$('#txtBuscarMedico').val('');

				$('#btnReVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnNoVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnTodosPersonas').addClass("btn-account-sel");

				$("#btnReVisitadosPersonas").removeAttr("disabled");
				$("#btnVisitadosPersonas").removeAttr("disabled");
				$("#btnNoVisitadosPersonas").removeAttr("disabled");
				$("#btnTodosPersonas").attr("disabled", true);
			});
			$('#btnVisitadosPersonas').click(function(){
				$('#txtBuscarMedico').val('');
				
				$('#btnReVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnNoVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnTodosPersonas').removeClass("btn-account-sel");
				$('#btnVisitadosPersonas').addClass("btn-account-sel");

				$("#btnReVisitadosPersonas").removeAttr("disabled");
				$("#btnTodosPersonas").removeAttr("disabled");
				$("#btnNoVisitadosPersonas").removeAttr("disabled");
				$("#btnVisitadosPersonas").attr("disabled", true);
			});
			$('#btnReVisitadosPersonas').click(function(){
				$('#txtBuscarMedico').val('');
				
				$('#btnNoVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnTodosPersonas').removeClass("btn-account-sel");
				$('#btnVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnReVisitadosPersonas').addClass("btn-account-sel");

				$("#btnTodosPersonas").removeAttr("disabled");
				$("#btnVisitadosPersonas").removeAttr("disabled");
				$("#btnNoVisitadosPersonas").removeAttr("disabled");
				$("#btnReVisitadosPersonas").attr("disabled", true);
			});
			$('#btnNoVisitadosPersonas').click(function(){
				$('#txtBuscarMedico').val('');
				
				$('#btnReVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnTodosPersonas').removeClass("btn-account-sel");
				$('#btnNoVisitadosPersonas').addClass("btn-account-sel");

				$("#btnReVisitadosPersonas").removeAttr("disabled");
				$("#btnVisitadosPersonas").removeAttr("disabled");
				$("#btnTodosPersonas").removeAttr("disabled");
				$("#btnNoVisitadosPersonas").attr("disabled", true);
			});

			$('#imgFiltrar2').click(function(){
				$('#imgFiltrar2').addClass("btn-red-slt");
				$('#trFiltros2').show('slow');
			});
			
			$('#imgFiltrar').click(function(){
				$('#trFiltros2').hide('slow');
				$('#imgFiltrar2').removeClass("btn-red-slt");
				$('#btnReVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnVisitadosPersonas').removeClass("btn-account-sel");
				$('#btnNoVisitadosPersonas').removeClass("btn-account-sel");
				
				$('#btnTodosPersonas').addClass("btn-account-sel");
				$("#btnReVisitadosPersonas").removeAttr("disabled");
				$("#btnVisitadosPersonas").removeAttr("disabled");
				$("#btnNoVisitadosPersonas").removeAttr("disabled");
				$("#btnTodosPersonas").attr("disabled", true);
			});

			/**Pantalla pequeña */

			$('.btnTodosPersonas2').click(function(){
				$('.btnReVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnNoVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnTodosPersonas2').addClass("btn-account-sel");

				$(".btnReVisitadosPersonas2").removeAttr("disabled");
				$(".btnVisitadosPersonas2").removeAttr("disabled");
				$(".btnNoVisitadosPersonas2").removeAttr("disabled");
				$(".btnTodosPersonas2").attr("disabled", true);
			});
			$('.btnVisitadosPersonas2').click(function(){
				$('.btnReVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnNoVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnTodosPersonas2').removeClass("btn-account-sel");
				$('.btnVisitadosPersonas2').addClass("btn-account-sel");

				$(".btnReVisitadosPersonas2").removeAttr("disabled");
				$(".btnTodosPersonas2").removeAttr("disabled");
				$(".btnNoVisitadosPersonas2").removeAttr("disabled");
				$(".btnVisitadosPersonas2").attr("disabled", true);
			});
			$('.btnReVisitadosPersonas2').click(function(){
				$('.btnNoVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnTodosPersonas2').removeClass("btn-account-sel");
				$('.btnVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnReVisitadosPersonas2').addClass("btn-account-sel");

				$(".btnTodosPersonas2").removeAttr("disabled");
				$(".btnVisitadosPersonas2").removeAttr("disabled");
				$(".btnNoVisitadosPersonas2").removeAttr("disabled");
				$(".btnReVisitadosPersonas2").attr("disabled", true);
			});
			$('.btnNoVisitadosPersonas2').click(function(){
				$('.btnReVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnTodosPersonas2').removeClass("btn-account-sel");
				$('.btnNoVisitadosPersonas2').addClass("btn-account-sel");

				$(".btnReVisitadosPersonas2").removeAttr("disabled");
				$(".btnVisitadosPersonas2").removeAttr("disabled");
				$(".btnTodosPersonas2").removeAttr("disabled");
				$(".btnNoVisitadosPersonas2").attr("disabled", true);
			});

			$('#imgFiltrar').click(function(){
				$('#trFiltros2').hide('slow');
				$('#filtrar2').removeClass("btn-account-sel");
				$('.btnReVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnVisitadosPersonas2').removeClass("btn-account-sel");
				$('.btnNoVisitadosPersonas2').removeClass("btn-account-sel");
				
				$('.btnTodosPersonas2').addClass("btn-account-sel");
				$(".btnReVisitadosPersonas2").removeAttr("disabled");
				$(".btnVisitadosPersonas2").removeAttr("disabled");
				$(".btnNoVisitadosPersonas2").removeAttr("disabled");
				$(".btnTodosPersonas2").attr("disabled", true);
			});

			$('#filtrar2').click(function(){
				$('#filtrar2').addClass("btn-account-sel");
				$('#trFiltros2').show('slow');
			});

			
			$('#imgAgregarPersona').click(function () {
				var idUsusario = $('#hdnIdUser').val();
				var idInst = $('#hdnIdInst').val();

				 banderaedit=0;
				$('#btnReactivarPersonaNuevo').hide();

				$('#aPerfilPersona').click();
				$('#divPersona').show();
				$('#divCapa3').show('slow');

				$("#tabsPersona").tabs("option", "active", 0);
				$('#tabPer1').addClass('active');
				$('#tabPer2').removeClass('active');
				$('#tabPer3').removeClass('active');
				$('#tabPer4').removeClass('active');
				$('#tabPer5').removeClass('active');
				$('#tabPer6').removeClass('active');
				$('#tabPer7').removeClass('active');

				$('#divRespuesta').load("ajax/cargarPersonaNueva.php", {
					idUsusario: idUsusario,
					idInst: idInst
				});
			});
			
			$('#lstYearMuestra').change(function(){
				$("#divRespuesta").load("ajax/cargarMuestra.php",{year:$('#lstYearMuestra').val(),idPersona:$('#hdnIdPersona').val()});
			});
			
			$('#imgAgregarPlan').click(function(){
				var idPersona = $('#hdnIdPersona').val();
				ruta = $('#sltRutaBuscaPersonas').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				idUser = $('#hdnIdUser').val();
				$('#btnEliminarPlanPerson').hide();
				$('#btnCancelarSigPlan').hide();
				$('#divPlanes').show();
				$('#divCapa3').show('slow');
				$("#divRespuesta").load("ajax/cargarPlan.php", {idPersona:idPersona,ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser});
			});
			
			$('#imgAgregarVisita').click(function(){
				var idPersona = $('#hdnIdPersona').val();
				ruta = $('#sltRutaBuscaPersonas').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				idUser = $('#hdnIdUser').val();
				especialidad = $('#hdnEspecialidadPersona').val();
				cicloActivo = $('#hdnCicloActivo').val();
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");

				$("#btnEliminarVisitaPerson").hide();
				$("#divVisitas").show();
				$("#divCapa3").show("slow");
				$("#divRespuesta").load("ajax/cargarVisita.php",{idPersona:idPersona,lat:lat,lon:lon,ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser,especialidad:especialidad,cicloActivo:cicloActivo});
			});
			
			$('#btnLimpiarFiltros').click(function(){
				$('#sltEstatusFiltro').val('00000000-0000-0000-0000-000000000000');
				$('#txtNombreFiltro').val('');
				$('#txtApellidosFiltro').val('');
				//$('#sltSexoFiltro').val('');
				$('#sltEspecialidadFiltro').val('00000000-0000-0000-0000-000000000000');
				$('#sltCategoriaFiltro').val('00000000-0000-0000-0000-000000000000');
				$('#txtInstitucionFiltro').val('');
				$('#txtDireccionFiltro').val('');
				$('#txtDelegacionFiltro').val('');
				$('#txtEstadoFiltro').val('');
				$('#sltMultiSelectPersonas').text('Seleccione Representante');
				$('#chkTodosRutasPersonas').prop('checked', false);
				$('#hdnIdsFiltroUsuarios').val('');
				$('#hdnNombresFiltroUsuarios').val('');
				$('#tblUsuariosSeleccionados').empty();
				$('#txtColoniaFiltro').val('');
				$('#txtCPFiltro').val('');
				$('#txtBrickFiltro').val('');
				$('#sltBajasFiltro').val('00000000-0000-0000-0000-000000000000');
				$('#sltCategoriaAlfaSigmaFiltro').val('00000000-0000-0000-0000-000000000000');
				//$('#btnEjecutarFiltro').click();
				$('#cardInfMedicos').show();
				$('#divMedicosInactivos').hide();
				muestraEditaPersona = 0;
			});

			$('#btnEjecutarFiltro').click(function(){
				var tipoUsuario = $("#hdnTipoUsuario").val();
				var estatusMed = $('#sltBajasFiltro').val();
				$('#txtBuscarMedico').val('');

				if(tipoUsuario == 2){
					if(estatusMed != '00000000-0000-0000-0000-000000000000'){
						muestraEditaPersona = 1;
					}else{
						muestraEditaPersona = 0;
					}
				}else{
					muestraEditaPersona = 0;
				}
				$('#imgFiltrar').click();
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
				var tipoUsuario = $("#hdnTipoUsuario").val();
				cargandoTablaVisitaMed();
				$('#divRespuesta').load("ajax/cargarVisitas.php",{idPersona:idPersona,year:year,idUsuario:idUsuario, tipoUsuario:tipoUsuario}, function(){
					$('#tblVisitas').waitMe('hide');
				});
			});
			
			$('#lstYearPlanes').change(function(){
				var idPersona = $('#hdnIdPersona').val();
				var idUsuario = $('#hdnIdUser').val();
				var year = $('#lstYearPlanes').val();
				var tipoUsuario = $("#hdnTipoUsuario").val();
				cargandoTablaPlanMed();
				$('#divRespuesta').load("ajax/cargarPlanes.php",{idPersona:idPersona,year:year,idUsuario:idUsuario,tipoUsuario:tipoUsuario}, function(){
					$('#tblPlan').waitMe('hide');
				});
			});
			
			$('#btnAprobacionesPers').click(function(){
				$('#divAprobacionesPers').show();
				$('#over2').show(500);
				$('#fade').show(500);
				$('#btnPendienteAprobacionesPers').click();
			});
			
			
			$('#btnPendienteAprobacionesPers').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				tipo = $('#sltTipoMovimientoAprobacionesPers').val();

				$('#btnAceptadoAprobacionesPers').removeClass("btn-aprob-sel");
				$('#btnRechazadoAprobacionesPers').removeClass("btn-aprob-sel");
				$('#btnPendienteAprobacionesPers').addClass("btn-aprob-sel");

				$("#btnAceptadoAprobacionesPers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesPers").removeAttr("disabled");
				$("#btnPendienteAprobacionesPers").attr("disabled", true);


				$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:1,tipo:tipo});
			});
			
			$('#btnAceptadoAprobacionesPers').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				tipo = $('#sltTipoMovimientoAprobacionesPers').val();
				
				$('#btnPendienteAprobacionesPers').removeClass("btn-aprob-sel");
				$('#btnRechazadoAprobacionesPers').removeClass("btn-aprob-sel");
				$('#btnAceptadoAprobacionesPers').addClass("btn-aprob-sel");

				$("#btnPendienteAprobacionesPers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesPers").removeAttr("disabled");
				$("#btnAceptadoAprobacionesPers").attr("disabled", true);

				$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:2,tipo:tipo});
			});
			
			$('#btnRechazadoAprobacionesPers').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				tipo = $('#sltTipoMovimientoAprobacionesPers').val();
				
				$('#btnPendienteAprobacionesPers').removeClass("btn-aprob-sel");
				$('#btnAceptadoAprobacionesPers').removeClass("btn-aprob-sel");
				$('#btnRechazadoAprobacionesPers').addClass("btn-aprob-sel");

				$("#btnPendienteAprobacionesPers").removeAttr("disabled");
				$("#btnAceptadoAprobacionesPers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesPers").attr("disabled", true);

				$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:3,tipo:tipo});
			});
			
			$('#btnAprobacionesInst').click(function(){
				$('#divAprobacionesInst').show();
				$('#over2').show(500);
				$('#fade').show(500);
				$('#btnPendienteAprobacionesInst').click();
			});
			
			$('#btnAceptarBaja').click(function(){
				var idPersonaBaja = $('#hdnIdPersonaBaja').val();
				var idUsuario = $('#hdnIdUser').val(); 
				var motivo = $('#sltMotivoBaja').val()
				var comentarios = $('#txtComentariosBaja').val();
				if(motivo == ''){
					alertIngresarMotivo();
					return;
				}
				
				/*if(comentarios == ''){
					alertIngresarComen();
					return;
				}*/
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
			
			/* inversiones */
			
			$('#imgAgregarInversion').click(function(){
				var idPersona = $('#hdnIdPersona').val();
				ruta = $('#sltRutaBuscaPersonas').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				idUser = $('#hdnIdUser').val();
				$('#divInversiones').show();
				$('#divCapa3').show('slow');
				$("#divRespuesta").load("ajax/cargarInversion.php", {idPersona:idPersona,ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser});
			});
			
			/* fin de inversiones */
			
			/*persona */
							
			$("#btnSleccionaInst").click(function(){
				$("#divBusqueda").show();
				$("#tblDatosGnr").hide();
				$('#buscarFarmacia').hide();
				$('#buscarInst').show();
				$('#buscarHospital').hide();
				palabra = $("#txtBusqueda").val('');
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				cargandoBuscadorInst();
				$("#divRespuesta").load("ajax/instFiltradas.php",{palabra:'',idUsuario:'<?= $idUsuario ?>',ids:ids,tipoUsuario:tipoUsuario},function(){
					$('#tblBuscarInst').waitMe('hide');
				});
			});

			$("#txtBusqueda").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					palabra = $("#txtBusqueda").val();
					ids = $("#hdnIds").val();
					idUsuario = $("#hdnIdUser").val();
					tipoUsuario = $("#hdnTipoUsuario").val();

					cargandoBuscadorInst();
					$("#divRespuesta").load("ajax/instFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,tipoUsuario:tipoUsuario}, function(){
						$('#tblBuscarInst').waitMe('hide');
					});
				}
			});

			$("#btnBuscarInstMed").click(function() {
				palabra = $("#txtBusqueda").val();
				ids = $("#hdnIds").val();
				idUsuario = $("#hdnIdUser").val();
				tipoUsuario = $("#hdnTipoUsuario").val();

				cargandoBuscadorInst();
				$("#divRespuesta").load("ajax/instFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,tipoUsuario:tipoUsuario}, function(){
					$('#tblBuscarInst').waitMe('hide');
				});
			});
			
			$("#txtBuscarMedico").keypress(function(e) {
				var ids = $('#hdnIdsEnviarPersonas').val();
				var hoy = $('#hdnHoy').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var visitados = '';
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){

					buscarMedico = $("#txtBuscarMedico").val();
					nuevaPagina(1, hoy, ids, visitados);
				}
			});
			
			$("#btnBuscarMedList").click(function() {
				var ids = $('#hdnIdsEnviarPersonas').val();
				var hoy = $('#hdnHoy').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var visitados = '';

				buscarMedico = $("#txtBuscarMedico").val();
				nuevaPagina(1, hoy, ids, visitados);
			
			});
			
			$('#btnCancelarPersonaNuevo').click(function () {
				$('#divPersona').hide();
				$('#divCapa3').hide();
				limpiaPersonaNuevo();
				$('#' + idmedico).click();
				$('#' + idTrMedico).addClass('div-slt-lista');
			});
			
			$('#btnCerrarBuscarInst').click(function(){
				$("#divBusqueda").hide();
				$("#tblDatosGnr").show();
			});


			$('.tab-sidebar-medicos-r').click(function(){
				$("#leftsidebarMedicos").addClass('showleftsidebarMedicos');
				$("#leftsidebarMedicos").removeClass('hideleftsidebarMedicos');
				$(".tab-sidebar-medicos-r").css("display", "none");
				$(".tab-sidebar-medicos-l").css("display", "block");
			});

			
			$('.tab-sidebar-medicos-l').click(function(){
				$("#leftsidebarMedicos").removeClass('showleftsidebarMedicos');
				$("#leftsidebarMedicos").addClass('hideleftsidebarMedicos');
				$(".tab-sidebar-medicos-l").css("display", "none");
				$(".tab-sidebar-medicos-r").css("display", "block");
			});

			$('.tab-sidebar-inst-r').click(function(){
				$("#leftsidebarInst").addClass('showleftsidebarInst');
				$("#leftsidebarInst").removeClass('hideleftsidebarInst');
				$(".tab-sidebar-inst-r").css("display", "none");
				$(".tab-sidebar-inst-l").css("display", "block");
			});

			
			$('.tab-sidebar-inst-l').click(function(){
				$("#leftsidebarInst").removeClass('showleftsidebarInst');
				$("#leftsidebarInst").addClass('hideleftsidebarInst');
				$(".tab-sidebar-inst-l").css("display", "none");
				$(".tab-sidebar-inst-r").css("display", "block");
			});

			/**Registrar persona */
			
			var sizeScreen = $(window).height(); //969
			var sizeCardCenter = $('.cardPersonaCenter').outerHeight(); //843
			
			var headerCapa3 = $('.headerCardCapa3').outerHeight();
			var heightFloatScreen = ($(window).height() - ($('.cardCapa3').outerHeight(true) + $('.headerCardCapa3').outerHeight() + $('.navbarTabs').outerHeight(true)));

			var heightNewDiv = heightFloatScreen + 'px';
				
			var marginCenterVerDiv = sizeScreen - sizeCardCenter;

			agregarPersona();
			$("#btnGuardarPersonaNuevo").click(function () {
				$("#btnGuardarPersonaNuevo").prop("disabled", true);
				
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var registroCorrecto = true;
				if ($("#formAgregarPersona").valid()) {
					registroCorrecto = true;

					if ($('#sltDiaFechaNacimientoPersonaNuevo').val() != null) {
						if ($('#sltMesFechaNacimientoPersonaNuevo').val() == null) {
							alertFechaNacimientoMes();
							$('#sltMesFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
						if ($('#sltAnioFechaNacimientoPersonaNuevo').val() == null) {
							alertFechaNacimientoAnio();
							$('#sltAnioFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
					}

					if ($('#sltMesFechaNacimientoPersonaNuevo').val() != null) {
						if ($('#sltDiaFechaNacimientoPersonaNuevo').val() == null) {
							alertFechaNacimientoDia();
							$('#sltDiaFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
						if ($('#sltAnioFechaNacimientoPersonaNuevo').val() == null) {
							alertFechaNacimientoAnio();
							$('#sltAnioFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
					}
					if ($('#sltAnioFechaNacimientoPersonaNuevo').val() != null) {
						if ($('#sltDiaFechaNacimientoPersonaNuevo').val() == null) {
							alertFechaNacimientoDia();
							$('#sltDiaFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
						if ($('#sltMesFechaNacimientoPersonaNuevo').val() == null) {
							alertFechaNacimientoMes();
							$('#sltMesFechaNacimientoPersonaNuevo').focus();
							registroCorrecto = false;
						}
					}
					
					xrEmail = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;
					
					if($.trim($("#txtCorreoPersonalPersonaNuevo").val().toUpperCase()) != 'NO TIENE' &&
						$.trim($("#txtCorreoPersonalPersonaNuevo").val().toUpperCase()) != 'NO PROPORCIONA'){
						if(! $.trim($("#txtCorreoPersonalPersonaNuevo").val()).match(xrEmail)){

							if(tipoUsuario !=2){
							alertMailNoValido();
							$("#btnGuardarPersonaNuevo").prop("disabled", false);
							$("#txtCorreoPersonalPersonaNuevo").focus();
							return;
							}
						}
					}
					
					if($("#txtCorreoPersonalPersonaNuevo2").val() != ""){
						if($.trim($("#txtCorreoPersonalPersonaNuevo2").val().toUpperCase()) != 'NO TIENE' &&
							$.trim($("#txtCorreoPersonalPersonaNuevo2").val().toUpperCase()) != 'NO PROPORCIONA'){
							if(! $.trim($("#txtCorreoPersonalPersonaNuevo2").val()).match(xrEmail)){
								if(tipoUsuario !=2){
								alertMailNoValido();
								$("#btnGuardarPersonaNuevo").prop("disabled", false);
								$("#txtCorreoPersonalPersonaNuevo2").focus();
								return;
								}
							}
						}
					}

					if (!registroCorrecto) {
						$("#btnGuardarPersonaNuevo").prop("disabled", false);
						return;
					}

					cicloActivo = $('#hdnCicloActivo').val();
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
					
					field_12 = $('#sltField_12').val();//categoria audit
					field_13 = $('#sltField_13').val();//categoria as
					field_14 = $('#sltField_14').val();//flonorm
					field_15 = $('#sltField_15').val();//vessel
					field_16 = $('#sltField_16').val();//ateka
					field_17 = $('#sltField_17').val();//esoxx
					field_18 = $('#sltField_18').val();//zifros

					tipoUsuario = $('#hdnTipoUsuario').val();
					ruta = $('#hdnRutaDatosPersonales').val();
					
					rutaAsignada = $('#sltRutaPersonaNueva').val();
					
					if(tipoUsuario != 4){
						if(rutaAsignada == '00000000-0000-0000-0000-000000000000'){
							alertFaltanDatosRuta();
							$('#sltRutaPersonaNueva').focus();
							$("#btnGuardarPersonaNuevo").prop("disabled", false);
							registroCorrecto = false;
							return true;
						}
					}

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
						cicloActivo:cicloActivo,
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
						rutaAsignada:rutaAsignada,
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
						celular: celular,
						field_12: field_12,
						field_13: field_13,
						field_14: field_14,
						field_15: field_15,
						field_16: field_16,
						field_17: field_17,
						field_18: field_18,
						banderaedit:banderaedit
					});
					//$('body').removeClass('no-scroll');
				} else {

					if(tipoUsuario==2){

						cicloActivo = $('#hdnCicloActivo').val();
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
					
					field_12 = $('#sltField_12').val();//categoria audit
					field_13 = $('#sltField_13').val();//categoria as
					field_14 = $('#sltField_14').val();//flonorm
					field_15 = $('#sltField_15').val();//vessel
					field_16 = $('#sltField_16').val();//ateka
					field_17 = $('#sltField_17').val();//esoxx
					field_18 = $('#sltField_18').val();//zifros

					tipoUsuario = $('#hdnTipoUsuario').val();
					ruta = $('#hdnRutaDatosPersonales').val();
					
					rutaAsignada = $('#sltRutaPersonaNueva').val();
					
					if(tipoUsuario != 4){
						if(rutaAsignada == '00000000-0000-0000-0000-000000000000'){
							alertFaltanDatosRuta();
							$('#sltRutaPersonaNueva').focus();
							$("#btnGuardarPersonaNuevo").prop("disabled", false);
							registroCorrecto = false;
							return true;
						}
					}

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
						cicloActivo:cicloActivo,
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
						rutaAsignada:rutaAsignada,
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
						celular: celular,
						field_12: field_12,
						field_13: field_13,
						field_14: field_14,
						field_15: field_15,
						field_16: field_16,
						field_17: field_17,
						field_18: field_18,
						banderaedit:banderaedit
					});

						
				
					}else{
						$("#btnGuardarPersonaNuevo").prop("disabled", false);
							alertFaltanDatos();

					}


					
				}
			});

			$('#btnCancelarPersonaNuevo').click(function () {
				var validator = $("#formAgregarPersona").validate();
				validator.resetForm();
				nombreInstOk = 0;

				$('input, select, textarea').each(function () {
					$(this).removeClass('invalid');
					$(this).removeClass('invalid-slt');
				});
			});
			/**FIN agregarPersona */
			

			$('#imgAgregarInstPersona').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				$('#divPersona').hide();
				$('#divInstitucion').show();
				$('#hdnPersonaNueva').val('si');
				$('#divRespuesta').load("ajax/cargarInstitucionNueva.php",{idUsuario:idUsuario});
			});
			
			$('#btnCambiarRutaPersonas').click(function(){
				$('#btnCambiarRutaPersonas').hide();
				$('#btnAprobacionesPers').hide();
				$('#btnExportarPersonas').hide();
				$('#btnReVisitadosPersonas').hide();
				$('#btnVisitadosPersonas').hide();
				$('#btnNoVisitadosPersonas').hide();
				$('#btnTodosPersonas').hide();
				$('.btnReVisitadosPersonas2').hide();
				$('.btnVisitadosPersonas2').hide();
				$('.btnNoVisitadosPersonas2').hide();
				$('.btnTodosPersonas2').hide();
				$('#imgAgregarPersona').hide();
				
				$('#lblSeleccionarTodosCambiarRuta').show();
				$('#chkSeleccionarTodosCambiarRuta').show();
				$('#btnAceptarCambiarRutaPersonas').show();
				$('#btnCancelarCambiarRutaPersonas').show();
				
				$("#hdnSelecciandoCambiarRuta").val('');
				$("#chkSeleccionarTodosCambiarRuta").prop('checked', false);
				
				$('#tbPersonas').hide();
				$('#tblCambiarRutaPersonas').show();

				$("#leftsidebarMedicos").addClass('showleftsidebarMedicos');
				$("#leftsidebarMedicos").removeClass('hideleftsidebarMedicos');
				$(".tab-sidebar-medicos-r").css("display", "none");
				$(".tab-sidebar-medicos-l").css("display", "block");
				
				if(idmedico == "med1"){
					$('#trmedR1').addClass('div-slt-lista');
					$('#trmedR2').removeClass('div-slt-lista');
					$('#trmedR3').removeClass('div-slt-lista');
					$('#trmedR4').removeClass('div-slt-lista');
					$('#trmedR5').removeClass('div-slt-lista');
				}
				if(idmedico == "med2"){
					$('#trmedR1').removeClass('div-slt-lista');
					$('#trmedR2').addClass('div-slt-lista');
					$('#trmedR3').removeClass('div-slt-lista');
					$('#trmedR4').removeClass('div-slt-lista');
					$('#trmedR5').removeClass('div-slt-lista');
				}
				if(idmedico == "med3"){
					$('#trmedR1').removeClass('div-slt-lista');
					$('#trmedR2').removeClass('div-slt-lista');
					$('#trmedR3').addClass('div-slt-lista');
					$('#trmedR4').removeClass('div-slt-lista');
					$('#trmedR5').removeClass('div-slt-lista');
				}
				if(idmedico == "med4"){
					$('#trmedR1').removeClass('div-slt-lista');
					$('#trmedR2').removeClass('div-slt-lista');
					$('#trmedR3').removeClass('div-slt-lista');
					$('#trmedR4').addClass('div-slt-lista');
					$('#trmedR5').removeClass('div-slt-lista');
				}
				if(idmedico == "med5"){
					$('#trmedR1').removeClass('div-slt-lista');
					$('#trmedR2').removeClass('div-slt-lista');
					$('#trmedR3').removeClass('div-slt-lista');
					$('#trmedR4').removeClass('div-slt-lista');
					$('#trmedR5').addClass('div-slt-lista');
				}
			});
			
			$('#btnAceptarCambiarRutaPersonas').click(function(){
				if($('#hdnSelecciandoCambiarRuta').val() == ""){

					alertSeleccionaMed();
					return;
				}
				idUsuario = $('#hdnIdUser').val();
				ids = $('#hdnIds').val();
				$('#divRutaNueva').show();
				$('#over').show(500);
				$('#fade').show(500);
				$('#divRespuesta').load("ajax/cargarRutasCambioPersonas.php", {idUsuario:idUsuario, ids:ids});
				/*$('#btnCambiarRutaPersonas').show();
				$('#btnAprobacionesPers').show();
				$('#btnExportarPersonas').show();
				$('#btnReVisitadosPersonas').show();
				$('#btnVisitadosPersonas').show();
				$('#btnNoVisitadosPersonas').show();
				$('#btnTodosPersonas').show();
				$('#imgAgregarPersona').show();
				
				$('#btnAceptarCambiarRutaPersonas').hide();
				$('#btnCancelarCambiarRutaPersonas').hide();
				
				$('#tbPersonas').show();
				$('#tblCambiarRutaPersonas').hide();*/
			});
			
			$('#btnCancelarCambiarRutaPersonas').click(function(){
				$('#btnCambiarRutaPersonas').show();
				$('#btnAprobacionesPers').show();
				$('#btnExportarPersonas').show();
				$('#btnReVisitadosPersonas').show();
				$('#btnVisitadosPersonas').show();
				$('#btnNoVisitadosPersonas').show();
				$('#btnTodosPersonas').show();
				$('.btnReVisitadosPersonas2').show();
				$('.btnVisitadosPersonas2').show();
				$('.btnNoVisitadosPersonas2').show();
				$('.btnTodosPersonas2').show();
				$('#imgAgregarPersona').show();
				
				$('#lblSeleccionarTodosCambiarRuta').hide();
				$('#chkSeleccionarTodosCambiarRuta').hide();
				$('#btnAceptarCambiarRutaPersonas').hide();
				$('#btnCancelarCambiarRutaPersonas').hide();
				
				$('#tbPersonas').show();
				$('#tblCambiarRutaPersonas').hide();
				
				$("#hdnSelecciandoCambiarRuta").val('');
				$("#chkSeleccionarTodosCambiarRuta").prop('checked', false);
				
				for(var i=1;i<21;i++){
					$("#chkCambiarRutaPersona"+i).prop("checked", false);
				}

				if(idmedico == "medR1"){
					$('#trmed1').addClass('div-slt-lista');
					$('#trmed2').removeClass('div-slt-lista');
					$('#trmed3').removeClass('div-slt-lista');
					$('#trmed4').removeClass('div-slt-lista');
					$('#trmed5').removeClass('div-slt-lista');
				}
				if(idmedico == "medR2"){
					$('#trmed1').removeClass('div-slt-lista');
					$('#trmed2').addClass('div-slt-lista');
					$('#trmed3').removeClass('div-slt-lista');
					$('#trmed4').removeClass('div-slt-lista');
					$('#trmed5').removeClass('div-slt-lista');
				}
				if(idmedico == "medR3"){
					$('#trmed1').removeClass('div-slt-lista');
					$('#trmed2').removeClass('div-slt-lista');
					$('#trmed3').addClass('div-slt-lista');
					$('#trmed4').removeClass('div-slt-lista');
					$('#trmed5').removeClass('div-slt-lista');
				}
				if(idmedico == "medR4"){
					$('#trmed1').removeClass('div-slt-lista');
					$('#trmed2').removeClass('div-slt-lista');
					$('#trmed3').removeClass('div-slt-lista');
					$('#trmed4').addClass('div-slt-lista');
					$('#trmed5').removeClass('div-slt-lista');
				}
				if(idmedico == "medR5"){
					$('#trmed1').removeClass('div-slt-lista');
					$('#trmed2').removeClass('div-slt-lista');
					$('#trmed3').removeClass('div-slt-lista');
					$('#trmed4').removeClass('div-slt-lista');
					$('#trmed5').addClass('div-slt-lista');
				}
			});
			
			$("#chkSeleccionarTodosCambiarRuta").click(function(){
				for(var i=1;i<21;i++){
					if($('#chkSeleccionarTodosCambiarRuta').prop('checked')){
						$("#chkCambiarRutaPersona"+i).prop("checked", true);
						$("#hdnSelecciandoCambiarRuta").val('todo');
					}else{
						$("#chkCambiarRutaPersona"+i).prop("checked", false);
						$("#hdnSelecciandoCambiarRuta").val('');
					}
				}
				
			});
			
			$("#btnActualizarPers").click(function(){
				var pagina = $('#hdnPaginaPersonas').val();
				var ids = $('#hdnIds').val();
				var hoy = $('#hdnHoy').val();

				alertActualizando();
				
				nuevaPagina(pagina,hoy,ids,'' );
			});
			
			$("#btnReactivarPersonaNuevo").click(function(){
				idPersona = $("#hdnIdPersona").val();
				alertConfirmarReactivar(idPersona);
			});

			$("#btnReactivarPersonaNuevo2").click(function(){
				alertConfirmarReactivar(idPersonaReactivar);
			});
				
			//chkCambiarRutaPersona1
			
			/*$("#btnGuardarCambioRuta").click(function(){
				rutaNueva = $('input:radio[name=radioRutas]:checked').val();
				ids = $("#hdnIds").val();
				if(! rutaNueva){
					alert('Debe seleccionar la nueva ruta');
					return;
				}
				
				if($("#hdnSelecciandoCambiarRuta").val() == 'todo'){
					idsPersonas = '';
					tipoPersona = $('#sltTipoPersonaFiltro').val();
					nombre = $('#txtNombreFiltro').val();
					apellidos = $('#txtApellidosFiltro').val();
					especialidad = $('#sltEspecialidadFiltro').val();
					categoria = $('#sltCategoriaFiltro').val();
					inst = $('#txtInstitucionFiltro').val();
					dir = $('#txtDireccionFiltro').val();
					del = $('#txtDelegacionFiltro').val();
					estado = $('#txtEstadoFiltro').val();
					repre = $('#hdnIdsFiltroUsuarios').val();
				}else{
					idsPersonas = $("#hdnSelecciandoCambiarRuta").val();
				}
				
				$('#divRespuesta').load("ajax/cambiarRuta.php",{ids:ids,rutaNueva:rutaNueva,idsPersonas:idsPersonas,tipoPersona:tipoPersona,nombre:nombre,apellidos:apellidos,especialidad:especialidad,categoria:categoria,inst:inst,dir:dir,del:del,estado:estado,repre:repre});
				
			});*/
			
			$('#imgFiltrarRx').click(function(){
				//alert('hola');
				var idPersona = $("#hdnIdPersona").val();
				var producto = $('#lstProductoDatosPersonales').val();
				var periodos = $('#hdnIdsFiltroPeriodos').val();
				var mercados = $('#hdnIdsFiltroMercados').val();
				
				//alert(idPersona);
				
				$('#divRespuesta').load("ajax/cargarTablaRxPersonas.php",{idPersona:idPersona,producto:producto,periodos:periodos,mercados:mercados});
			});
			
			/* fin de persona */
			
			/*fin de personas*/
			
			
			
			/*instituciones */
		
			$('#imgAgregarInstitucion').click(function(){
				var validator = $("#formAgregarInst").validate();
				validator.resetForm();

				var idUsuario = $('#hdnIdUser').val();
				$('#hdnIdInst').val('');
				$('#hdnPersonaNueva').val('no');
				$('#divInstitucion').show('slow');
				$('#divCapa3').show('slow');
				$('#sltFormatoInstNueva').empty();
				var tabActivo = tabActivoInst;
				$('#divRespuesta').load("ajax/cargarInstitucionNueva.php",{idUsuario:idUsuario,tabActivo:tabActivo});
			});
			
			$('#imgCancelarInstituciones').click(function(){
				$('#divFiltrosInstituciones').hide();
			});
			
			$('#btnAgregarDepartamento').click(function(){
				var idInst = $('#hdnIdInst').val();
				$('#divDepartamento').show();
				$('#divCapa3').show('slow');
				$('#divRespuesta').load("ajax/cargarDepartamento.php",{idInst:idInst});
			});
			
			$('#imgAgregarPersonaInstituciones').click(function(){
				var ancho = 900;
				var alto = 500;
				var x = (screen.width/2)-(ancho/2);
				var y = (screen.height/2)-(alto/2);
				var ventana = window.open("persona.php", "vtnPersona", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
			});
			
			
			$('#btnAgregarPersonaDatosInstituciones').click(function(){
				$('#imgAgregarPersona').click();
			});
			
			$('#btnAgregarPersonaDepartamentoDatosInstituciones').click(function(){
				/*if($.trim($('#btnAgregarPersonaDepartamentoDatosInstituciones').html()) == "Agregar"){
					if($('#tblDepartamentos tbody tr').length == 0){
						alert('Debe tener al menos un departamento');
						return true;
					}else{
						if($('#hdnIDepto').val() == ''){
							alert('Debe seleccionar un departamento');
							return true;
						}
					}
					$('#divRespuesta').load("ajax/cargarPersonaDeptoNueva.php");
				}else{
					if($('#hdnIdpersonaDeptoEdit').val == ''){//altas
						var renglon = $('#tblPersonasDepartamentoInstituciones tbody tr').length;
						$('#trDeptoPersonaNueva' + renglon).remove();
						$('#btnAgregarPersonaDepartamentoDatosInstituciones').html('Agregar');
					}else{
						idDepto = $('#hdnIDepto').val();
						$('#divRespuesta').load("ajax/cargarPersonasDepto.php",{idDepto:idDepto});
					}
				}*/
				if($('#tblDepartamentos tbody tr').length == 0){
					//alert('Debe tener al menos un departamento');
					alertSelUnDepartamento();
					return true;
				}else{
					if($('#hdnIDepto').val() == ''){
						alertSelDepartamento();
						return true;
					}
				}
				$('#divRespuesta').load("ajax/cargarPersonaDeptoNueva.php");
			});
			
			$('#imgAgregarPlanInstituciones').click(function(){
				idInst = $('#hdnIdInst').val();
				idUsuario = $('#hdnIdUser').val();
				ruta = $('#sltRutaBuscaInst').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				var idRepre = $('#hdnIdRutaDatosInst').val();
				$('#divPlanesInst').show();
				$('#divCapa3').show('slow');
				$("#btnEliminarPlanInst").hide();
				tipoUsuario = $('#hdnTipoUsuario').val();
				$('#divRespuesta').load("ajax/cargarPlanInst.php",{idInst:idInst,idUsuario:idUsuario,tipoUsuario:tipoUsuario,ruta:ruta,repre:idRepre});
			});
			
			$('#btnAgregarVisitaInstituciones').click(function(){
				var idInst = $('#hdnIdInst').val(); //idinstseleccionada
				var idUsuario = $('#hdnIdUser').val();//idUsuarioactivo (url)
				var idTipoInst = $("#hdnIdTipoInst").val();//tipo de institucion: todos-3, hosp-1, farm-2 
				var lat = '0.0';
				var lon = '0.0';
				tipoUsuario = $('#hdnTipoUsuario').val(); //6000-tipo5
				var idRepre = $('#hdnIdRutaDatosInst').val();//id repersenante 

				$('#divRespuesta').load("ajax/cargarVisitaInst.php", {idInst:idInst,lat:lat,lon:lon,idUsuario:idUsuario, idTipoInst:idTipoInst,tipoUsuario:tipoUsuario,idRepre:idRepre});
				$('#divVisitasInst').show();
				$('#divCapa3').show('slow');
				$("#btnEliminarVisitaInst").hide();
			});
			
			$('#btnTodosInst').click(function(){
				$('#txtBuscarHosp').val('');
				$('#txtBuscarFar').val('');

				$('#btnReVisitadosInst').removeClass("btn-account-sel");
				$('#btnVisitadosInst').removeClass("btn-account-sel");
				$('#btnNoVisitadosInst').removeClass("btn-account-sel");
				$('#btnTodosInst').addClass("btn-account-sel");

				$("#btnReVisitadosInst").removeAttr("disabled");
				$("#btnVisitadosInst").removeAttr("disabled");
				$("#btnNoVisitadosInst").removeAttr("disabled");
				$("#btnTodosInst").attr("disabled", true);
			});
			$('#btnVisitadosInst').click(function(){
				$('#txtBuscarHosp').val('');
				$('#txtBuscarFar').val('');

				$('#btnReVisitadosInst').removeClass("btn-account-sel");
				$('#btnNoVisitadosInst').removeClass("btn-account-sel");
				$('#btnTodosInst').removeClass("btn-account-sel");
				$('#btnVisitadosInst').addClass("btn-account-sel");

				$("#btnReVisitadosInst").removeAttr("disabled");
				$("#btnTodosInst").removeAttr("disabled");
				$("#btnNoVisitadosInst").removeAttr("disabled");
				$("#btnVisitadosInst").attr("disabled", true);
			});
			$('#btnReVisitadosInst').click(function(){
				$('#txtBuscarHosp').val('');
				$('#txtBuscarFar').val('');

				$('#btnNoVisitadosInst').removeClass("btn-account-sel");
				$('#btnTodosInst').removeClass("btn-account-sel");
				$('#btnVisitadosInst').removeClass("btn-account-sel");
				$('#btnReVisitadosInst').addClass("btn-account-sel");

				$("#btnTodosInst").removeAttr("disabled");
				$("#btnVisitadosInst").removeAttr("disabled");
				$("#btnNoVisitadosInst").removeAttr("disabled");
				$("#btnReVisitadosInst").attr("disabled", true);
			});
			$('#btnNoVisitadosInst').click(function(){
				$('#txtBuscarHosp').val('');
				$('#txtBuscarFar').val('');

				$('#btnReVisitadosInst').removeClass("btn-account-sel");
				$('#btnVisitadosInst').removeClass("btn-account-sel");
				$('#btnTodosInst').removeClass("btn-account-sel");
				$('#btnNoVisitadosInst').addClass("btn-account-sel");

				$("#btnReVisitadosInst").removeAttr("disabled");
				$("#btnVisitadosInst").removeAttr("disabled");
				$("#btnTodosInst").removeAttr("disabled");
				$("#btnNoVisitadosInst").attr("disabled", true);
			});

			$('#imgFiltrar2Inst').click(function(){
				$('#imgFiltrar2Inst').addClass("btn-red-slt");
				$('#trFiltrosInst').show('slow');
			});

			$('#imgFiltrarInst').click(function(){
				$('#trFiltrosInst').hide('slow');
				$('#imgFiltrar2Inst').removeClass("btn-red-slt");
				$('#btnReVisitadosInst').removeClass("btn-account-sel");
				$('#btnVisitadosInst').removeClass("btn-account-sel");
				$('#btnNoVisitadosInst').removeClass("btn-account-sel");
				
				$('#btnTodosInst').addClass("btn-account-sel");
				$("#btnReVisitadosInst").removeAttr("disabled");
				$("#btnVisitadosInst").removeAttr("disabled");
				$("#btnNoVisitadosInst").removeAttr("disabled");
				$("#btnTodosInst").attr("disabled", true);
			});

			/**Menu filtros sm */
			$('.btnTodosInst2').click(function(){
				$('.btnReVisitadosInst2').removeClass("btn-account-sel");
				$('.btnVisitadosInst2').removeClass("btn-account-sel");
				$('.btnNoVisitadosInst2').removeClass("btn-account-sel");
				$('.btnTodosInst2').addClass("btn-account-sel");

				$(".btnReVisitadosInst2").removeAttr("disabled");
				$(".btnVisitadosInst2").removeAttr("disabled");
				$(".btnNoVisitadosInst2").removeAttr("disabled");
				$(".btnTodosInst2").attr("disabled", true);
			});
			$('.btnVisitadosInst2').click(function(){
				$('.btnReVisitadosInst2').removeClass("btn-account-sel");
				$('.btnNoVisitadosInst2').removeClass("btn-account-sel");
				$('.btnTodosInst2').removeClass("btn-account-sel");
				$('.btnVisitadosInst2').addClass("btn-account-sel");

				$(".btnReVisitadosInst2").removeAttr("disabled");
				$(".btnTodosInst2").removeAttr("disabled");
				$(".btnNoVisitadosInst2").removeAttr("disabled");
				$(".btnVisitadosInst2").attr("disabled", true);
			});
			$('.btnReVisitadosInst2').click(function(){
				$('.btnNoVisitadosInst2').removeClass("btn-account-sel");
				$('.btnTodosInst2').removeClass("btn-account-sel");
				$('.btnVisitadosInst2').removeClass("btn-account-sel");
				$('.btnReVisitadosInst2').addClass("btn-account-sel");

				$(".btnTodosInst2").removeAttr("disabled");
				$(".btnVisitadosInst2").removeAttr("disabled");
				$(".btnNoVisitadosInst2").removeAttr("disabled");
				$(".btnReVisitadosInst2").attr("disabled", true);
			});
			$('.btnNoVisitadosInst2').click(function(){
				$('.btnReVisitadosInst2').removeClass("btn-account-sel");
				$('.btnVisitadosInst2').removeClass("btn-account-sel");
				$('.btnTodosInst2').removeClass("btn-account-sel");
				$('.btnNoVisitadosInst2').addClass("btn-account-sel");

				$(".btnReVisitadosInst2").removeAttr("disabled");
				$(".btnVisitadosInst2").removeAttr("disabled");
				$(".btnTodosInst2").removeAttr("disabled");
				$(".btnNoVisitadosInst2").attr("disabled", true);
			});

			$('#imgFiltrarInst').click(function(){
				$('#trFiltrosInst').hide('slow');
				$('#filtrarInst2').removeClass("btn-account-sel");
				$('.btnReVisitadosInst2').removeClass("btn-account-sel");
				$('.btnVisitadosInst2').removeClass("btn-account-sel");
				$('.btnNoVisitadosInst2').removeClass("btn-account-sel");
				
				$('.btnTodosInst2').addClass("btn-account-sel");
				$(".btnReVisitadosInst2").removeAttr("disabled");
				$(".btnVisitadosInst2").removeAttr("disabled");
				$(".btnNoVisitadosInst2").removeAttr("disabled");
				$(".btnTodosInst2").attr("disabled", true);
			});

			$('#filtrarInst2').click(function(){
				$('#filtrarInst2').addClass("btn-account-sel");
				$('#trFiltrosInst').show('slow');
			});


			/**FIN menu filtros sm */
			
			$('#btnLimpiarFiltrosInst').click(function(){
				$('#sltTipoInstFiltro').val('');
				$('#txtNombreInstFiltro').val('');
				$('#txtCalleInstFiltro').val('');
				$('#txtColoniaInstFiltro').val('');
				$('#txtCiudadInstFiltro').val('');
				$('#txtEstadoInstFiltro').val('');
				$('#txtCPInstFiltro').val('');
				$('#sltMultiSelectInst').text('Seleccione');
				$('#hdnIdsFiltroUsuarios').val('');
				$('#hdnNombresFiltroUsuarios').val('');
				$('#tblUsuariosSeleccionados').empty();
				$('#sltEstatusFiltrosInst').val('');
				$('#sltMotivoBajaFiltrosInst').val('');
			});

			$("#txtBuscarHosp").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					var nombreList = $("#txtBuscarHosp").val();
					var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					var hoy = $('#hdnHoy').val();
					var tipoUsuario = $('#hdnTipoUsuario').val();
					var visitados = '';
					var pagina = 1;
					var tipo = $('#sltTipoInstFiltro').val();
					var tabActivo= tabActivoInst;

					var sizeScreen = $(window).height(); //959
					var sizeNavbar = $('.navbar').outerHeight(true); //90
					var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
					var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
					var sizeListInstFoot = 0;
					var sizeListInstFoot2 = 0;
					if(sizeHeaderInicio == 15){
						sizeHeaderInicio = sizeHeaderInst;
					}
					if(sizeListInstFoot == 104){
						sizeListInstFoot2 = 72;
					}
					if(sizeListInstFoot2 == 104){
						sizeListInstFoot = 72;
					}
					if(sizeListInstFoot == 0){
						sizeListInstFoot = 72;
					}
					if(sizeListInstFoot2 == 0){
						sizeListInstFoot2 = 72;
					}

					cargandoInst();
					$("#divRespuesta").load("ajax/cargarTablaInst.php", {
						pagina: pagina,
						hoy: hoy,
						ids: ids,
						nombreList: nombreList,
						visitados: visitados,
						tipoUsuario: tipoUsuario,
						tipo:tipo,
						tabActivo:tabActivo
					}, function () {
						$(".cardListInst").waitMe("hide");

						sizeListInstFoot = $('.listaInstTfoot').outerHeight(); //15-45
						sizeListInstFoot2 = $('.listaInstTfoot2').outerHeight(); //15-45
						var addHeight = 160;
						if (sizeListInstFoot == 104) {
							sizeListInstFoot2 = 72;
						}
						if (sizeListInstFoot2 == 104) {
							sizeListInstFoot = 72;
						}
						if (sizeListInstFoot == 0) {
							sizeListInstFoot = 72;
						}
						if (sizeListInstFoot2 == 0) {
							sizeListInstFoot2 = 72;
						}
						var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot - addHeight;
						var heightInstList2 = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot2 - addHeight;
						var heightCardInstList = heightInstList + 'px';
						var heightCardInstList2 = heightInstList2 + 'px';
						$('.listaInstituciones').css('height', heightCardInstList);
						$('.listaInstituciones2').css('height', heightCardInstList2);
					});
				}
			});
			
			$("#btnBuscarHospList").click(function() {
				var nombreList = $("#txtBuscarHosp").val();
				var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				var hoy = $('#hdnHoy').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var visitados = '';
				var pagina = 1;
				var tipo = $('#sltTipoInstFiltro').val();
				var tabActivo= tabActivoInst;

				var sizeScreen = $(window).height(); //959
				var sizeNavbar = $('.navbar').outerHeight(true); //90
				var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
				var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
				var sizeListInstFoot = 0;
				var sizeListInstFoot2 = 0;
				if(sizeHeaderInicio == 15){
					sizeHeaderInicio = sizeHeaderInst;
				}
				if(sizeListInstFoot == 104){
					sizeListInstFoot2 = 72;
				}
				if(sizeListInstFoot2 == 104){
					sizeListInstFoot = 72;
				}
				if(sizeListInstFoot == 0){
					sizeListInstFoot = 72;
				}
				if(sizeListInstFoot2 == 0){
					sizeListInstFoot2 = 72;
				}

				cargandoInst();
				$("#divRespuesta").load("ajax/cargarTablaInst.php", {
					pagina: pagina,
					hoy: hoy,
					ids: ids,
					nombreList: nombreList,
					visitados: visitados,
					tipoUsuario: tipoUsuario,
					tipo:tipo,
					tabActivo:tabActivo
				}, function () {
					$(".cardListInst").waitMe("hide");

					sizeListInstFoot = $('.listaInstTfoot').outerHeight(); //15-45
					sizeListInstFoot2 = $('.listaInstTfoot2').outerHeight(); //15-45
					var addHeight = 160;
					if (sizeListInstFoot == 104) {
						sizeListInstFoot2 = 72;
					}
					if (sizeListInstFoot2 == 104) {
						sizeListInstFoot = 72;
					}
					if (sizeListInstFoot == 0) {
						sizeListInstFoot = 72;
					}
					if (sizeListInstFoot2 == 0) {
						sizeListInstFoot2 = 72;
					}
					var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot - addHeight;
					var heightInstList2 = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot2 - addHeight;
					var heightCardInstList = heightInstList + 'px';
					var heightCardInstList2 = heightInstList2 + 'px';
					$('.listaInstituciones').css('height', heightCardInstList);
					$('.listaInstituciones2').css('height', heightCardInstList2);
				});
			});

			$("#txtBuscarFar").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					var nombreList = $("#txtBuscarFar").val();
					var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
					var hoy = $('#hdnHoy').val();
					var tipoUsuario = $('#hdnTipoUsuario').val();
					var visitados = '';
					var pagina = 1;
					var tipo = $('#sltTipoInstFiltro').val();
					var tabActivo= tabActivoInst;

					var sizeScreen = $(window).height(); //959
					var sizeNavbar = $('.navbar').outerHeight(true); //90
					var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
					var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
					var sizeListInstFoot = 0;
					var sizeListInstFoot2 = 0;
					if(sizeHeaderInicio == 15){
						sizeHeaderInicio = sizeHeaderInst;
					}
					if(sizeListInstFoot == 104){
						sizeListInstFoot2 = 72;
					}
					if(sizeListInstFoot2 == 104){
						sizeListInstFoot = 72;
					}
					if(sizeListInstFoot == 0){
						sizeListInstFoot = 72;
					}
					if(sizeListInstFoot2 == 0){
						sizeListInstFoot2 = 72;
					}

					cargandoInst();
					$("#divRespuesta").load("ajax/cargarTablaInst.php", {
						pagina: pagina,
						hoy: hoy,
						ids: ids,
						nombreList: nombreList,
						visitados: visitados,
						tipoUsuario: tipoUsuario,
						tipo:tipo,
						tabActivo:tabActivo
					}, function () {
						$(".cardListInst").waitMe("hide");

						sizeListInstFoot = $('.listaInstTfoot').outerHeight(); //15-45
						sizeListInstFoot2 = $('.listaInstTfoot2').outerHeight(); //15-45
						var addHeight = 160;
						if (sizeListInstFoot == 104) {
							sizeListInstFoot2 = 72;
						}
						if (sizeListInstFoot2 == 104) {
							sizeListInstFoot = 72;
						}
						if (sizeListInstFoot == 0) {
							sizeListInstFoot = 72;
						}
						if (sizeListInstFoot2 == 0) {
							sizeListInstFoot2 = 72;
						}
						var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot - addHeight;
						var heightInstList2 = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot2 - addHeight;
						var heightCardInstList = heightInstList + 'px';
						var heightCardInstList2 = heightInstList2 + 'px';
						$('.listaInstituciones').css('height', heightCardInstList);
						$('.listaInstituciones2').css('height', heightCardInstList2);
					});
				}
			});
			
			$("#btnBuscarFarList").click(function() {
				var nombreList = $("#txtBuscarFar").val();
				var ids = $('#hdnIds').val()+"','"+$('#hdnIdUser').val();
				var hoy = $('#hdnHoy').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var visitados = '';
				var pagina = 1;
				var tipo = $('#sltTipoInstFiltro').val();
				var tabActivo= tabActivoInst;

				var sizeScreen = $(window).height(); //959
				var sizeNavbar = $('.navbar').outerHeight(true); //90
				var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
				var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
				var sizeListInstFoot = 0;
				var sizeListInstFoot2 = 0;
				if(sizeHeaderInicio == 15){
					sizeHeaderInicio = sizeHeaderInst;
				}
				if(sizeListInstFoot == 104){
					sizeListInstFoot2 = 72;
				}
				if(sizeListInstFoot2 == 104){
					sizeListInstFoot = 72;
				}
				if(sizeListInstFoot == 0){
					sizeListInstFoot = 72;
				}
				if(sizeListInstFoot2 == 0){
					sizeListInstFoot2 = 72;
				}

				cargandoInst();
				$("#divRespuesta").load("ajax/cargarTablaInst.php", {
					pagina: pagina,
					hoy: hoy,
					ids: ids,
					nombreList: nombreList,
					visitados: visitados,
					tipoUsuario: tipoUsuario,
					tipo:tipo,
					tabActivo:tabActivo
				}, function () {
					$(".cardListInst").waitMe("hide");

					sizeListInstFoot = $('.listaInstTfoot').outerHeight(); //15-45
					sizeListInstFoot2 = $('.listaInstTfoot2').outerHeight(); //15-45
					var addHeight = 160;
					if (sizeListInstFoot == 104) {
						sizeListInstFoot2 = 72;
					}
					if (sizeListInstFoot2 == 104) {
						sizeListInstFoot = 72;
					}
					if (sizeListInstFoot == 0) {
						sizeListInstFoot = 72;
					}
					if (sizeListInstFoot2 == 0) {
						sizeListInstFoot2 = 72;
					}
					var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot - addHeight;
					var heightInstList2 = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot2 - addHeight;
					var heightCardInstList = heightInstList + 'px';
					var heightCardInstList2 = heightInstList2 + 'px';
					$('.listaInstituciones').css('height', heightCardInstList);
					$('.listaInstituciones2').css('height', heightCardInstList2);
				});
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
				var tipoUsuario = $("#hdnTipoUsuario").val();
				var repre = $("#hdnIdRutaDatosInst").val();

				cargandoTablaVisitaInst();
				$('#divRespuesta').load("ajax/cargarVisitasInst.php",{idInst:idInst,year:year,idUsuario:idUsuario, tipoUsuario:tipoUsuario, repre:repre}, function(){
					$('#tblVisitasInst').waitMe('hide');
				});
			});
			
			$('#btnPendienteAprobacionesInst').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				
				$('#btnAceptadoAprobacionesInst').removeClass("btn-aprob-sel");
				$('#btnRechazadoAprobacionesInst').removeClass("btn-aprob-sel");
				$('#btnPendienteAprobacionesInst').addClass("btn-aprob-sel");

				$("#btnAceptadoAprobacionesInst").removeAttr("disabled");
				$("#btnRechazadoAprobacionesInst").removeAttr("disabled");
				$("#btnPendienteAprobacionesInst").attr("disabled", true);

				$('#divRespuesta').load("ajax/cargarAprobacionesInst.php",{idUsuario:idUsuario,estatus:1});
			});
			
			$('#btnAceptadoAprobacionesInst').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				
				$('#btnAceptadoAprobacionesInst').addClass("btn-aprob-sel");
				$('#btnRechazadoAprobacionesInst').removeClass("btn-aprob-sel");
				$('#btnPendienteAprobacionesInst').removeClass("btn-aprob-sel");

				$("#btnAceptadoAprobacionesInst").attr("disabled");
				$("#btnRechazadoAprobacionesInst").removeAttr("disabled");
				$("#btnPendienteAprobacionesInst").removeAttr("disabled", true);

				$('#divRespuesta').load("ajax/cargarAprobacionesInst.php",{idUsuario:idUsuario,estatus:2});
			});
			
			$('#btnRechazadoAprobacionesInst').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				
				$('#btnAceptadoAprobacionesInst').removeClass("btn-aprob-sel");
				$('#btnRechazadoAprobacionesInst').addClass("btn-aprob-sel");
				$('#btnPendienteAprobacionesInst').removeClass("btn-aprob-sel");

				$("#btnAceptadoAprobacionesInst").removeAttr("disabled");
				$("#btnRechazadoAprobacionesInst").attr("disabled");
				$("#btnPendienteAprobacionesInst").removeAttr("disabled", true);

				$('#divRespuesta').load("ajax/cargarAprobacionesInst.php",{idUsuario:idUsuario,estatus:3});
			});
			
			$("#btnGuardarPlanInst").click(function(){
				//$("#btnGuardarPlanInst").prop("disabled", true);
				idPlan = $("#hdnIdPlanInst").val();
				fecha_plan = $("#txtFechaPlanInst").val();
				hora_plan = $("#lstHoraPlanInst").val() + ':' + $("#lstMinutosPlanInst").val();
				codigo_plan = $("#lstCodigoPlanInst").val();
				objetivo_plan = $("#objetivoPlanInst").val();
				idInst = $("#hdnIdInst").val();
				idUsuario = $("#sltReprePlanInst").val();
				pantalla = $("#hdnPantallaPlanInst").val();
				idRepre = $("#hdnIdRutaDatosInst").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				idUser = $("#hdnIdUser").val();
				//alert(tipoUsuario);
				$("#divRespuesta").load("ajax/guardarPlanInst.php",{idPlan:idPlan,fechaPlan:fecha_plan,horaPlan:hora_plan,codigoPlan:codigo_plan,objetivoPlan:objetivo_plan,idInst:idInst,idUsuario:idUsuario,pantalla:pantalla,idRepre:idRepre,tipoUsuario:tipoUsuario,idUser:idUser});
			});
			
			$("#btnCancelarPlanInst").click(function(){
				$('#divPlanesInst').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnEliminarPlanInst').click(function(){
				idPlan = $("#hdnIdPlanInst").val();
				alertEliminarPlanInst(idPlan);
			});
			
			$("#btnReportarInst").click(function(){
				if($('#hdnIdVisitaPlanInst').val() != '' && $('#hdnIdVisitaPlanInst').val() != '00000000-0000-0000-0000-000000000000'){
					alertReportPlan();
					return true;
				}
				var idPlan = $("#hdnIdPlanInst").val();
				var idInst = $('#hdnIdInst').val();
				var idUsuario = $('#hdnIdUser').val();
				var idTipoInst = $("#hdnIdTipoInst").val();
				var tipoUsuario = $("#hdnTipoUsuario").val();
				var pantalla = $("#hdnPantallaPlan").val();
				var lat = '0.0';
				var lon = '0.0';
				$('#divRespuesta').load("ajax/cargarVisitaInst.php", {idInst:idInst,lat:lat,lon:lon,idUsuario:idUsuario, idTipoInst:idTipoInst, idPlan:idPlan,tipoUsuario:tipoUsuario,pantalla:pantalla});
				$('#divVisitasInst').show();
				$('#divPlanesInst').hide();
				$('#divCapa3').show('slow');
				$('#btnLimpiarFirmaInst').click();
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
				idInst = $('#hdnIdInst').val();
				idUsuario = $('#hdnIdUser').val();
				$('#divVisitasInst').hide();
				$('#divPlanesInst').show();

				cargandoVisita();
				$('#divRespuesta').load("ajax/cargarPlanInst.php",{idInst:idInst,idUsuario:idUsuario,regreso:'visita'}, function(){
					$("#cargandoInfVisitaMed").waitMe('hide');
				});
			});
			
			$('#btnGuardarVisitasInst').click(function () {
				$("#btnGuardarVisitasInst").prop("disabled", true);
				var fecha = $('#txtFechaVisitasInst').val();
				var hora = $('#lstHoraVisistasInst').val() + ':' + $('#lstMinutosVisitasInst').val();
				var codigoVisita = $('#lstCodigoVisitaInst').val();
				var comentariosVisita = $('#txtComentariosVisitaInst').val();
				var infoSiguienteVisita = $('#txtInfoSiguienteVisitaInst').val();
				var productosSeleccionados = '';
				var porcentajesProductosSeleccionados = '';
				var productosPromocionados = '';
				var cantidadProductosPromocionados = '';
				var lat = '';
				var lon = '';
				var idTipoInst = $('#hdnIdTipoInst').val();

				//alert(hora);
				if (codigoVisita == 0) {
					alertCodVisita();
					$('#lstCodigoVisitaInst').focus();
					$("#btnGuardarVisitasInst").prop("disabled", false);
					return;
				}
				
				if (comentariosVisita == '') {
					alertComenVisita();
					$("#txtComentariosVisitaInst").focus();
					$("#btnGuardarVisitasInst").prop("disabled", false);
					return;
				}
				
				if (infoSiguienteVisita == '') {
					alertInfoSigVisita();
					$("#infoSiguienteVisita").focus();
					$("#btnGuardarVisitasInst").prop("disabled", false);
					return;
				}
				
				visitaAcompa = '';
				for (i = 1; i <= $("#hdnTotalChecksInst").val(); i++) {
					if ($('#acompaInst' + i).prop('checked')) {
						visitaAcompa += $('#acompaInst' + i).val() + ";";
					}
				}
				
				if(visitaAcompa == ''){
					alertVisAcompa();
					$("#btnGuardarVisitasInst").prop("disabled", false);
					return;
				}
				
				for (var i = 1; i < 11; i++) {
					if ($("#lstProductoInst" + i).size() > 0) {
						if ($("#lstProductoInst" + i).val() != '00000000-0000-0000-0000-000000000000') {
							productosSeleccionados += $("#lstProductoInst" + i).val() + "|";
							porcentajesProductosSeleccionados += $("#txtProdPosInst" + i).val() + "|";
						} else {
							break;
						}
					}
				}


				if ($("#hdnTotalPromocionesVisitasInst").val() > 0) {
					for (var j = 1; j < $("#hdnTotalPromocionesVisitasInst").val(); j++) {
						if ($("#textInst" + j).val() > 0) {
							productosPromocionados += $("#hdnIdProductoInst" + j).val() + "|";
							cantidadProductosPromocionados += $("#textInst" + j).val() + "|";
							existenciaInst = $("#hdnExistenciaInst" + j).val();
							maximoInst = $("#hdnMaximoInst" + j).val();
							cantidadInst = $("#textInst" + j).val();
							if (parseInt(cantidadInst, 10) > parseInt(existenciaInst, 10)) {
								alertExistenciaPiezas(existenciaInst);
								//$("#btnGuardarVisitasInst").prop("disabled", false);
								return;
							}
							if (parseInt(cantidadInst, 10) > parseInt(maximoInst, 10)) {
								alertMasPiezas(maximoInst);
								//$("#btnGuardarVisitasInst").prop("disabled", false);
								return;
							}
						}
					}
				}

				/*revisa firma */

				if ($("#hdnTotalPromocionesVisitasInst").val() > 0) {
					var requiereFirmaProductoInst = false;
					for (var j = 1; j < $("#hdnTotalPromocionesVisitasInst").val(); j++) {
						if ($("#textInst" + j).val() > 0) {
							if ($("#hdnTipoProductoInst" + j).val() == 132) {
								requiereFirmaProductoInst = true;
							}
						}
					}
				}

				if (requiereFirmaProductoInst) {
					if ($("#hdnFirmaInst").val() == '') {
						alertFirma();
						//$("#btnGuardarVisitasInst").prop("disabled", false);
						return true;
					}
				}
			
				pantalla = $('#hdnPantallaVisitasInst').val();

				firma = $('#hdnFirmaInst').val();

				idVisita = $('#hdnIdVisitaInst').val();
				idPlan = $('#hdnIdPlan').val();
				idInst = $('#hdnIdInst').val();
				idUsuario = $('#sltRepreVisitasInst').val();
				idUser = $('#hdnIdUser').val();
				ruta = $('#hdnIdRutaDatosInst').val();
				tipoUsuario = $('#hdnTipoUsuario').val();

				var productosStock = '';
				var productoStockExistencia = '';
				var productoStockPrecio = '';
				var productoStockPedido = '';
				var productoStockSugerido = '';
				if ($('#hdnTotalProdcutosSeleccionados').val() > 0) {
					for (var i = 0; i < $('#hdnTotalProdcutosSeleccionados').val(); i++) {
						if ($('#hdnIdProdFormS' + i).length) {
							productosStock += $('#hdnIdProdFormS' + i).val() + ',';
							productoStockExistencia += $('#txtExistenciaS' + i).val() + ',';
							productoStockPrecio += $('#txtPrecioS' + i).val() + ',';
							productoStockPedido += $('#txtPedidoS' + i).val() + ',';
							productoStockSugerido += $('#txtSugeridoS' + i).val() + ',';
						}
					}
				}
				var idProductoCompetidores = $('#hdnIdProductosCompetidores').val();
				var existenciaCompetidores = $('#hdnExistenciaCompetidores').val();
				var precioCompetidores = $('#hdnPrecioCompetidores').val();


				$("#divRespuesta").load("ajax/guardaVisitaInst.php", {
					idVisita: idVisita,
					idPlan: idPlan,
					idInst: idInst,
					idUsuario: idUsuario,
					fecha: fecha,
					hora: hora,
					codigoVisita: codigoVisita,
					visitaAcompa: visitaAcompa,
					comentariosVisita: comentariosVisita,
					infoSiguienteVisita: infoSiguienteVisita,
					productosSeleccionados: productosSeleccionados,
					productosPromocionados: productosPromocionados,
					cantidadProductosPromocionados: cantidadProductosPromocionados,
					lat: lat,
					lon: lon,
					pantalla: pantalla,
					porcentajesProductosSeleccionados: porcentajesProductosSeleccionados,
					firma: firma,
					idTipoInst: idTipoInst,
					productosStock: productosStock,
					productoStockExistencia: productoStockExistencia,
					productoStockPrecio: productoStockPrecio,
					productoStockPedido: productoStockPedido,
					productoStockSugerido: productoStockSugerido,
					idProductoCompetidores: idProductoCompetidores,
					existenciaCompetidores: existenciaCompetidores,
					precioCompetidores: precioCompetidores,
					idUser: idUser,
					tipoUsuario: tipoUsuario,
					ruta: ruta
				}, function () {
					$("#" + idTrInst).addClass('div-slt-lista');
				});
			});
			
			$("#btnCancelarVisitasInst").click(function(){
				$("#divVisitasInst").hide();
				$("#divCapa3").hide();
			});
			
			$("#btnGuardarFirmaVisitasInst").click(function(){
				var canvas = document.getElementById('canvasFirmaVisitasInst2');
				var dataURL = canvas.toDataURL();
				$("#hdnFimaInst").val(dataURL);
				console.log(dataURL);
			});
			
			$('#sltTipoInstNueva').change(function(){
				tipoInst = $('#sltTipoInstNueva').val();
				$('#divRespuesta').load("ajax/cargarSubtipoInst.php",{tipoInst:tipoInst});
			});
			
			$('#sltSubtipoInstNueva').change(function(){
				subTipoInst = $('#sltSubtipoInstNueva').val();
				$('#divRespuesta').load("ajax/cargarFormatoInst.php",{subTipoInst:subTipoInst});
			});

			$("#btnCancelarInstNueva").click(function(){
				if($("#hdnPersonaNueva").val() == 'si'){
					$("#divInstitucion").hide();
					$("#divPersona").show();
					$("#hdnPersonaNueva").val('');
				}else{
					$("#divInstitucion").hide();
					$("#divCapa3").hide();
					$('input, select, textarea').each(function () {
						$(this).removeClass('invalid');
						$(this).removeClass('invalid-slt');
					});
					$('#' + idInstTabs).click();
					$('#' + idTrInst).addClass('div-slt-lista');
				}
			});
			
			$('#sltColoniaInstNueva').change(function(){
				cp = $('#txtCPInstNueva').val();
				colonia = $('#sltColoniaInstNueva').val();
				$("#divRespuesta").load("ajax/traeCity.php",{cp:cp,colonia:colonia});
			});
			
			$('#txtCPInstNueva').keyup(function(){
				tam = $('#txtCPInstNueva').val().length;
				if(tam == 5){
					cp = $('#txtCPInstNueva').val();
					$("#divRespuesta").load("ajax/traeColonias.php",{cp:cp});
				}
			});


			/**Agregar Institución */


			agregarInstitucion();

			$("#btnGuardarInstNueva").click(function () {
				
				var registroCorrecto = true;

				tipoUsuario = $("#hdnTipoUsuario").val();
				if($('#hdnCityInstNueva').val() == ''){
					$('#sltColoniaInstNueva').addClass('invalid-slt');
					$('#sltColoniaInstNuevaError').show();
					registroCorrecto = false;
				}

				if ($("#formAgregarInst").valid()) {
					registroCorrecto = true;

					rutaAsignada = $('#sltRutaInstNueva').val();
					
					if(tipoUsuario != 4){
						if(rutaAsignada == '00000000-0000-0000-0000-000000000000'){
							alertFaltanDatosRuta();
							$('#sltRutaInstNueva').focus();
							registroCorrecto = false;
						}
					}
					if (!registroCorrecto) {
						return;
					}

					tipoInst = $('#sltTipoInstNueva').val();
					subtipo = $('#sltSubtipoInstNueva').val();
					formato = $('#sltFormatoInstNueva').val();
					/*
					if(subtipo == '' || subtipo === null){
						subtipo = '00000000-0000-0000-0000-000000000000';
					}
					if(subtipo == '' || subtipo === null){
						subtipo = '00000000-0000-0000-0000-000000000000';
					}*/
					
					var flonorm = $('select[id=sltFlonorm]').val();
					var vessel = $('select[id=sltVessel]').val();
					var ateka = $('select[id=sltAteka]').val();
					var zirfos = $('select[id=sltZirfos]').val();
					var esoxx = $('select[id=sltEsoxx]').val();
					var mayoristas = $('select[id=sltMayoristas]').val();
					var catAlfa = $('#sltCatAlfa').val();

					

					/*alert($("#hdnIdInst").val());
					alert(flonorm);
					alert(vessel);
					alert(ateka);
					alert(zirfos);
					alert(esoxx);
					alert(mayoristas);*/
					estatus = $('#sltEstatusInstNueva').val();
					categoria = $('#sltCategoriaInstNueva').val();
					frecuencia = $('#sltFrecuenciaInstNueva').val();
					inst = $('#txNombreInstNueva').val();
					calle = $('#txtCalleInstNueva').val();
					numExt = $('#txtNumExtInstNueva').val();
					sucursal = $('#txtSucursalInstNueva').val();
					cp = $('#txtCPInstNueva').val();
					colonia = $('#sltColoniaInstNueva').val();
					city = $('#hdnCityInstNueva').val();
					tel1 = $('#txtTel1InstNueva').val();
					tel2 = $('#txtTel2InstNueva').val();
					celular = $('#txtCelularInstNueva').val();
					email = $('#txtEmailInstNueva').val();
					web = $('#txtWebInstNueva').val();
					posiciongps = $('#txtPosicionGPSInstNueva').val();
					comentarios = $('#txtComentariosInstNueva').val();
					representantesUnidos = $('#txtRepresentantesUnidos').val();
					PersonaNueva = $('#hdnPersonaNueva').val();
						/*
					nivelVentas = $('#sltNivelVentasInstNueva').val();
					nombreComercialFarmacia = $('#txtNomComercialFarmInstNueva').val();
					rotacion = $('#sltRotacionInstNueva').val();
					tipoPaciente = $('#sltTipoPacienteInstNueva').val();
					numEmpleados = $('#sltNumEmpInstNueva').val();
					numCtesxDia = $('#sltNumCtesXdiaInstNueva').val();
					numMedFarm = $('#txtNumMedFarmInstNueva').val();
					accesibilidad = $('#sltAccesibilidadInstNueva').val();
					numCtes = $('#txtNumCtesInstNueva').val();
					recibeVendedores = $('#sltRecibeVendedoresInstNueva').val();
					ventaGenericos = $('#sltVentaGenericosInstNueva').val();
					tamanoFarmacia = $('#sltTamFarmaciaInstNueva').val();
					mayorista1 = $('#sltMayorista1InstNueva').val();
					mayorista2 = $('#sltMayorista2InstNueva').val();
					numAnaqueles = $('#txtNumAnaquelesInstNueva').val();
					numVisitasXciclo = $('#txtNumVisitasXcicloInstNueva').val();
					turnos = $('#sltTurnosInstNueva').val();
					frecuenciaVisita = $('#txtFrecuenciaVisitaInstNueva').val();
					trabajaInstPublica = $('#sltTrabajaInstPublicaInstNueva').val();
					ubicacion = $('#sltUbicacionInstNueva').val();
					
					numCamas = $('#txtNumCamasInstNueva').val();
					numQuirofano = $('#txtNumQuirofanosInstNueva').val();
					numSalasExpulsion = $('#txtNumSalasExpulsionInstNueva').val();
					numCunas = $('#txtNumCunasInstNueva').val();
					numIncubadoras = $('#txtNumIncubadorasInstNueva').val();
					terapiaIntensiva = $('#chkTerapiaIntensiva').prop('checked') ? 1 : 0;
					unidadCuidadosIntensivos = $('#chkUnidadCuidadosIntensivos').prop('checked') ? 1 : 0;
					infectologia = $('#chkInfectologia').prop('checked') ? 1 : 0;
					laboratorio = $('#chkLaboratorio').prop('checked') ? 1 : 0;
					urgencias = $('#chkUrgencias').prop('checked') ? 1 : 0;
					rayosx = $('#chkRayosx').prop('checked') ? 1 : 0;
					farmacia = $('#chkFarmacia').prop('checked') ? 1 : 0;
					botiquin = $('#chkBotiquin').prop('checked') ? 1 : 0;
					endoscopia = $('#chkEndoscopia').prop('checked') ? 1 : 0;
					consultaExterna = $('#chkConsultaExterna').prop('checked') ? 1 : 0;
					cirugiaAmbulatoria = $('#chkCirugiaAmbulatoria').prop('checked') ? 1 : 0;
					scanner = $('#chkScanner').prop('checked') ? 1 : 0;
					ultrasonido = $('#chkUltrasonido').prop('checked') ? 1 : 0;
					dialisis = $('#chkDialisis').prop('checked') ? 1 : 0;
					hemodialisis = $('#chkHemodialisis').prop('checked') ? 1 : 0;
					resonanciaMagnetica = $('#chkResonanciaMagnetica').prop('checked') ? 1 : 0;
					
					*/

					$("#divRespuesta").load("ajax/guardarInstitucion.php", {
						idInst: $("#hdnIdInst").val(),
						idUsuario: $("#hdnIdUser").val(),
						tipoInst: tipoInst,
						subtipo: subtipo,
						formato: formato,
						estatus: estatus,
						categoria: categoria,
						frecuencia: frecuencia,
						inst: inst,
						calle: calle,
						numExt: numExt,
						sucursal: sucursal,
						cp: cp,
						colonia: colonia,
						city: city,
						tel1: tel1,
						tel2: tel2,
						celular: celular,
						email: email,
						web: web,
						posiciongps: posiciongps,
						comentarios: comentarios,
						/*
						representantesUnidos: representantesUnidos,
						nivelVentas: nivelVentas,
						nombreComercialFarmacia: nombreComercialFarmacia,
						rotacion: rotacion,
						tipoPaciente: tipoPaciente,
						numEmpleados: numEmpleados,
						numCtesxDia: numCtesxDia,
						numMedFarm: numMedFarm,
						accesibilidad: accesibilidad,
						numCtes: numCtes,
						recibeVendedores: recibeVendedores,
						ventaGenericos: ventaGenericos,
						tamanoFarmacia: tamanoFarmacia,
						mayorista1: mayorista1,
						mayorista2: mayorista2,
						numAnaqueles: numAnaqueles,
						numVisitasXciclo: numVisitasXciclo,
						turnos: turnos,
						frecuenciaVisita: frecuenciaVisita,
						trabajaInstPublica: trabajaInstPublica,
						ubicacion: ubicacion,
						
						numCamas: numCamas,
						numQuirofano: numQuirofano,
						numSalasExpulsion: numSalasExpulsion,
						numCunas: numCunas,
						numIncubadoras: numIncubadoras,
						terapiaIntensiva: terapiaIntensiva,
						unidadCuidadosIntensivos: unidadCuidadosIntensivos,
						infectologia: infectologia,
						laboratorio: laboratorio,
						urgencias: urgencias,
						rayosx: rayosx,
						farmacia: farmacia,
						botiquin: botiquin,
						endoscopia: endoscopia,
						consultaExterna: consultaExterna,
						cirugiaAmbulatoria: cirugiaAmbulatoria,
						scanner: scanner,
						ultrasonido: ultrasonido,
						dialisis: dialisis,
						hemodialisis: hemodialisis,
						resonanciaMagnetica: resonanciaMagnetica,
						PersonaNueva: PersonaNueva,
						
						
						*/
						tipoUsuario: tipoUsuario,							
						rutaAsignada: rutaAsignada,
						flonorm:flonorm,
						vessel:vessel,
						ateka:ateka,
						zirfos:zirfos,
						esoxx:esoxx,
						mayoristas:mayoristas,
						catAlfa:catAlfa
					});
				} else {
					alertFaltanDatos();
				}
			});

			$('#sltProductoStock').change(function(){
				funcion = 1;
				idProducto = $('#sltProductoStock').val();
				$('#divRespuesta').load("ajax/cargarProductosSeleccionados.php",{funcion:funcion,idProducto:idProducto});
			});
			
			$('#btnAceptarProductoSeleccionado').click(function(){
				totalProductos = $('#hdnTotalProdcutosSeleccionadosStock').val();
				idProdForm = '';
				existencia = '';
				precio = '';
				pedido = '';
				sugerido = '';
				idProducto = $('#sltProductoStock').val();
				funcion = 2;
				for(var i=1; i<= totalProductos; i++){
					idProdForm += $('#hdnIdProdForm'+i).val() + ',';
					existencia += $('#txtExistenciaStock'+i).val() + ',';
					precio += $('#txtPrecioStock'+i).val() + ',';
					pedido += $('#txtPedidoStock'+i).val() + ',';
					sugerido += $('#txtSugerido'+i).val() + ',';
				}
				$('#divRespuesta').load("ajax/cargarProductosSeleccionados.php",{funcion:funcion,idProducto:idProducto,idProdForm:idProdForm,existencia:existencia,precio:precio,pedido:pedido,sugerido:sugerido});
			});
			
			$('#txtCPDepto').keyup(function(){
				tam = $('#txtCPDepto').val().length;
				if(tam == 5){
					cp = $('#txtCPDepto').val();
					$("#divRespuesta").load("ajax/traeColonias.php",{cp:cp,pantalla:'depto'});
				}
			});
			
			$('#btnCancelarDepartamento').click(function(){
				$('#divCapa3').hide();
				$('#divDepartamento').hide();
				$('#hdnIDepto').val('');

				var validator = $("#formAgregarDepto").validate();
				validator.resetForm();
			});
			
			/**Agregar Departamento */
			agregarDepto();

			$("#btnGuardarDepartamento").click(function () {

				var registroCorrecto = true;

				if ($("#formAgregarDepto").valid()) {
					registroCorrecto = true;

					if (!registroCorrecto) {
						return;
					}

					var idDepto = $('#hdnIDepto').val();
					var idCity = $('#hdnCityDepto').val();
					var estatus = $('#sltEstatusDepto').val();
					var tipo = $('#sltTipoDepartamento').val();
					var nombre = $('#txtNombreDepartamento').val();
					var responsable = $('#txtNombreResponsableDepto').val();
					var calle = $('#txtCalleDepto').val();
					var tel1 = $('#txtTelefono1Depto').val();
					var tel2 = $('#txtTelefono2Depto').val();
					var celular = $('#txtCelularDepto').val();
					var mail = $('#txtEmailDepto').val();
					var comentarios = $('#txtComentariosDepto').val();
					var idInst = $('#hdnIdInst').val();

					$('#divRespuesta').load('ajax/guardarDepartamento.php', {
						idCity: idCity,
						estatus: estatus,
						tipo: tipo,
						nombre: nombre,
						responsable: responsable,
						calle: calle,
						tel1: tel1,
						tel2: tel2,
						celular: celular,
						mail: mail,
						comentarios: comentarios,
						idInst: idInst,
						idDepto: idDepto
					});
				} else {
					alertFaltanDatos();
				}
			});

			$('#btnCambiarRutaInst').click(function(){
				$('#btnCambiarRutaInst').hide();
				$('#btnAprobacionesInst').hide();
				$('#btnExportarInst').hide();
				$('#btnReVisitadosInst').hide();
				$('#btnVisitadosInst').hide();
				$('#btnNoVisitadosInst').hide();
				$('.btnReVisitadosInst2').hide();
				$('.btnVisitadosInst2').hide();
				$('.btnNoVisitadosInst2').hide();
				$('.btnTodosInst2').hide();
				$('#btnTodosInst').hide();
				$('#imgAgregarInstitucion').hide();
				
				$('#lblSeleccionarTodosCambiarRutaInst').show();
				$('#chkSeleccionarTodosCambiarRutaInst').show();
				$('#btnAceptarCambiarRutaInst').show();
				$('#btnCancelarCambiarRutaInst').show();
				
				$("#hdnSelecciandoCambiarRutaInst").val('');
				$("#chkSeleccionarTodosCambiarRutaInst").prop('checked', false);
				
				$('#divGridInstituciones').hide();
				$('#divCambiarRutaInst').show();

				$("#leftsidebarInst").addClass('showleftsidebarInst');
				$("#leftsidebarInst").removeClass('hideleftsidebarInst');
				$(".tab-sidebar-inst-r").css("display", "none");
				$(".tab-sidebar-inst-l").css("display", "block");

				if(idInstTabs == "inst1"){
					$('#trinstR1').addClass('div-slt-lista');
					$('#trinstR2').removeClass('div-slt-lista');
					$('#trinstR3').removeClass('div-slt-lista');
					$('#trinstR4').removeClass('div-slt-lista');
					$('#trinstR5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "inst2"){
					$('#trinstR1').removeClass('div-slt-lista');
					$('#trinstR2').addClass('div-slt-lista');
					$('#trinstR3').removeClass('div-slt-lista');
					$('#trinstR4').removeClass('div-slt-lista');
					$('#trinstR5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "inst3"){
					$('#trinstR1').removeClass('div-slt-lista');
					$('#trinstR2').removeClass('div-slt-lista');
					$('#trinstR3').addClass('div-slt-lista');
					$('#trinstR4').removeClass('div-slt-lista');
					$('#trinstR5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "inst4"){
					$('#trinstR1').removeClass('div-slt-lista');
					$('#trinstR2').removeClass('div-slt-lista');
					$('#trinstR3').removeClass('div-slt-lista');
					$('#trinstR4').addClass('div-slt-lista');
					$('#trinstR5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "inst5"){
					$('#trinstR1').removeClass('div-slt-lista');
					$('#trinstR2').removeClass('div-slt-lista');
					$('#trinstR3').removeClass('div-slt-lista');
					$('#trinstR4').removeClass('div-slt-lista');
					$('#trinstR5').addClass('div-slt-lista');
				}
			});
			
			$('#btnAceptarCambiarRutaInst').click(function(){
				if($('#hdnSelecciandoCambiarRutaInst').val() == ""){
					alertSeleccionaInst();
					return;
				}
				idUsuario = $('#hdnIdUser').val();
				ids = $('#hdnIds').val();
				$('#divRutaNueva').show();
				$('#over').show(500);
				$('#fade').show(500);
				$('#divRespuesta').load("ajax/cargarRutasCambioPersonas.php", {idUsuario:idUsuario,ids:ids});
			});
			
			$('#btnCancelarCambiarRutaInst').click(function(){
				$('#btnCambiarRutaInst').show();
				$('#btnAprobacionesInst').show();
				$('#btnExportarInst').show();
				$('#btnReVisitadosInst').show();
				$('#btnVisitadosInst').show();
				$('#btnNoVisitadosInst').show();
				$('#btnTodosInst').show();
				$('#imgAgregarInstitucion').show();
				
				$('#lblSeleccionarTodosCambiarRutaInst').hide();
				$('#chkSeleccionarTodosCambiarRutaInst').hide();
				$('#btnAceptarCambiarRutaInst').hide();
				$('#btnCancelarCambiarRutaInst').hide();
				
				$('#divGridInstituciones').show();
				$('#divCambiarRutaInst').hide();
				
				$("#hdnSelecciandoCambiarRutaInst").val('');
				$("#chkSeleccionarTodosCambiarRutaInst").prop('checked', false);
				
				for(var i=1;i<21;i++){
					$("#chkCambiarRutaInst"+i).prop("checked", false);
				}

				if(idInstTabs == "instR1"){
					$('#trinst1').addClass('div-slt-lista');
					$('#trinst2').removeClass('div-slt-lista');
					$('#trinst3').removeClass('div-slt-lista');
					$('#trinst4').removeClass('div-slt-lista');
					$('#trinst5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "instR2"){
					$('#trinst1').removeClass('div-slt-lista');
					$('#trinst2').addClass('div-slt-lista');
					$('#trinst3').removeClass('div-slt-lista');
					$('#trinst4').removeClass('div-slt-lista');
					$('#trinst5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "instR3"){
					$('#trinst1').removeClass('div-slt-lista');
					$('#trinst2').removeClass('div-slt-lista');
					$('#trinst3').addClass('div-slt-lista');
					$('#trinst4').removeClass('div-slt-lista');
					$('#trinst5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "instR4"){
					$('#trinst1').removeClass('div-slt-lista');
					$('#trinst2').removeClass('div-slt-lista');
					$('#trinst3').removeClass('div-slt-lista');
					$('#trinst4').addClass('div-slt-lista');
					$('#trinst5').removeClass('div-slt-lista');
				}
				if(idInstTabs == "instR5"){
					$('#trinst1').removeClass('div-slt-lista');
					$('#trinst2').removeClass('div-slt-lista');
					$('#trinst3').removeClass('div-slt-lista');
					$('#trinst4').removeClass('div-slt-lista');
					$('#trinst5').addClass('div-slt-lista');
				}
			});
			
			$("#chkSeleccionarTodosCambiarRutaInst").click(function(){
				for(var i=1;i<21;i++){
					if($('#chkSeleccionarTodosCambiarRutaInst').prop('checked')){
						$("#chkCambiarRutaInst"+i).prop("checked", true);
						$("#hdnSelecciandoCambiarRutaInst").val('todo');
					}else{
						$("#chkCambiarRutaInst"+i).prop("checked", false);
						$("#hdnSelecciandoCambiarRutaInst").val('');
					}
				}
				
			});
			
			$("#btnGuardarCambioRuta").click(function(){
				rutaNueva = $('input:radio[name=radioRutas]:checked').val();
				ids = $("#hdnIds").val();
				if(! rutaNueva){
					alertNuevaRuta();
					return;
				}
				$("#btnGuardarCambioRuta").prop("disabled", true);
				if($("#divPersonas").is (':visible')){
					if($("#hdnSelecciandoCambiarRuta").val() == 'todo'){
						idsPersonas = '';
						//tipoPersona = $('#sltTipoPersonaFiltro').val();
						nombre = $('#txtNombreFiltro').val();
						apellidos = $('#txtApellidosFiltro').val();
						especialidad = $('#sltEspecialidadFiltro').val();
						categoria = $('#sltCategoriaFiltro').val();
						inst = $('#txtInstitucionFiltro').val();
						dir = $('#txtDireccionFiltro').val();
						del = $('#txtDelegacionFiltro').val();
						estado = $('#txtEstadoFiltro').val();
						repre = $('#hdnIdsFiltroUsuarios').val();
					}else{
						idsPersonas = $("#hdnSelecciandoCambiarRuta").val();
					}
					
					//$('#divRespuesta').load("ajax/cambiarRuta.php",{ids:ids,rutaNueva:rutaNueva,idsPersonas:idsPersonas,tipoPersona:tipoPersona,nombre:nombre,apellidos:apellidos,especialidad:especialidad,categoria:categoria,inst:inst,dir:dir,del:del,estado:estado,repre:repre});
					$('#divRespuesta').load("ajax/cambiarRuta.php",{ids:ids,rutaNueva:rutaNueva,idsPersonas:idsPersonas,nombre:nombre,apellidos:apellidos,especialidad:especialidad,categoria:categoria,inst:inst,dir:dir,del:del,estado:estado,repre:repre});
				}else{
					if($("#hdnSelecciandoCambiarRutaInst").val() == 'todo'){
						var tipo = $('#sltTipoInstFiltro').val();
						var nombre = $('#txtNombreInstFiltro').val();
						var calle = $('#txtCalleInstFiltro').val();
						var colonia = $('#txtColoniaInstFiltro').val();
						var ciudad = $('#txtCiudadInstFiltro').val();
						var estado = $('#txtEstadoInstFiltro').val();
						var cp = $('#txtCPInstFiltro').val();
						var repre = $('#hdnIdsFiltroUsuarios').val();
						var geolocalizados = $('input:radio[name=rbGeo]:checked').val();
						//var tabActivo = $("#divGridInstituciones").tabs('option', 'active');
						var tabActivo = tabActivoInst;
						idsInsts = '';
					}else{
						idsInsts = $("#hdnSelecciandoCambiarRutaInst").val();
					}
					
					$('#divRespuesta').load("ajax/cambiarRutaInst.php",{ids:ids,rutaNueva:rutaNueva,idsInsts:idsInsts,tipo:tipo,nombre:nombre,calle:calle,colonia:colonia,ciudad:ciudad,estado:estado,cp:cp,geolocalizados:geolocalizados,repre:repre,tabActivo:tabActivo});
					
				}
				$("#btnGuardarCambioRuta").prop("disabled", false);
			});
			
			$('#btnAceptarBajaInst').click(function(){
				var idInstBaja = $('#hdnIdInstBaja').val();
				var idUsuario = $('#hdnIdUser').val(); 
				var motivo = $('#sltMotivoBajaInst').val()
				var comentarios = $('#txtComentariosBajaInst').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				if(motivo == ''){
					alertIngresarMotivo();
					return;
				}
				
				/*if(comentarios == ''){
					alertIngresarComen();
					return;
				}*/
				$('#divRespuesta').load("ajax/eliminarInst.php",{idInst:idInstBaja,idUsuario:idUsuario,motivo:motivo,comentarios:comentarios,tipoUsuario:tipoUsuario});
				$('#hdnIdInstBaja').val('');
				$('#sltMotivoBajaInst').val('');
				$('#txtComentariosBajaInst').val('');
				$('#cerrarInformacion').click();
			});
			
			$('#btnCancelarBajaInst').click(function(){
				$('#hdnIdPersonaBajaInst').val('');
				$('#sltMotivoBajaInst').val('');
				$('#txtComentariosBajaInst').val('');
				$('#cerrarInformacion').click();
				$('#' + idInstTabs).click();
				$('#' + idTrInst).addClass('div-slt-lista');
			});
			
			$('#btnActualizarInst').click(function(){
				var pagina = $('#hdnPaginaInst').val();
				var hoy = $('#hdnHoy').val();
				var ids = $('#hdnIds').val();

				alertActualizando();

				nuevaPaginaInst(pagina,hoy,ids,'');
			});
			
			/*fin de instituciones*/
			
			/* calendario */
			$("#agregarPlanVisita").click(function(){
				$("#agregarPlanVisita").hide();
				$("#menuCalendarioAgregarPlanVisita").show(500);
			});
			
			$('#btnAgregarProductosSupervision').click(function(){
				abrirVentanaPersona('productos.php', 450, 500)
			});

			$("#txtBuscarPersona").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					palabra = $("#txtBuscarPersona").val();
					dia = $("#hdnDiaCalendario").val();
					fecha = $("#hdnFechaCalendario").val();
					var repre = $("#sltRutaBuscaPersonas").val();
					idUsuario = $("#hdnIdUser").val();
					ids = $("#hdnIds").val();
					tipoUsuario = $("#hdnTipoUsuario").val();

					cargandoBuscadorMedSearch();
					$("#divRespuesta").load("ajax/persFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
						$('.cargaBusquedaMed').waitMe('hide');
					});
				}
			});

			$("#btnBuscarMed").click(function() {
				palabra = $("#txtBuscarPersona").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaPersonas").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();

				cargandoBuscadorMedSearch();
				$("#divRespuesta").load("ajax/persFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
					$('.cargaBusquedaMed').waitMe('hide');
				});
			});
			
			/*$('#txtBuscarPersona').keyup(function() {
				palabra = $("#txtBuscarPersona").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaPersonas").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				$("#divRespuesta").load("ajax/persFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre});
			});*/

			$("#txtBuscarInst").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					palabra = $("#txtBuscarInst").val();
					dia = $("#hdnDiaCalendario").val();
					fecha = $("#hdnFechaCalendario").val();
					var repre = $("#sltRutaBuscaInst").val();
					idUsuario = $("#hdnIdUser").val();
					ids = $("#hdnIds").val();
					tipoUsuario = $("#hdnTipoUsuario").val();
					cargandoBuscadorInstSearch();
					$("#divRespuesta").load("ajax/InstFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
						$('.buscaInst').waitMe('hide');
					});
				}
			});

			$("#btnBuscarInst").click(function() {
				palabra = $("#txtBuscarInst").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaInst").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				cargandoBuscadorInstSearch();
				$("#divRespuesta").load("ajax/InstFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
					$('.buscaInst').waitMe('hide');
				});
			});

			$("#txtBuscarHos").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					palabra = $("#txtBuscarHos").val();
					dia = $("#hdnDiaCalendario").val();
					fecha = $("#hdnFechaCalendario").val();
					var repre = $("#sltRutaBuscaInst").val();
					idUsuario = $("#hdnIdUser").val();
					ids = $("#hdnIds").val();
					tipoUsuario = $("#hdnTipoUsuario").val();
					cargandoBuscadorInstSearch();
					$("#divRespuesta").load("ajax/hosFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
						$('.buscaInst').waitMe('hide');
					});
				}
			});

			$("#btnBuscarHos").click(function() {
				palabra = $("#txtBuscarHos").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaInst").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				cargandoBuscadorInstSearch();
				$("#divRespuesta").load("ajax/hosFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
					$('.buscaInst').waitMe('hide');
				});
			});

			$("#txtBuscarFar2").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					palabra = $("#txtBuscarFar2").val();
					dia = $("#hdnDiaCalendario").val();
					fecha = $("#hdnFechaCalendario").val();
					var repre = $("#sltRutaBuscaInst").val();
					idUsuario = $("#hdnIdUser").val();
					ids = $("#hdnIds").val();
					tipoUsuario = $("#hdnTipoUsuario").val();
					cargandoBuscadorInstSearch();
					$("#divRespuesta").load("ajax/farFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
						$('.buscaInst').waitMe('hide');
					});
				}
			});

			$("#btnBuscarFar2").click(function() {
				palabra = $("#txtBuscarFar2").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaInst").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				cargandoBuscadorInstSearch();
				$("#divRespuesta").load("ajax/farFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre}, function(){
					$('.buscaInst').waitMe('hide');
				});
			});
			
			/*$('#txtBuscarInst').keyup(function() {
				palabra = $("#txtBuscarInst").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaInst").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				//alert(tipoUsuario);
				$("#divRespuesta").load("ajax/InstFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre});
			});*/
			
			$('#sltRutaBuscaInst').change(function(){
				palabra = $("#txtBuscarInst").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				var repre = $("#sltRutaBuscaInst").val();
				idUsuario = $("#hdnIdUser").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				$("#divRespuesta").load("ajax/InstFiltradas.php",{palabra:palabra,idUsuario:idUsuario,ids:ids,dia:dia,fecha:fecha,tipoUsuario:tipoUsuario,repre:repre});
			});
			
			$("#chkSeleccionarTodo").change(function(){
				total = $("#hdnTotalChecks").val();
				for(i=1;i<=total;i++){
					$('#repre'+i).prop('checked',$("#chkSeleccionarTodo").prop('checked'));
				}
			});
			
			$('#btnGuardarPeriodo').click(function(){
				$('#tdFechaOtrasActividades').show();
				$('#btnQuitarPeriodo').show();
				$('#btnGuardarPeriodo').hide();
			});

			$('#btnQuitarPeriodo').click(function(){
				$('#tdFechaOtrasActividades').hide();
				$('#btnQuitarPeriodo').hide();
				$('#btnGuardarPeriodo').show();
			});
			
			$('#btnPlanCalendario').click(function(){
				actualizaCalendarioBoton('plan');
			});
			
			$('#btnVisitaCalendario').click(function(){
				actualizaCalendarioBoton('visita');
			});

			$('#btnPlanCalendario').click(function(){
				$('#btnVisitaCalendario').removeClass("btn-account-sel");
				$('#btnPlanCalendario').addClass("btn-account-sel");

				$("#btnVisitaCalendario").removeAttr("disabled");
				$("#btnPlanCalendario").attr("disabled", true);

				$("#tituloPlanHoy").show();
				$("#tituloVisitaHoy").hide();
			});
			$('#btnVisitaCalendario').click(function(){
				$('#btnPlanCalendario').removeClass("btn-account-sel");
				$('#btnVisitaCalendario').addClass("btn-account-sel");

				$("#btnPlanCalendario").removeAttr("disabled");
				$("#btnVisitaCalendario").attr("disabled", true);

				$("#tituloPlanHoy").hide();
				$("#tituloVisitaHoy").show();
			});
			
			/*$('#btnRuteo2').click(function(){
				tipoUsuario = $("#hdnTipoUsuario").val();
				if(tipoUsuario == 4){
					idUsuario = $('#hdnIdUser').val();
				}else{
					//alert($('#hdnIdsFiltroUsuarios').val());
					if($('#hdnIdsFiltroUsuarios').val() == ''){
						alertSelRepresentante();
						return true;
					}else{
						if($('#hdnIdsFiltroUsuarios').val().split(",").length > 2){
							alertSelUnRepresentante();
							return true;
						}else{
							idUsuario = $('#hdnIdsFiltroUsuarios').val();
						}
					}
				}
				$('#divBuscarPersonas').hide();
				$('#divReportarOtrasActividades').hide();
				$('#divBuscarInst').hide();
				$('#divRuteo').show();
				$('#over').show(500);
				$('#fade').show(500);
				load_map('map_canvas3');
				fecha = $("#hdnFechaCalendario").val();
				if($('#btnPlanCalendario').hasClass('btn-account-sel')){
					planVisita = 'plan';
					$('#btnPlanRuteo').addClass("seleccionado");
					$("#btnVisitaRuteo").removeClass("seleccionado");
					//$("#btnPlanRuteo").click();

				
				}else if($('#btnVisitaCalendario').hasClass('btn-account-sel')){
					planVisita = 'visita';
					$('#btnPlanRuteo').removeClass("seleccionado");
					$("#btnVisitaRuteo").addClass("seleccionado");
					//$("#btnVisitaRuteo").click();
				}
				
				$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
				
			});*/
			
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
				var horas='';
				var totalChkOA = $('#hdnTotalChkOA').val();
				var totalHoras = $('#txtTotalActividades').val();
				var idUsuario = $('#hdnIdUser').val();
				var fecha = $('#txtFechaReportarOtrasActividades').val();
				var comentariosTft=$('#txtAreaReportarOtrasActividades').val();
				if($('#txtFechaReportarOtrasActividadesFin').is (':visible')){
					var fechaFin = $('#txtFechaReportarOtrasActividadesFin').val();
				}else{
					var fechaFin = '';
				}
				//var comentarios = $('#txtComentariosOtrasActividades').val();
				var comentarios = '';
				var repre = $('#hdnIdsFiltroUsuarios').val();
				if($("#txtFechaReportarOtrasActividadesFin").is (':visible')){
					if(fecha > fechaFin){
						alertFechaFinal();
						return true;
					}
				}
				if(totalHoras > 8){
					alertMaxHoras();
					return true;
				}
				for(i=1;i<=totalChkOA;i++){
					if($('#chkOA'+i).prop('checked')){
						if($('#txtOA'+i).val() == ''){
							alertNumHoras();
							$('#txtOA'+i).focus();
							return;
						}else{
							if(actividades == ''){
								actividades = $('#chkOA'+i).val();
								horas = $('#txtOA'+i).val();
							}else{
								actividades += ',' + $('#chkOA'+i).val();
								horas += "," + $('#txtOA'+i).val();
							}
						}
					}else{
						if($('#txtOA'+i).val() != ''){
							$('#chkOA'+i).prop('checked', true);
							actividades += ',' + $('#chkOA'+i).val();
							horas += "," + $('#txtOA'+i).val();
						}
					}
				}
				if(actividades == ''){
					alertSeleccionaActividad();
					return true;
				}
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					planVisita = 'plan';
	
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					planVisita = 'visita';

				}
				//alert('usuario: ' + idUsuario + ' ::: repre: ' + repre);
				if($('#hdnIdOA').val() == ''){
					$('#divRespuesta').load("ajax/guardaOtrasActividades.php",{fecha:fecha,idUsuario:idUsuario,comentarios:comentariosTft,actividades:actividades,horas:horas,fechaFin:fechaFin,planVisita:planVisita,repre:repre,comentariosTft:comentariosTft});
				}else{
					idOA = $('#hdnIdOA').val();
					idUsuario = $('#hdnIdUsuarioOA').val();
					$('#divRespuesta').load("ajax/guardaOtrasActividades.php",{fecha:fecha,idUsuario:idUsuario,comentarios:comentariosTft,actividades:actividades,horas:horas,planVisita:planVisita,idOA:idOA,repre:'',comentariosTft:comentariosTft});
				}
				$('#btnCancelarOtrasActividades').click();
			});
			
			$('#btnEliminarOtrasActividades').click(function(){
				id = $('#hdnIdOA').val();
				if(confirm('¿Esta seguro de eliminarlo?')){
					$('#divRespuesta').load("ajax/eliminarOtrasActividades.php",{id:id});
				}
			});
			
			$('#sltRepreCalendario').change(function(){
				idRepre = $('#sltRepreCalendario').val();
				if($('#hdnTipoUsuario').val == 4){
					var ids = $('#hdnIds').val();
				}else{
					var ids = $('#hdnIds').val()+"','"+'<?= $idUsuario ?>';
				}
				if($('#btnPlanCalendario').hasClass('seleccionado')){
					planVisita = 'plan';
				}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
					planVisita = 'visita';
				}
				
				cargandoCalendario();
				$('#divRespuesta').load("ajaxCalendario.php", {fecha:$('#hdnFechaCalendario').val(), idUsuario:idRepre, planVisita:planVisita,ids:ids}, function(){
					$(".cardInfCal").waitMe("hide");
				});
			});
			
			$('#btnCopiarPlanes').click(function(){
				$('#txtFechaIcopiarPlanes').val('');
				$('#txtFechaFcopiarPlanes').val('');
				$('#txtFechaOcopiarPlanes').val('');
				$('#tblCopiarPlanes tbody').empty();
				$('#tblCopiarPlanes tfoot').empty();
				$('input:radio[name=optPlanes]').attr("checked", false);
				$('#divCopiarPlanes').show();
				$('#over').show(500);
				$('#fade').show(500);
			});
			
			/* copiar planes */
			$("#txtFechaIcopiarPlanes").change(function(){
				cambiarFecha('txtFechaIcopiarPlanes');
				$('input:radio[name=optPlanes]').attr("checked", false);
			});
			
			$("#txtFechaFcopiarPlanes").change(function(){
				cambiarFecha('txtFechaFcopiarPlanes');
				$('input:radio[name=optPlanes]').attr("checked", false);
			});
			
			$("#txtFechaOcopiarPlanes").change(function(){
				cambiarFecha('txtFechaOcopiarPlanes');
				$('input:radio[name=optPlanes]').attr("checked", false);
			});
			
			$("input[name='optPlanes']").change(function(){
				var fechaObjetivo = $('#txtFechaOcopiarPlanes').val();
				var fechaIni = $('#txtFechaIcopiarPlanes').val();
				var fechaFin = $('#txtFechaFcopiarPlanes').val();
				var visitados = $('input:radio[name=optPlanes]:checked').val();
				var idUsuario = $('#hdnIdUser').val();
				var ids = $('#hdnIds').val();
				if(fechaIni > fechaFin){
					alertFechaInicial();
					$('input:radio[name=optPlanes]').attr("checked", false);
					return true;
				}
				if(fechaFin > fechaObjetivo){
					alertFechaObjMenor();
					$('input:radio[name=optPlanes]').attr("checked", false);
					return true;
				}
				$('#divRespuesta').load("ajax/cargarPlanesCopiar.php",{idUsuario:idUsuario,fechaIni:fechaIni,fechaFin:fechaFin,visitados:visitados,ids:ids});
			});
			
			$('#btnCopiarPlanesFuncion').click(function(){
				var fechaObjetivo = $('#txtFechaOcopiarPlanes').val();
				var fechaIni = $('#txtFechaIcopiarPlanes').val();
				var fechaFin = $('#txtFechaFcopiarPlanes').val();
				var visitados = $('input:radio[name=optPlanes]:checked').val();
				var idUsuario = $('#hdnIdUser').val();
				var ids = $('#hdnIds').val();
				if(fechaFin > fechaObjetivo){
					alertFechaObjMayor();
					return true;
				}
				$('#divRespuesta').load("ajax/copiarPlanes.php",{idUsuario:idUsuario,fechaIni:fechaIni,fechaFin:fechaFin,visitados:visitados,fechaObjetivo:fechaObjetivo,ids:ids});
			});
			
			$("#sltRutaBuscaPersonas").change(function (){
				var repre = $("#sltRutaBuscaPersonas").val();
				var palabra = $("#txtBuscarPersona").val();
				dia = $("#hdnDiaCalendario").val();
				fecha = $("#hdnFechaCalendario").val();
				idUsuario = $("#hdnIds").val();
				ids = $("#hdnIds").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				$("#divRespuesta").load("ajax/persFiltradas.php",{palabra:palabra,idUsuario:idUsuario,dia:dia,fecha:fecha,repre:repre,ids:ids,tipoUsuario:tipoUsuario});
			});
			
			/* termina copiar planes */
			
			$('#btnPlanearRapido').click(function(){
				$('#divPlanesRapidos').show();
				$('#over').show(500);
				$('#fade').show(500);
			});
			
			/*for(i=1;i<=$('#hdnTotalPersonasPlanesRapidos').val();i++){
				$('#item'+i).draggable({
					helper: 'clone'
				});
			}
			
			for(j=7;j<23;j++){
				for(k=0;k<2;k++){
					min = j*30;
					$('#divLunes'+i+j).droppable({
					  accept: '.item1',
					  hoverClass: 'hovering',
					  drop: function( ev, ui ) {
						ui.draggable.detach();
						$( this ).append( ui.draggable );
					  }
					});
				}
			}
			
			id=\"item".$item."\"*/
			
			$('.item').draggable({
					helper: 'clone'
				});
				
			$('.day').droppable({
				
				accept: '.item',
				hoverClass: 'hovering',
				drop: function( ev, ui ) {
					ui.draggable.detach();
					$( this ).append( ui.draggable );
					if($("#tabPersonas").is (':visible')){
						var tipo = 'p';
					}else{
						var tipo = 'i';
					}
					$("#"+$(this).attr("id")).load("ajax/actualizaDivPlanesRapido.php",{div:$(this).attr("id"),tipo:tipo,id:$(ui.draggable).attr("id")});
				}
			});
  
			/* fin de calendario */
			$("#btnCancelarSigPlan").click(function(){
				$("#divPlanes").hide();
				$("#divVisitas").show();
			});
			/* planes */
			$("#btnGuardarPlan").click(function(){
				$("#btnGuardarPlan").prop("disabled", true);
				id_plan = $("#hdnIdPlan").val();
				fecha_plan = $("#txtFechaPlan").val();
				hora_plan = $("#lstHoraPlan").val() + ':' + $("#lstMinutosPlan").val();
				codigo_plan = $("#lstCodigoPlan").val();
				objetivo_plan = $("#objetivoPlan").val();
				pantalla = $("#hdnPantallaPlan").val();
				idPersona = $("#hdnIdPersona").val();
				idUsuario = $("#sltReprePlan").val();
				idUser = $('#hdnIdUser').val();
				ruta = $('#hdnRutaDatosPersonales').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				$("#divRespuesta").load("ajax/guardarPlan.php",{idPlan:id_plan,fechaPlan:fecha_plan,horaPlan:hora_plan,codigoPlan:codigo_plan,objetivoPlan:objetivo_plan,idPersona:idPersona,idUsuario:idUsuario,pantalla:pantalla,ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser});
			});
			
			$("#btnCancelarPlan").click(function(){
				$("#divPlanes").hide();
				$("#divCapa3").hide();
				//window.close();
			});
			
			$("#btnReportarPlan").click(function(){
				if($('#hdnIdVisitaPlan').val() != '' && $('#hdnIdVisitaPlan').val() != '00000000-0000-0000-0000-000000000000'){
					alertReportPlan();
					return true;
				}
				var idPersona = $('#hdnIdPersona').val();
				var idPlan = $('#hdnIdPlan').val();
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
				pantalla = $('#hdnPantallaPlan').val();
				especialidad = $('#hdnEspecialidadPersona').val();
				cicloActivo = $('#hdnCicloActivo').val();

				cargandoVisita();
				$('#divRespuesta').load("ajax/cargarVisita.php", {idPersona:idPersona,lat:lat,lon:lon,idPlan:idPlan,pantalla:pantalla,especialidad:especialidad,cicloActivo:cicloActivo}, function(){
					$("#cargandoInfVisitaMed").waitMe('hide');
				});
				$('#divPlanes').hide();
				$('#divVisitas').show();
				$('#btnEliminarVisitaPerson').hide();
			});
			
			$('#btnEliminarPlanPerson').click(function(){
				idPlan = $('#hdnIdPlan').val();
				/*if (confirm('¿Esta seguro de eliminar el plan?')) {
					$('#divRespuesta').load("ajax/eliminarPlan.php", {idPlan:idPlan});
				}*/
				alertEliminarPlan(idPlan);
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
				var idPersona = $('#hdnIdPersona').val();
				$('#divVisitas').hide();
				$('#divPlanes').show();
				$('#btnEliminarPlanPerson').hide();
				$('#btnCancelarSigPlan').show();
				$("#divRespuesta").load("ajax/cargarPlan.php", {idPersona:idPersona,regreso:'visita'});
			});
			
			$('#btnCancelarVisitas').click(function(){
				$('#divVisitas').hide();
				$('#divCapa3').hide();
			});

			$('#btnGuardarVisitas').click(function (){
				//$("#btnGuardarVisitas").prop("disabled", true);
				//idVisita:'',
				//idPlan:'',
				var idPers = $('#hdnIdPersona').val();
				var idUsuario = $('#sltRepreVisita').val();
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
				var lat = $('#hdnLatitude').val();
				var lon = $('#hdnLongitude').val();
				if(codigoVisita == 0 || codigoVisita === null){
					alertCodVisita();
					$('#lstCodigoVisita').focus();
					$("#btnGuardarVisitas").prop("disabled", false);
					return;
				}
				
				visitaAcompa = '';
				//alert($("#hdnTotalChecksVisitas").val());
				for(i=1;i<=$("#hdnTotalChecksVisitas").val();i++){
					//alert(i + ' ' + $('#acompa'+i).prop('checked'));
					if($('#acompa'+i).prop('checked')){
						visitaAcompa += $('#acompa'+i).val() + ";";
					}
				}
				
				//alert(visitaAcompa);
				/*
				if(visitaAcompa == ''){
					alertVisAcompa();
					$("#btnGuardarVisitas").prop("disabled", false);
					return true;
				}
				*/
				
				if(comentariosVisita == ''){
					alertComenVisita();
					$("#txtComentariosVisita").focus();
					$("#btnGuardarVisitas").prop("disabled", false);
					return;
				}
				
				if(infoSiguienteVisita == ''){
					alertInfoSigVisita();
					$("#txtInfoSiguienteVisita").focus();
					$("#btnGuardarVisitas").prop("disabled", false);
					return;
				}
				
				if($("#hdnIdVisita").val() == ''){
					for(var i=1;i<11;i++){
						if($("#lstProducto"+i).size() > 0){
							if($("#lstProducto"+i).val() != '00000000-0000-0000-0000-000000000000'
							&& $("#lstProducto"+i).val() != null){
								productosSeleccionados += $("#lstProducto"+i).val()+"|";
								porcentajesProductosSeleccionados += $("#txtProdPos"+i).val()+"|";
							}else{
								break;
							}
						}
					}
					if(productosSeleccionados == ''){
						alertPromocionar();
						$("#btnGuardarVisitas").prop("disabled", false);
						return;
					}
				}
				
				//alert(productosSeleccionados);
				
				if($("#hdnTotalPromociones").val() > 0){
					for(var j=1;j<$("#hdnTotalPromociones").val();j++){
						if($("#text"+j).val() > 0){
							productosPromocionados += $("#hdnIdProducto"+j).val()+"|";
							cantidadProductosPromocionados += $("#text"+j).val()+"|";
							existencia = $("#hdnExistencia"+j).val();
							maximo = $("#hdnMaximo"+j).val();
							cantidad = $("#text"+j).val();
							if(parseInt(cantidad, 10) > parseInt(existencia, 10)){
								alertExistenciaPiezas(existencia);
								$("#btnGuardarVisitas").prop("disabled", false);
								return;
							}
							if(parseInt(cantidad, 10) > parseInt(maximo, 10)){
								alertMasPiezas(maximo);
								$("#btnGuardarVisitas").prop("disabled", false);
								return;
							}
						}
					}
				}
				/*revisa firma */
				
				if($("#hdnTotalPromociones").val() > 0){
					var requiereFirmaProducto = false;
					for(var j=1;j<$("#hdnTotalPromociones").val();j++){
						if($("#text"+j).val() > 0){
							if($("#hdnTipoProducto"+j).val() == 132){
								requiereFirmaProducto = true;
							}
						}
					}
				}
				
				if(requiereFirmaProducto){ 
					if($("#hdnFirma").val() == ''){
						alertFirmaMed();
						$("#btnGuardarVisitas").prop("disabled", false);
						return true;
					}
				}
				
				pantalla = $('#hdnPantallaVisitas').val();
				idVisita = $('#hdnIdVisita').val();
				idPlan = $('#hdnIdPlan').val();
				firma = $('#hdnFirma').val();
				ruta = $('#hdnRutaDatosPersonales').val();
				tipoUsuario = $('#hdnTipoUsuario').val();
				repre = $('#hdnIdUser').val();
				//alert(codigoVisita);
				//alert(lat+''+lon);
				
				$("#divRespuesta").load("ajax/guardaVisitaPersona.php", {
					idVisita: idVisita,
					idPlan: idPlan,
					idPers: idPers,
					idUsuario: idUsuario,
					fecha: fecha,
					hora: hora,
					codigoVisita: codigoVisita,
					visitaAcompa: visitaAcompa,
					comentariosVisita: comentariosVisita,
					infoSiguienteVisita: infoSiguienteVisita,
					comentariosMedico: comentariosMedico,
					productosSeleccionados: productosSeleccionados,
					productosPromocionados: productosPromocionados,
					cantidadProductosPromocionados: cantidadProductosPromocionados,
					lat: lat,
					lon: lon,
					pantalla: pantalla,
					porcentajesProductosSeleccionados: porcentajesProductosSeleccionados,
					firma: firma,
					ruta: ruta,
					tipoUsuario: tipoUsuario,
					repre: repre
				},function(){
						var idMedicoC = idmedico;
						$("#divRespuesta").load("ajax/cambiarCirculoStatus.php", {fecha:fecha,tipoUsuario:tipoUsuario,idUsuario:idUsuario,ruta:ruta,idPers:idPers,idMedicoC:idMedicoC});
						
				});
			});
			
			$("#btnCancelarCompetidor").click(function(){
				$("#divCompetidores").hide();
				$("#divCapa4").hide();
			});
			
			$("#btnGuardarCompetidor").click(function(){
				totalCompetidores = $('#tblCompetidor tbody tr').length;
				idProductoCompetidores = '';
				existenciaCompetidores = '';
				precioCompetidores = '';
				if(totalCompetidores > 0){
					for(i=1;i<=totalCompetidores;i++){
						idProductoCompetidores += $('#hdnIdProdFormCompetidor'+i).val() + ',';
						existenciaCompetidores += $('#txtExistenciaStockCompetidor'+i).val() + ',';
						precioCompetidores += $('#txtPrecioCompetidor'+i).val() + ',';
					}
					idPaso = $('#hdnIdProductosCompetidores').val() + idProductoCompetidores;
					existenciaPaso = $('#hdnExistenciaCompetidores').val() + existenciaCompetidores;
					precioPaso = $('#hdnPrecioCompetidores').val() + precioCompetidores;
					$('#hdnIdProductosCompetidores').val(idPaso);
					$('#hdnExistenciaCompetidores').val(existenciaPaso);
					$('#hdnPrecioCompetidores').val(precioPaso);
				}
				$('#divCapa4').hide();
				$('#divCompetidores').hide();
			});
			
			$('#btnEliminarVisitaPerson').click(function(){
				var idPers = $('#hdnIdPersona').val();
				var idUsuario = $('#sltRepreVisita').val();
				var fecha = $('#txtFechaVisita').val();
				var ruta = $('#hdnRutaDatosPersonales').val();
				var	tipoUsuario = $('#hdnTipoUsuario').val();
				var idMedicoC = idmedico;

				idVisita = $('#hdnIdVisita').val();
				/*if(confirm('¿Desea elimiar la visita?')){
					$('#divRespuesta').load("ajax/eliminarVisita.php",{idVisita:idVisita});
				}*/
				alertEliminarVisita(idVisita,fecha,tipoUsuario,idUsuario,ruta,idPers,idMedicoC);
			});
			
			$('#btnEliminarVisitaInst').click(function(){
				idVisita = $('#hdnIdVisitaInst').val();
				/*if(confirm('¿Desea elimiar la visita?')){
					$('#divRespuesta').load("ajax/eliminarVisitaInst.php",{idVisita:idVisita});
				}*/
				alertEliminarVisitaInst(idVisita);
			});
			/* fin visitas */
			
			/*ruteo */
			
			$('#btnPlanRuteo').click(function(){
				$("#btnPlanRuteo").addClass("seleccionado");
				$("#btnVisitaRuteo").removeClass("seleccionado");

				$('#btnVisitaRuteo').removeClass("btn-aprob-sel");
				$('#btnPlanRuteo').addClass("btn-aprob-sel");
				$("#btnVisitaRuteo").removeAttr("disabled");
				$("#btnPlanRuteo").attr("disabled", true);

				$("#tblSimbologiaMarcadoresRuteo").hide();

				fecha = $("#hdnFechaRuteo").val();
				tipo = $("#hdnTipoUsuario").val();
				if(tipo == 4){
					idUsuario = $('#hdnIdUser').val();
				}else{
					var idUsuarioF = $('#hdnIdsFiltroUsuarios').val();
					idUsuario = idUsuarioF.substring(0, idUsuarioF.length-1);
				}
				planVisita = 'plan';
				ids = $('#hdnIds').val();
				$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita,ids:ids });
			});
			
			$('#btnVisitaRuteo').click(function(){
				month = $('#sltMesCal').val();
				year = $('#sltYearCal').val();
				day = $('#hdnFechaRuteo').val().split("-")[2];

				$("#btnVisitaRuteo").addClass("seleccionado");
				$("#btnPlanRuteo").removeClass("seleccionado");

				$('#btnPlanRuteo').removeClass("btn-aprob-sel");
				$('#btnVisitaRuteo').addClass("btn-aprob-sel");
				$("#btnPlanRuteo").removeAttr("disabled");
				$("#btnVisitaRuteo").attr("disabled", true);
				$("#tblSimbologiaMarcadoresRuteo").show();

				fecha = $("#hdnFechaRuteo").val();
				tipo = $("#hdnTipoUsuario").val();
				if(tipo == 4){
					idUsuario = $('#hdnIdUser').val();
				}else{
					var idUsuarioF = $('#hdnIdsFiltroUsuarios').val();
					idUsuario = idUsuarioF.substring(0, idUsuarioF.length-1);
				}
				planVisita = 'visita';
				
				$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita,month:month,year:year,day:day});
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
			
			$("#sltTipo").change( function() {
				$(this).attr("data-id", "sltTipoInst");
				if ($(this).val() == "p") {
					$("#sltTipoInst").prop("disabled", true);
					$("#txtNombreInstLocalizador").prop("disabled", true);

					$('#txtNombreInstLocalizador').val('');
					$('#sltTipoInst').val('0');
					
					$("#sltEspecialidadRadar").prop("disabled", false);
					$("#txtNombreMedicoLocalizador").prop("disabled", false);
				} 
				if ($(this).val() == "i") {
					$("#sltTipoInst").prop("disabled", false);
					$("#txtNombreInstLocalizador").prop("disabled", false);
					
					$("#sltEspecialidadRadar").prop("disabled", true);
					$("#txtNombreMedicoLocalizador").prop("disabled", true);
				}
				
				if ($(this).val() == ""){
					$("#sltTipoInst").prop("disabled", false);
					$("#txtNombreInstLocalizador").prop("disabled", false);
					$("#sltEspecialidadRadar").prop("disabled", false);
					$("#txtNombreMedicoLocalizador").prop("disabled", false);
				}
			});

			$("#sltEspecialidadRadar").change( function() {
				//$(this).attr("data-id", "sltTipoInst");
				if ($(this).val() != '00000000-0000-0000-0000-000000000000') {
					$("#sltTipoInst").prop("disabled", true);
					$("#txtNombreInstLocalizador").prop("disabled", true);
				} else{
					$("#sltTipoInst").prop("disabled", false);
					$("#txtNombreInstLocalizador").prop("disabled", false);
				}
			});

			$("#sltTipoInst").change( function() {
				//$(this).attr("data-id", "sltTipoInst");
				if ($(this).val() != '0') {
					$("#sltEspecialidadRadar").prop("disabled", true);
					$("#txtNombreMedicoLocalizador").prop("disabled", true);
					$('#txtNombreMedicoLocalizador').val('');
					$('#sltEspecialidadRadar').val('00000000-0000-0000-0000-000000000000');
				}else{
					$("#sltEspecialidadRadar").prop("disabled", false);
					$("#txtNombreMedicoLocalizador").prop("disabled", false);
				}
			});
			
			$('#btnLocalizarFiltro').click(function(){
				ids = $('#hdnIds').val();
				km = $('#sltKilometros').val();
				vis = $('#sltVisitas').val();
				esp = $('#sltEspecialidadRadar').val();
				tipo = $('#sltTipo').val();
				tipoIns = $('#sltTipoInst').val();
				lat = $('#txtLatitud').val();
				lon = $('#txtLongitud').val();
				persona = $('#txtNombreMedicoLocalizador').val();
				inst = $('#txtNombreInstLocalizador').val();
				var repre = $('#hdnIdsFiltroUsuarios').val();

				if (($("#sltTipo").val() === "i") && ($("#sltTipoInst").val() == "0")) {
					//alert('Seleccione un tipo de institución');
					$("#sltTipoInst").addClass('invalid-slt');
					$("#txtTipoInstError").show();
				}else{
					$("#sltTipoInst").removeClass('invalid-slt');
					$("#txtTipoInstError").hide();
				} 

				$('#divRespuesta').load("ajax/marcadoresGeolocalizacion.php",{km:km,planVisita:vis,esp:esp,tipo:tipo,tipoIns:tipoIns,lat:lat,lon:lon,ids:ids,repre:repre,persona:persona,inst:inst});
			});
			
			/*******termina radar *********/
			
			/*********documentos***********/
			$('#btnFiltrarDocs').click(function(){
				var ids = $('#hdnIds').val();
				var repre = $('#hdnIdsFiltroUsuarios').val();
				//var idUsuario = $('#hdnIdUsuario').val();
				idUsuario = $('#hdnIdUser').val();
				//alert(idUsuario);
				$('#divRespuesta').load("ajax/cargarDocumentosEntregados.php", {repre:repre,ids:ids,idUsuario:idUsuario});
			});
			
			$('#btnArchivo').click(function(){
			  $('.inputfile').each(function () {
				var $input = $(this);
				$input.on('change', function (e) {
				  var fileName = '';
				  if (e.target.value){
					fileName = e.target.value.split('\\').pop();
				  }
				  if (fileName){
					var $fileName = $('#file_name');
					$("#file_name").val(fileName);
					//$fileName.html(fileName);
				  } else {
				   $("#file_name").val('');
				  }
				});
			  });
			});
			
			$('#btnEnviarArchivo').click(function(){
				//alert($("#archivo").val());
				idUsuario = $('#hdnIdUsuario').val();
				if($("#archivo").val() == ''){
					alertSelArchivo();
					$("#archivo").focus();
					return true;
				}
				if($("#txtInformacionArchivo").val() == ''){
					alertInfoArchivo();
					$("#txtInformacionArchivo").focus();
					return true;
				}
				$("#file_name").val('');
				//$("#divRespuesta").load("ajax/revisaArchivo.php",{archivo:archivo,idUsuario:idUsuario});
				
				$("#formuploadajax").submit();
			});
			/*******fin de documentos******/
			
			/***********inventario*********/
			$('#btnPenSel').click(function(){
				$("#menuFiltrosAprob").show();
				$("#lblExistencia").hide();
				$("#lblLoteExpirado").hide();
			});

			$('#btnInvSel').click(function(){
				$("#menuFiltrosAprob").hide();
				$("#lblExistencia").show();
				$("#lblLoteExpirado").show();
			});

			$('#btnPendienteAprobacion').click(function(){
				var repre = $("#hdnIdsFiltroUsuarios").val();
				var producto = $("#sltProductosInv").val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var idUsuario = $('#hdnIdUser').val();

				$('#btnPendienteAprobacion').addClass("seleccionado");
				$("#btnAceptadoInv").removeClass("seleccionado");
				$("#btnRechazadoInv").removeClass("seleccionado");

				$('#btnAceptadoInv').removeClass("btn-account-sel");
				$('#btnRechazadoInv').removeClass("btn-account-sel");
				$('#btnPendienteAprobacion').addClass("btn-account-sel");
				$("#btnAceptadoInv").removeAttr("disabled");
				$("#btnRechazadoInv").removeAttr("disabled");
				$("#btnPendienteAprobacion").attr("disabled", true);

				if($('#hdnNombresFiltroUsuarios').val() == ''){
					$("#btnEjecutarFiltroInv").click();
				}

				cargandoInventario3();

				$("#divRespuesta").load("ajax/cargarInventario.php",
					{
						pendiente:'0',
						idUsuario:idUsuario,
						repre:repre,
						producto:producto,
						tipoUsuario:tipoUsuario
					}, function()
					{
						$("#pendiente").waitMe("hide");
					}
				);
			});
			
			$('#btnAceptadoInv').click(function(){
				repre = $("#hdnIdsFiltroUsuarios").val();
				producto = $("#sltProductosInv").val();
				$('#btnPendienteAprobacion').removeClass("seleccionado");
				$("#btnAceptadoInv").addClass("seleccionado");
				$("#btnRechazadoInv").removeClass("seleccionado");

				$('#btnPendienteAprobacion').removeClass("btn-account-sel");
				$('#btnRechazadoInv').removeClass("btn-account-sel");
				$('#btnAceptadoInv').addClass("btn-account-sel");
				$("#btnPendienteAprobacion").removeAttr("disabled");
				$("#btnRechazadoInv").removeAttr("disabled");
				$("#btnAceptadoInv").attr("disabled", true);

				if($('#hdnNombresFiltroUsuarios').val() == ''){
					$("#btnEjecutarFiltroInv").click();
				}

				cargandoInventario3();
				$("#divRespuesta").load("ajax/cargarInventario.php",{pendiente:'1',idUsuario:$('#hdnIdUser').val(),repre:repre,producto:producto}, function(){
					if($('#hdnNombresFiltroUsuarios').val() != ''){
						$("#pendiente").waitMe("hide");
					}
				});
			});
			
			$('#btnRechazadoInv').click(function(){
				repre = $("#hdnIdsFiltroUsuarios").val();
				producto = $("#sltProductosInv").val();
				$('#btnPendienteAprobacion').removeClass("seleccionado");
				$("#btnAceptadoInv").removeClass("seleccionado");
				$("#btnRechazadoInv").addClass("seleccionado");
				
				$('#btnPendienteAprobacion').removeClass("btn-account-sel");
				$('#btnAceptadoInv').removeClass("btn-account-sel");
				$('#btnRechazadoInv').addClass("btn-account-sel");
				$("#btnPendienteAprobacion").removeAttr("disabled");
				$("#btnAceptadoInv").removeAttr("disabled");
				$("#btnRechazadoInv").attr("disabled", true);

				if($('#hdnNombresFiltroUsuarios').val() == ''){
					$("#btnEjecutarFiltroInv").click();
				}

				cargandoInventario3();
				$("#divRespuesta").load("ajax/cargarInventario.php",{pendiente:'2',idUsuario:$('#hdnIdUser').val(),repre:repre,producto:producto}, function(){
					if($('#hdnNombresFiltroUsuarios').val() != ''){
						$("#pendiente").waitMe("hide");
					}
				});
			});

			
			
			$('#btnAjustarInv').click(function(){
				//alert("kp3");
				var idProdForm = $("#hdnIdProductoAjuste").val();
				$("#divAjusteMuestra").show();
				$("#divCapa3").show('slow');
				$("#divRespuesta").load("ajax/cargarAjusteMaterial.php", {idProdForm:idProdForm});
			});
			
			$('#btnAceptarAjusteInv').click(function(){
				var idProdForm = $("#hdnIdProductoAjuste").val();
				$("#divRespuesta").load("ajax/guardaAjusteMaterial.php",{idProdForm:idProdForm,cantidad:'',catidadAceptada:'',motivo:'',movimiento:'aceptado'});
			});
			$('#btnCancelarAjusteInv').click(function(){
				$("#trMotivoRechazoMuestra").show('slow');
				$("#trBotonesRechazoMuestra").hide('slow');
			});
			
			$("#btnAceptarMuestra").click(function(){
				idProdForm = $("#hdnIdProdFormAjuste").val();
				cantidad = $("#hdnCantidadAjuste").val();
				catidadAceptada = $("#txtCantidadRecibida").val();
				motivo = $("#sltMotivo").val();
				if(catidadAceptada == ""){
					alertCantidadBlanco();
					return true;
				}
				//alert(catidadAceptada+' ::: '+cantidad);
				if(catidadAceptada > cantidad){
					alertCantidadMayor();
					return true;
				}
				//alert(motivo);
				if(motivo == '00000000-0000-0000-0000-000000000000'){
					alertIngresarMotivo();
					return true;
				}
				$("#divRespuesta").load("ajax/guardaAjusteMaterial.php",{idProdForm:idProdForm,cantidad:cantidad,catidadAceptada:catidadAceptada,motivo:motivo,movimiento:'noRecibido'});
				
			});
			
			$("#btnCancelarMuestra").click(function(){
				$("#divAjusteMuestra").hide();
				$("#over").hide();
				$("#fade").hide();
			});
			
			$("#btnAceptarMotivoRechazoMuestra").click(function(){
				var idProdForm = $("#hdnIdProductoAjuste").val();
				var motivo = $("#sltMotivoRechazoMuestra").val();
				if(motivo == '00000000-0000-0000-0000-000000000000'){
					alertIngresarMotivo();
					return true;
				}
				$("#divRespuesta").load("ajax/guardaAjusteMaterial.php",{idProdForm:idProdForm,cantidad:'',catidadAceptada:'',motivo:motivo,movimiento:'rechazado'});
			});
			
			$("#btnCancelarMotivoRechazoMuestra").click(function(){
				$("#trMotivoRechazoMuestra").hide();
				$("#trBotonesRechazoMuestra").show('slow');
				$("#divConfirmacionInventario").hide();
				$("#over").hide();
				$("#fade").hide();
			});
			
			$("#imgFiltrarAbrirInventario").click(function(){
				$("#imgFiltrarAbrirInventario").hide();
				$("#imgFiltrarCerrarInventario").show();
				$("#tblFiltrosInventario").show('slow');
			});
			
			$("#imgFiltrarCerrarInventario").click(function(){
				$("#imgFiltrarAbrirInventario").show();
				$("#imgFiltrarCerrarInventario").hide();
				$("#tblFiltrosInventario").hide('slow');
			});
			
			$("#btnEjecutarFiltroInv").click(function(){
				var idUsuario = $('#hdnIdUser').val();
				var pestana = $("#hdnPestana").val();
				var producto = $("#sltProductosInv").val();
				var repre = $("#hdnIdsFiltroUsuarios").val();
				var ids = $("#hdnIds").val();
				var pendiente = '';
				var tipoUsuario = $('#hdnTipoUsuario').val();

				if(pestana == 'aprobacion'){
					if($('#btnPendienteAprobacion').hasClass('seleccionado')){
						pendiente = 0;
						$('#btnPendienteAprobacion').addClass("seleccionado");
						$('#btnAceptadoInv').removeClass("seleccionado");
						$('#btnRechazadoInv').removeClass("seleccionado");
					}else if($('#btnAceptadoInv').hasClass('seleccionado')){
						pendiente = 1;
						$('#btnPendienteAprobacion').removeClass("seleccionado");
						$('#btnAceptadoInv').addClass("seleccionado");
						$('#btnRechazadoInv').removeClass("seleccionado");
					}else if($('#btnRechazadoInv').hasClass('seleccionado')){
						pendiente = 2;
						$('#btnPendienteAprobacion').removeClass("seleccionado");
						$('#btnAceptadoInv').removeClass("seleccionado");
						$('#btnRechazadoInv').addClass("seleccionado");
					}
					cargandoInventario3();
				}
				if($("#chkExistencia").is(":checked")){
					existencia = 1;
				}else{
					existencia = 0;
				}

				if($("#chkLoteExpirado").is(":checked")){
					expirado = 1;
				}else{
					expirado = 0;
				}

				if(pestana == 'inventario'){
					cargandoInventario();
				}
				
				$("#divRespuesta").load("ajax/cargarInventario.php",{pestana:pestana,producto:producto,repre:repre,ids:ids,pendiente:pendiente,idUsuario:idUsuario,existencia:existencia,tipoUsuario:tipoUsuario,expirado:expirado}, function(){
					$("#inventario").waitMe("hide");
					$("#pendiente").waitMe("hide");
				});
			});

			$('#btnLimpiarFiltroInv').click(function(){
				$('#sltMultiSelectInv').text('Seleccione');
				$('#sltProductosInv').val('');
				$("#btnQuitarSeleccion").click();
				$("#btnEjecutarFiltroInv").click();
			});
			
			$('#btnCerrarTransferirMuestraMedica').click(function(){
				$('#divTransferirMuestraMedica').hide();
				$('#divCapa3').hide();
			});
			
			$("#txtBuscarRepreTransferirMuestraMedica").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					var palabra = $("#txtBuscarRepreTransferirMuestraMedica").val();
					var ids = $("#hdnIds").val();
					$("#divRespuesta").load("ajax/cargarRepresMuestraMedica.php",{palabra:palabra,ids:ids});
				}
			});
			
			$("#txtCantidadTransferir").keyup(function(){
				var transferido = $("#txtCantidadTransferir").val();
				var disponible = $("#hdnPiezasDisponibles").val();
				var saldo = disponible - transferido;
				if(saldo < 0){
					transferidoDisponible();
				}else if(saldo == disponible){
					transferidoCero();
				}else{
					$('#lblRestanteMuestraMedica').text(saldo);
				}
			});
			
			$("#btnGuardarTransferirMuestraMedica").click(function(){
				var idUser = $('#hdnIdUser').val();
				var idStock = $('#hdnIdStockransferirMuestraMedica').val();
				var repreTransferir = $('#hdnIdRepreTransferirMuestraMedica').val();
				var repreTransferido = $('input:radio[name=optRepres]:checked').val();
				var transferido = $("#txtCantidadTransferir").val();
				var disponible = $("#hdnPiezasDisponibles").val();
				var saldo = disponible - transferido;
				
				if(! repreTransferido){
					alertWarning('Debe seleccionar al representante');
				}else{
					if(saldo < 0){
						transferidoDisponible();
					}else if(saldo == disponible){
						transferidoCero();
					}else{
						$("#divRespuesta").load("ajax/guardarTransferirMuestraMedica.php",
							{
								idUser:idUser,
								repreTransferir:repreTransferir,
								repreTransferido:repreTransferido,
								cantidad:transferido,
								idStock:idStock
							}
						);
					}
				}
			});
		
			/***********termina inventario*********/
			
			/*********reportes**********/
			
			$("#btnReporte").click(function(){
				reporte = $("#hdnReporte").val();
				fechaInicio = $("#txtFechaInicioReportes").val();
				fechaFin = $("#txtFechaFinReportes").val();
				estatus = $("#sltEsatusReportes").val();
				estatusI = $("#sltEsatusReportesI").val();
				periodo = $("#txtPeriodoReportes").val();
				productos = $("#hdnIdsFiltroProductos").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				ciclo = $("#sltCycleReportes").val();
				if(tipoUsuario == 4){
					ids = $("#hdnIdUser").val();
				}else{
					ids = $("#hdnIdsFiltroUsuarios").val();
				}
				if(ids == ''){
					alertSeleccionaRuta();
					return true;
				}
				$("#hdnFechaI").val(fechaInicio);
				$("#hdnFechaF").val(fechaFin);
				$("#hdnIDS").val(ids);
				$("#hdnEstatus").val(estatus);
				$("#hdnEstatusInst").val(estatusI);
				$("#hdnCicloReporte").val(ciclo);
				$("#hdnIdsProductos").val(productos);
				$("#lblLeyendaCargandoArchivo").text("Generando reporte, por favor espere...");
				$("#divCargando").show();
				$("#divReporteReporteador").empty();
				$("#divReporteReporteador").load("reportes/"+reporte,{hdnFechaI:fechaInicio,hdnFechaF:fechaFin,hdnEstatus:estatus,periodo:periodo,hdnIdsProductos:productos,hdnIDS:ids,hdnTipoReporte:'0',hdnEstatusInst:estatusI,hdnCicloReporte:ciclo});
				aparece("divReporteador");
			});
			
			$("#btnListado").click(function(){
				listado = $("#hdnListado").val();
				fechaInicio = $("#txtFechaInicioListados").val();
				fechaFin = $("#txtFechaFinListados").val();
				estatus = "";
				estatusI = "";
				periodo = $("#txtPeriodoListados").val();
				productos = $("#hdnIdsFiltroProductos").val();
				tipoUsuario = $("#hdnTipoUsuario").val();
				ciclo = $("#sltCycleListados").val();
				
				if(tipoUsuario == 4){
					ids = $("#hdnIdUser").val();
				}else{
					ids = $("#hdnIdsFiltroUsuarios").val();
				}
				if(ids == ''){
					alertSeleccionaRuta();
					return true;
				}

				for(i=1;i<=$("#hdnTotalChecksListadosPersonas").val();i++){
					if($("#listadosPersonas"+i).prop("checked")){
						estatus += $("#listadosPersonas"+i).val()+"','";
					}
				}
				if(estatus != ""){
					estatus = estatus.substring(0,estatus.length-3);
				}
				//////statusListadosInst
				for(i=1;i<=$("#hdnTotalChecksListadosInst").val();i++){
					if($("#listadosInst"+i).prop("checked")){
						estatusI += $("#listadosInst"+i).val()+"','";
					}
				}
				if(estatusI != ""){
					estatusI = estatusI.substring(0,estatusI.length-3);
				}
				//alert(ids);
				$("#hdnFechaIListado").val(fechaInicio);
				$("#hdnFechaFListado").val(fechaFin);
				$("#hdnIDSListado").val(ids);
				$("#hdnEstatusListado").val(estatus);
				$("#hdnEstatusInstListado").val(estatusI);
				$("#hdnCicloListado").val(ciclo);
				$("#hdnIdsProductosListado").val(productos);
				$("#lblLeyendaCargandoArchivo").text("Generando listado, por favor espere...");
				$("#divCargando").show();
				$("#divListado").empty();
				$("#divListado").load("reportes/"+listado,{hdnFechaIListado:fechaInicio,hdnFechaFListado:fechaFin,hdnEstatusListado:estatus,periodo:periodo,hdnIdsProductosListado:productos,hdnIDSListado:ids,hdnTipoListado:'0',hdnEstatusInstListado:estatusI,hdnCicloListado:ciclo});
				aparece("divListador");
			});
			
			$("#btnExportarReporteExcel").click(function(){
				$("#hdnTipoReporte").val('1');
				reporte = $("#hdnReporte").val();
				$('#formReportes').attr('action', "reportes/"+reporte).submit();
			});
			
			$("#btnExportarReporteExcelPlano").click(function(){
				$("#hdnTipoReporte").val('2');
				reporte = $("#hdnReporte").val();
				$('#formReportes').attr('action', "reportes/"+reporte).submit();
			});
			
			$("#btnExportarReportePDF").click(function(){
				$("#hdnTipoReporte").val('3');
				reporte = $("#hdnReporte").val();
				$('#formReportes').attr('action', "reportes/"+reporte).submit();
			});

			$("#btnExportarListadoExcel").click(function(){
				$("#hdnTipoListado").val('1');
				listado = $("#hdnListado").val();
				$('#formListados').attr('action', "reportes/"+listado).submit();
			});
			
			$("#btnExportarListadoExcelPlano").click(function(){
				$("#hdnTipoListado").val('2');
				listado = $("#hdnListado").val().split(".");
				$('#formListados').attr('action', "reportes/"+listado[0]+"Excel.php").submit();
			});
			
			$("#btnExportarListadoPDF").click(function(){
				$("#hdnTipoListado").val('3');
				listado = $("#hdnListado").val();
				$('#formListados').attr('action', "reportes/"+listado).submit();
			});
			
			$('#btnCerrarReporte').click(function(){
				$('#imgReportes').click();
			});

			$('#btnCerrarListado').click(function(){
				$('#imgListados').click();
			});
			
			$('#txtFechaInicioListados').change(function(){
				cambiarFecha('txtFechaInicioListados');
			});
			
		
			$('#txtFechaFinListados').change(function(){
				cambiarFecha('txtFechaFinListados');
			});
			
			$('#txtFechaInicioReportes').change(function(){
				cambiarFecha('txtFechaInicioReportes');
			});
			
			$('#txtFechaFinReportes').change(function(){
				cambiarFecha('txtFechaFinReportes');
			});
			
			$('#sltProductoReportes').click(function(){
				idsFiltros = $('#hdnIdsFiltroProductos').val();
				$('#divFiltrosProductos').show();
				$('#divCapa3').show();
				$('#divRespuesta').load("ajax/cargarFiltroProductos.php",{palabra:'',idsFiltros:idsFiltros});
			});

			/*******termina reportes****/
			
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
			
			/*$('#imgBuscarInstitucion').click(function(){
				if($('#trSubmenuInstituciones').is (':visible')){
					$('#trSubmenuInstituciones').hide('slow');
				}else{
					$('#trSubmenuInstituciones').show('slow');
				}
			});
			
			$('#imgCerrarInstituciones').click(function(){
				$('#trSubmenuInstituciones').hide('slow');
			});*/
			$('#imgFlechaAtras').click(function(){
				$('#divPersonasDatos').hide();
				$('#divPersonas').show();
			});
			
			$('#imgFiltrar').click(function(){
				$('#trFiltros').show();
			});
			
			$('#cerrarInformacion').click(function(){
				$('#over').hide();
				$('#over2').hide();
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
				$('#divCopiarPlanes').hide();
				$('#divRutaNueva').hide();
				$('#divPlanesRapidos').hide();
				$('#divMotivoBaja').hide();
				$('#divMotivoBajaInst').hide();
				$('#divTransferirMuestraMedica').hide();

				//alert(isMedorInst);
				if(isMedorInst == 0){
					$('#' + idmedico).click();
					$('#' + idTrMedico).addClass('div-slt-lista');
				}
				if(isMedorInst == 1){
					$('#' + idInstTabs).click();
					$('#' + idTrInst).addClass('div-slt-lista');
				}
			});

			$('#cerrarInfoInst').click(function(){
				$('#' + idInstTabs).click();
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
			$('#imgAgregarMensaje').click(function(){
				var idUser = $('#hdnIdUser').val();
				$('#divMensaje').show();
				$('#divCapa3').show();
				//$("#divRespuesta").load("ajax/cargarMensajeNuevo.php",{idUser:idUser});
			});
			
			$('#btnEnviarMensaje').click(function(){
				var idUser = $('#hdnIdUser').val();
				var para = $('#hdnIdsFiltroUsuariosMensajes').val();
				var asunto = $('#txtAsuntoMensaje').val();
				var mensaje = $('#txtMensaje').val();
				//alert(para);
				$("#divRespuesta").load("ajax/enviarMensaje.php",{
					idUser:idUser,
					para:para,
					asunto:asunto,
					mensaje:mensaje});
			});
			
			$('#btnCancelarMensaje').click(function(){
				$('#divMensaje').hide();
				$('#divCapa3').hide();
			});
			
			$('#tabEntradaCabecera').click(function(){
				var idUser = $('#hdnIdUser').val();
				var tabMsj = 1; //entrada
				$('#tbMensajesEntrada').load("ajax/cargarMensajes.php",{idUser:idUser,tabMsj:tabMsj},function(){
					aparece('divMensajes');
				});
			});
			
			$('#tabTodosCabecera').click(function(){
				var idUser = $('#hdnIdUser').val();
				var tabMsj = 2; //todos
				$('#tbMensajesTodos').load("ajax/cargarMensajes.php",{idUser:idUser,tabMsj:tabMsj},function(){
					aparece('divMensajes');
				});
			});
			
			$('#tabEnviadosCabecera').click(function(){
				var idUser = $('#hdnIdUser').val();
				var tabMsj = 3; //entrada
				$('#tbMensajesEnviados').load("ajax/cargarMensajes.php",{idUser:idUser,tabMsj:tabMsj},function(){
					aparece('divMensajes');
				});
			});
			
			/**********filtro mensaje ***************/
			
			$('#txtBuscarFiltroUsuariosMensaje').keyup(function() {
				idRemitente = $("#hdnIdUser").val();
				palabra = $("#txtBuscarFiltroUsuariosMensaje").val();
				idsFiltros = $("#hdnIdsFiltroUsuariosMensajes").val();
				$("#divRespuesta").load("ajax/cargarFiltroMensaje.php",{idRemitente:idRemitente,palabra:palabra,idsFiltros:idsFiltros});
			});
			
			$('#btnSeleccionarTodosMensaje').click(function(){
				$('#btnQuitarSeleccionMensaje').click();
				var idRemitente = $('#hdnIdUser').val();
				var palabra = $('#txtBuscarFiltroUsuariosMensaje').val();
				$('#divRespuesta').load("ajax/cargarFiltroMensaje.php",{idRemitente:idRemitente,palabra:palabra,idsFiltros:'',seleccionarTodo:'si'});
			});
			
			$('#btnQuitarSeleccionMensaje').click(function(){
				var idRemitente = $('#hdnIdUser').val();
				var palabra = $("#txtBuscarFiltroUsuariosMensaje").val();
				$('#tblUsuariosSeleccionadosFiltrosMensajes').empty();
				$('#hdnIdsFiltroUsuariosMensajes').val('');
				$('#hdnNombresFiltroUsuariosMensaje').val('');
				$('#divRespuesta').load("ajax/cargarFiltroMensaje.php",{idRemitente:idRemitente,palabra:palabra,idsFiltros:''});
			});
			
			$('#btnEjecutarFiltroMensaje').click(function(){
				$('#sltMultiSelectMensajes').text($('#hdnNombresFiltroUsuariosMensaje').val());
				$('#divFiltrosMensaje').hide();
				$('#divCapa4').hide();
			});
			
			$('#btnCancelarFiltroMensaje').click(function(){
				$('#divFiltrosMensaje').hide();
				$('#divCapa4').hide();
			});
			
			$('#sltMultiSelectMensajes').click(function(){
				var idRemitente = $('#hdnIdUser').val();
				$('#divFiltrosMensaje').show();
				$('#divCapa4').show();
				$('#divRespuesta').load("ajax/cargarFiltroMensaje.php",{palabra:'',idRemitente:idRemitente});
			});
			
			/**********termina filtro mensajes *******/
			
			/*$('#imgApareceBuscar').click(function(){
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
			});*/
			/*fin mensajes*/
			
			/*aprobaciones*/
			$('#btnAceptarAprobacion').click(function(){
				$('#btnAceptarAprobacion').prop("disabled", true);
				idUser = $('#hdnIdUser').val();
				idPersApproval = $('#hdnIdPersApproval').val();
				idInstApproval = $('#hdnIdInstApproval').val();
				if(idPersApproval != ''){
					$("#divRespuesta").load("ajax/persAprobacion.php",{idPersApproval:idPersApproval,idUser:idUser},function(){
						alertMovimientoAprobado();
					});
				}else if(idInstApproval != ''){
					$("#divRespuesta").load("ajax/instAprobacion.php",{idInstApproval:idInstApproval,idUser:idUser},function(){
						alertMovimientoAprobado();
					});
				}
			});
			
			$('#btnRechazarAprobacion').click(function(){
				$("#divMotivoRechazo").show('slow');
				//$('#imgAprobaciones').click();
				$('#btnAceptarAprobacion').prop("disabled", true);
				$('#btnRechazarAprobacion').prop("disabled", true);
				$('#btnCancelarAprobacion').prop("disabled", true);
			});
			
			$("#btnAceptarRechazo").click(function(){
				var motivo = $("#sltMotivoRechazo").val();
				idPersApproval = $('#hdnIdPersApproval').val();
				idInstApproval = $('#hdnIdInstApproval').val();
				idUser = $('#hdnIdUser').val();
				if(motivo == '00000000-0000-0000-0000-000000000000'){
					alertIngresarMotivo();
					return true;
				}
				if(idPersApproval != ''){
					tabla = 'personas';
				}else if(idInstApproval != ''){
					tabla = 'inst';
				}
				$("#divRespuesta").load("ajax/rechazoAprobacion.php",{idPersApproval:idPersApproval,idInstApproval:idInstApproval,motivo:motivo,idUsuario:idUser,tabla:tabla});
				$("#divMotivoRechazo").hide();
				$('#btnAceptarAprobacion').prop("disabled", false);
				$('#btnRechazarAprobacion').prop("disabled", false);
				$('#btnCancelarAprobacion').prop("disabled", false);
				$("#cerrarInformacion").click();
				$('#btnPendienteAprobacionesGerentePers').click();
				
				/*$("#btnPendienteAprobacionesGerentePers").addClass("seleccionado");
				$("#btnAceptadoAprobacionesGerentePers").removeClass("seleccionado");
				$("#btnRechazadoAprobacionesGerentePers").removeClass("seleccionado");

				$('#btnAceptadoAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnRechazadoAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnPendienteAprobacionesGerentePers').addClass("btn-account-sel");
				$("#btnAceptadoAprobacionesGerentePers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesGerentePers").removeAttr("disabled");
				$("#btnPendienteAprobacionesGerentePers").attr("disabled", true);*/
						alertMovimientoAprobado();
			});
			
			$("#btnCancelarRechazo").click(function(){
				$("#sltMotivoRechazo").val('');
				$('#btnAceptarAprobacion').prop("disabled", false);
				$('#btnRechazarAprobacion').prop("disabled", false);
				$('#btnCancelarAprobacion').prop("disabled", false);
				$("#divMotivoRechazo").hide('slow');
				
			});
			
			$("#sltRutasAprobacionesGerentePers").change(function (){
				ids = $('#hdnIds').val();
				ruta = $("#sltRutasAprobacionesGerentePers").val();
				tipoMovimiento = $("#sltTipoMovimientoAprobacionesGte").val();
				estatus = '';
				if($("#btnPendienteAprobacionesGerentePers").hasClass('seleccionado')){
					estatus = '1';
				}else if($("#btnAceptadoAprobacionesGerentePers").hasClass('seleccionado')){
					estatus = '2';
				}else if($("#btnRechazadoAprobacionesGerentePers").hasClass('seleccionado')){
					estatus = '3';
				}else{
					estatus = '1';
				}
				
				$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:estatus,tipoMovimiento:tipoMovimiento});
			});

			$("#btnPendienteAprobacionesGerentePers").click(function(){
				ids = $('#hdnIds').val();
				ruta = $("#sltRutasAprobacionesGerentePers").val();
				tipoMovimiento = $("#sltTipoMovimientoAprobacionesGte").val();
				$("#btnPendienteAprobacionesGerentePers").addClass("seleccionado");
				$("#btnAceptadoAprobacionesGerentePers").removeClass("seleccionado");
				$("#btnRechazadoAprobacionesGerentePers").removeClass("seleccionado");

				$('#btnAceptadoAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnRechazadoAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnPendienteAprobacionesGerentePers').addClass("btn-account-sel");
				$("#btnAceptadoAprobacionesGerentePers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesGerentePers").removeAttr("disabled");
				$("#btnPendienteAprobacionesGerentePers").attr("disabled", true);

				cargandoAprobaciones();
				$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:'1',tipoMovimiento:tipoMovimiento}, function(){
					$("#tblAprobacionesPersGerente").waitMe("hide");
					$('#tblAprobacionesInstGerente').waitMe("hide");
				});
			});
			$("#btnAceptadoAprobacionesGerentePers").click(function(){
				ids = $('#hdnIds').val();
				ruta = $("#sltRutasAprobacionesGerentePers").val();
				tipoMovimiento = $("#sltTipoMovimientoAprobacionesGte").val();
				$("#btnPendienteAprobacionesGerentePers").removeClass("seleccionado");
				$("#btnAceptadoAprobacionesGerentePers").addClass("seleccionado");
				$("#btnRechazadoAprobacionesGerentePers").removeClass("seleccionado");

				$('#btnPendienteAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnRechazadoAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnAceptadoAprobacionesGerentePers').addClass("btn-account-sel");
				$("#btnPendienteAprobacionesGerentePers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesGerentePers").removeAttr("disabled");
				$("#btnAceptadoAprobacionesGerentePers").attr("disabled", true);

				cargandoAprobaciones();
				$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:'2',tipoMovimiento:tipoMovimiento}, function(){
					$("#tblAprobacionesPersGerente").waitMe("hide");
					$('#tblAprobacionesInstGerente').waitMe("hide");
				});
			});
			$("#btnRechazadoAprobacionesGerentePers").click(function(){
				ids = $('#hdnIds').val();
				ruta = $("#sltRutasAprobacionesGerentePers").val();
				tipoMovimiento = $("#sltTipoMovimientoAprobacionesGte").val();
				$("#btnPendienteAprobacionesGerentePers").removeClass("seleccionado");
				$("#btnAceptadoAprobacionesGerentePers").removeClass("seleccionado");
				$("#btnRechazadoAprobacionesGerentePers").addClass("seleccionado");

				$('#btnPendienteAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnAceptadoAprobacionesGerentePers').removeClass("btn-account-sel");
				$('#btnRechazadoAprobacionesGerentePers').addClass("btn-account-sel");
				$("#btnPendienteAprobacionesGerentePers").removeAttr("disabled");
				$("#btnAceptadoAprobacionesGerentePers").removeAttr("disabled");
				$("#btnRechazadoAprobacionesGerentePers").attr("disabled", true);

				cargandoAprobaciones();
				$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:'3',tipoMovimiento:tipoMovimiento}, function(){
					$("#tblAprobacionesPersGerente").waitMe("hide");
					$('#tblAprobacionesInstGerente').waitMe("hide");
				});
			});
			
			$("#sltTipoMovimientoAprobacionesGte").change(function(){
				ids = $('#hdnIds').val();
				ruta = $("#sltRutasAprobacionesGerentePers").val();
				tipoMovimiento = $("#sltTipoMovimientoAprobacionesGte").val();
				estatus = '';
				if($("#btnPendienteAprobacionesGerentePers").hasClass('seleccionado')){
					estatus = '1';
				}else if($("#btnAceptadoAprobacionesGerentePers").hasClass('seleccionado')){
					estatus = '2';
				}else if($("#btnRechazadoAprobacionesGerentePers").hasClass('seleccionado')){
					estatus = '3';
				}else{
					estatus = '1';
				}
				
				$('#divRespuesta').load('ajax/cargarAprobacionesGerente.php',{ids:ids,ruta:ruta,estatus:estatus,tipoMovimiento:tipoMovimiento});
			});
			
			/*if($('#btnPlanCalendario').hasClass('seleccionado')){
				planVisita = 'plan';
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				planVisita = 'visita';
			}*/
			
			$('#sltTipoMovimientoAprobacionesPers').change(function(){
				var idUsuario = $('#hdnIdUser').val();
				tipo = $('#sltTipoMovimientoAprobacionesPers').val();
				if($('#btnPendienteAprobacionesPers').hasClass("seleccionado")){
					estatus = 1;
				}else if($("#btnAceptadoAprobacionesPers").hasClass("seleccionado")){
					estatus = 2;
				}else{
					estatus = 3;
				}
				$('#divRespuesta').load("ajax/cargarAprobaciones.php",{idUsuario:idUsuario,estatus:estatus,tipo:tipo});
			});
			
			/* fin aprobaciones */

			var map = new google.maps.Map(document.getElementById('map_canvas'), {
				zoom: 14
			});

			var marker;
			
			$('#lkMapa').click(function(){
				//load_map('map_canvas');
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","") * 1;
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","") * 1;
				
				var myLatLng = {lat: lat, lng: lon};
				
				map.setCenter(myLatLng);

				if(marker != null){
					marker.setPosition(null);
				}else{
					marker=new google.maps.Marker();
				}
				marker.setPosition(myLatLng);
				marker.setMap(map);
				
				$('#txtCanvas').val('map_canvas');
			});

			var mapInst = new google.maps.Map(document.getElementById('canvasInstitucion'), {
				zoom: 14
			});

			var markerInst;
			
			$('#lkMapaInstituciones').click(function(){
				//load_map('map_canvas2');
				var latInst = $('#txtLatitudInstituciones').val()*1;
				var lonInst = $('#txtLongitudInstituciones').val()*1;
				
				var myLatLngInst = {lat: latInst, lng: lonInst};
				
				mapInst.setCenter(myLatLngInst);

				if(markerInst != null){
					markerInst.setPosition(null);
				}else{
					markerInst=new google.maps.Marker();
				}
				markerInst.setPosition(myLatLngInst);
				markerInst.setMap(mapInst);

				$('#txtCanvas').val('canvasInstitucion');
			});
			
			/*$('#lkVisitas').click(function(){
				if($('#lblLatitudPersonas').text() == '' && $('#lblLongitudPersonas').text() == ''){
					load_map('map_canvas2');
					$('#txtCanvas').val('map_canvas2');
				}
			});*/
			
			$('#imgLogout').click(function(){
				///$('#divRespuesta').load("ajax/cerrarSesion.php",{idUL:<?= $idUL ?>});
				$('#divRespuesta').load("ajax/cerrarSesion.php",{idUL:'<?= $idUL ?>'});
				
			});
			
			
			/*configuracion*/
			$('#editarPass').click(function(){
				$('#muestraCambiarPassword').hide('slow');
				$('#cambiarPassword').show('slow');
				$('#seguridad').show();
			});
			
			$('#btnGuardarClave').click(function(){
				var pass = $('#txtActualClave').val();
				var clave = $('#txtClave').val();
				var repClave = $('#txtRepetirClave').val();
				var idUsuario = $('#hdnIdUser').val();
				if(clave != repClave){
					alertPassError();
					return;
				}
				if(pass == ''){
					alertPassActualError();
					return;
				}
				alertCambiarPass(pass, clave, idUsuario);
			});
			
			$('#btnCancelarClave').click(function(){
				$('#txtClave').val('');
				$('#txtRepetirClave').val('');
				$('#seguridad').hide();
				$('#seguridad').val('');
				$('#txtActualClave').val('');
				$('#muestraCambiarPassword').show('slow');
				$('#cambiarPassword').hide('slow');
			});
			
			/*fin de configuracion*/
			
			/******* comienza filtros de usuario ******/
			
			$('#txtBuscarFiltroUsuarios').keyup(function() {
				palabra = $("#txtBuscarFiltroUsuarios").val();
				ids = $("#hdnIds").val();
				idsFiltros = $("#hdnIdsFiltroUsuarios").val();
				$("#divRespuesta").load("ajax/cargarFiltroUsuarios.php",{palabra:palabra,ids:ids,idsFiltros:idsFiltros});
			});
			
			$('#btnSeleccionarTodos').click(function(){
				var ids = $('#hdnIds').val();
				var palabra = $('#txtBuscarFiltroUsuarios').val();
				$('#btnQuitarSeleccion').click();
				$('#divRespuesta').load("ajax/cargarFiltroUsuarios.php",{ids:ids,palabra:palabra,idsFiltros:'',seleccionarTodo:'si'});
			
			});
			
			$('#btnQuitarSeleccion').click(function(){
				var palabra = $("#txtBuscarFiltroUsuarios").val();
				var ids = $("#hdnIds").val();
				$('#tblUsuariosSeleccionados').empty();
				$('#hdnIdsFiltroUsuarios').val('');
				$('#hdnNombresFiltroUsuarios').val('');
				$('#divRespuesta').load("ajax/cargarFiltroUsuarios.php",{ids:ids,palabra:palabra,idsFiltros:''});
			});
			
			$('#btnEjecutarFiltroUsuarios').click(function(){
				var pantalla = $('#hdnPantallaFiltroUsuarios').val();
				if(pantalla == 'personas'){
					$('#sltMultiSelectPersonas').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'inst'){
					$('#sltMultiSelectInst').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'cal'){
					$('#sltMultiSelectCal').text($('#hdnNombresFiltroUsuarios').val());
					
					if($('#btnPlanCalendario').hasClass('seleccionado')){
						$('#btnPlanCalendario').click();
					}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
						$('#btnVisitaCalendario').click();
					}
				}else if(pantalla == 'geo'){
					$('#sltMultiSelectGeo').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'docs'){
					$('#sltMultiSelectDocs').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'inv'){
					$('#sltMultiSelectInv').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'copiarPlanes'){
					$('#sltMultiSelectCopiarPlanes').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'oa'){
					$('#sltMultiSelectOA').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'reportes'){
					$('#sltMultiSelectReportes').text($('#hdnNombresFiltroUsuarios').val());
				}else if(pantalla == 'listados'){
					$('#sltMultiSelectListados').text($('#hdnNombresFiltroUsuarios').val());
				}
				$('#divFiltrosUsuarios').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnCancelarFiltro').click(function(){
				$('#divFiltrosUsuarios').hide();
				$('#divCapa3').hide();
				//$('body').removeClass('no-scroll');
				$("#btnEjecutarFiltroUsuarios").click();
			});


			$('#btnEjecutarFiltroUsuarios').click(function(){
				/*var today = new Date();
				var month = today.getMonth() + 1;
				var year = today.getFullYear();

				if (month < 10) {
					month = '0' + month;
				}*/
				//$('body').removeClass('no-scroll');
				var month = $('#calendar_mes').val();
				var year = $('#calendar_anio').val();
				update_calendar3(month, year);
				cargandoCalendario3();
			});
			
			/*********Termina filtros de usuario ******/
			
			/**********filtro productos ***************/
			
			$('#txtBuscarFiltroProductos').keyup(function() {
				palabra = $("#txtBuscarFiltroProductos").val();
				//ids = $("#hdnIds").val();
				idsFiltros = $("#hdnIdsFiltroProductos").val();
				$("#divRespuesta").load("ajax/cargarFiltroProductos.php",{palabra:palabra,idsFiltros:idsFiltros});
			});
			
			$('#btnSeleccionarTodosProductos').click(function(){
				//var ids = $('#hdnIds').val();
				var palabra = $('#txtBuscarFiltroProductos').val();
				$('#divRespuesta').load("ajax/cargarFiltroProductos.php",{palabra:palabra,idsFiltros:'',seleccionarTodo:'si'});
			});
			
			$('#btnQuitarSeleccionProductos').click(function(){
				var palabra = $("#txtBuscarFiltroProductos").val();
				//var ids = $("#hdnIds").val();
				$('#tblProductosSeleccionadosFiltros').empty();
				$('#hdnIdsFiltroProductos').val('');
				$('#hdnNombresFiltroProductos').val('');
				$('#divRespuesta').load("ajax/cargarFiltroProductos.php",{palabra:palabra,idsFiltros:''});
			});
			
			$('#btnEjecutarFiltroProductos').click(function(){
				//alert($('#divListados').is (':visible'));
				if($('#divListados').is (':visible')){
					//alert($('#hdnNombresFiltroProductos').val());
					$('#sltProductoListados').text($('#hdnNombresFiltroProductos').val());
				}
				/*if($('#divReportes').is (':visible')){
					$('#sltProductoReportes').text($('#hdnNombresFiltroProductos').val());
				}else if($('#divListados').is (':visible')){{
					$('#sltProductoListados').text($('#hdnNombresFiltroProductos').val());
				}*/
				$('#divFiltrosProductos').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnCancelarFiltroProductos').click(function(){
				$('#divFiltrosProductos').hide();
				$('#divCapa3').hide();
			});
			
			/**********termina filtro productos *******/
			
			/**********filtro ciclos ***************/
			
			/*$('#txtBuscarFiltroCiclos').keyup(function() {
				palabra = $("#txtBuscarFiltroCiclos").val();
				//ids = $("#hdnIds").val();
				idsFiltros = $("#hdnIdsFiltroCiclos").val();
				$("#divRespuesta").load("ajax/cargarFiltroCiclos.php",{palabra:palabra,idsFiltros:idsFiltros});
			});
			
			$('#btnSeleccionarTodosCiclos').click(function(){
				//var ids = $('#hdnIds').val();
				var palabra = $('#txtBuscarFiltroCiclos').val();
				$('#divRespuesta').load("ajax/cargarFiltroCiclos.php",{palabra:palabra,idsFiltros:'',seleccionarTodo:'si'});
			});
			
			$('#btnQuitarSeleccionCiclos').click(function(){
				var palabra = $("#txtBuscarFiltroCiclos").val();
				//var ids = $("#hdnIds").val();
				$('#tblCiclosSeleccionados').empty();
				$('#hdnIdsFiltroCiclos').val('');
				$('#hdnNombresFiltroCiclos').val('');
				$('#divRespuesta').load("ajax/cargarFiltroCiclos.php",{palabra:palabra,idsFiltros:''});
			});
			
			$('#btnEjecutarFiltroCiclos').click(function(){
				$('#sltPeriodoReportes').text($('#hdnNombresFiltroCiclos').val());
				$('#divFiltrosCiclos').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnCancelarFiltroCiclos').click(function(){
				//alert('hola');
				$('#divFiltrosCiclos').hide();
				$('#divCapa3').hide();
			});*/
			
			/**********termina filtro ciclos *******/
			/***********eventos**************/
			
			$('#imgAgregarEvento').click(function(){
				var idUsuario = $('#hdnIdUser').val();
				$('#divEventoEditar').show();
				$('#divCapa3').show('slow');
				$("#divRespuesta").load("ajax/cargarEvento.php",{idUsuario:idUsuario,idEvento:'',renglonSeleccionado:''});
			});
			
			$('#btnCancelarEventoEditar').click(function(){
				$('#divEventoEditar').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnBuscarEventoEditar').click(function(){
				var ids = $('#hdnIds').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var idUsuario = $('#hdnIdUser').val();
				$('#divBuscarPersonasEventos').show();
				$('#divCapa4').show('slow');
				$("#txtBuscarPersonaEvento").val('');
				cargandoBuscadorMed();
				$("#divRespuesta").load("ajax/persFiltradasEventos.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario}, function(){
					$('#tblBuscarPersonasEventos').waitMe('hide');
				});
			
			});
			
			$('#btnAgregarInvitadosEventoEditar').click(function(){
				invitados = $('#hdnIvitadosEventos').val();
				idEvento = $('#hdnIdEventoEditar').val();
				$('#divBuscarPersonasEventos').hide();
				$('#divCapa4').hide();
				$("#divRespuesta").load("ajax/agregarInvitadosEventos.php",{invitados:invitados,idEvento:idEvento});
			});
			
			$('#btnCancelarBuscarPersonaEvento').click(function(){
				$('#divBuscarPersonasEventos').hide();
				$('#divCapa4').hide();
			});
			
			$('#btnGuardarEventoEditar').click(function(){
				$('#btnGuardarEventoEditar').attr('disabled', true);
				var renglonSeleccionado = $('#hdnTrSeleccionadaEvento').val();
				var idUsuario = $('#hdnIdUser').val();
				var idEvento = $('#hdnIdEventoEditar').val();
				var idUsuarioEvento = $('#sltRepreEventoEditar').val();
				var tipoEvento = $('#lstTipoEventoEditar').val();
				var lugarEvento = $('#txtLugarEventoEditar').val();
				var nombreEvento = $('#txtNombreEventoEditar').val();
				var fechaInicial = new Date($('#txtFechaInicialEventoEditar').val());
				var fechaInicialChar = $('#txtFechaInicialEventoEditar').val();
				var horaInicial = $('#lstHoraInicialEventoEditar').val() + ":" + $('#lstMinutosInicialEventoEditar').val();
				var fechaFinal = new Date($('#txtFechaFinalEventoEditar').val());
				var fechaFinalChar = $('#txtFechaFinalEventoEditar').val();
				var horaFinal = $('#lstHoraFinalEventoEditar').val() + ":" + $('#lstMinutosFinalEventoEditar').val();
				var tipoParticipacion = $('#lstTipoParticipacionEventoEditar').val();
				var numeroParticipantes = $('#txtNumeroParticipantesEventoEditar').val();
				var especialidadEvento = $('#lstEspecialidadEventoEditar').val();
				var grupoTerapeutico = $('#lstGrupoTerapeuticoEventoEditar').val();
				var comentarios = $('#txtComentariosEventoEditar').val();
				var invitados = $('#hdnIvitadosEventos').val();
				if(idUsuarioEvento == '00000000-0000-0000-0000-000000000000' || idUsuarioEvento == ''){
					alertEvento('Seleccione el Representante');
					return true;
				}
				
				if(tipoEvento == '00000000-0000-0000-0000-000000000000'){
					$('#btnGuardarEventoEditar').attr('disabled', false);
					alertEvento('Seleccione el tipo de evento');
					return true;
				}
				
				if(lugarEvento == ''){
					$('#btnGuardarEventoEditar').attr('disabled', false);
					alertEvento('Ingrese el lugar del evento');
					return true;
				}
				
				if(nombreEvento == ''){
					$('#btnGuardarEventoEditar').attr('disabled', false);
					alertEvento('Ingrese el nombre del evento');
					return true;
				}
				
				if(fechaFinal < fechaInicial){
					$('#btnGuardarEventoEditar').attr('disabled', false);
					alertEvento('La fecha final no puede ser menor a la fecha inicial');
					return true;
				}
				
				if(fechaInicial.getDate() === fechaFinal.getDate()){
					if(horaFinal < horaInicial){
						$('#btnGuardarEventoEditar').attr('disabled', false);
						alertEvento('La hora final no puede ser menor a la hora inicial');
						return true;
					}
				}
				
				if(tipoParticipacion == '00000000-0000-0000-0000-000000000000'){
					$('#btnGuardarEventoEditar').attr('disabled', false);
					alertEvento('Seleccione el tipo de participacón');
					return true;
				}
				
				if(numeroParticipantes == '' || numeroParticipantes == 0){
					$('#btnGuardarEventoEditar').attr('disabled', false);
					alertEvento('Ingrese el número de participantes');
					return true;
				}
				
				$("#divRespuesta").load("ajax/guardarEvento.php",{idEvento:idEvento,idUsuarioEvento:idUsuarioEvento,tipoEvento:tipoEvento,lugarEvento:lugarEvento,nombreEvento:nombreEvento,fechaInicial:fechaInicialChar,horaInicial:horaInicial,fechaFinal:fechaFinalChar,horaFinal:horaFinal,tipoParticipacion:tipoParticipacion,numeroParticipantes:numeroParticipantes,especialidadEvento:especialidadEvento,grupoTerapeutico:grupoTerapeutico,comentarios:comentarios,idUser:idUsuario,invitados:invitados,renglonSeleccionado:renglonSeleccionado});
				
			});
			
			$("#imgAgregarEventoPerfil").click(function(){
				idPersona = $("#hdnIdPersona").val();
				$("#divEventosAgregar").show();
				$("#divCapa3").show('slow');
				$("#divRespuesta").load("ajax/cargarEventosAgregar.php", {idPersona: idPersona});
			});
			
			$("#btnGuardarEventoAgregar").click(function(){
				idEvento = $("#hdnIdEventoAgregar").val();
				idPersona = $("#hdnIdPersona").val();
				idUsuario = $("#hdnIdUser").val();
				$("#divRespuesta").load("ajax/guardarEventoAgregar.php", {idPersona:idPersona, idEvento:idEvento, idUsuario:idUsuario});
			});
			
			$("#btnCancelarEventoAgregar").click(function(){
				$("#divEventosAgregar").hide();
				$("#divCapa3").hide();
			});
			
			$("#tblEventosAgregar tbody tr").click(function(e){
				/*$('#tblEventosAgregar tbody tr').removeClass('seleccionado');
				$(this).addClass('seleccionado');*/
				alert($(this).rowIndex());
			});
			
			$("#txtBuscarPersonaEventos").keypress(function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code==13){
					palabra = $("#txtBuscarPersonaEventos").val();
					idUsuario = $("#hdnIdUser").val();
					ids = $("#hdnIds").val();
					tipoUsuario = $("#hdnTipoUsuario").val();

					cargandoBuscadorMedSearch();
					$("#divRespuesta").load("ajax/persFiltradasEventos.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:palabra,idUsuario:idUsuario}, function(){
						$('.cargaBusquedaMed').waitMe('hide');
					});
				}
			});
			
			$('#btnBuscarMedEvento').click(function(){
				var ids = $('#hdnIds').val();
				var tipoUsuario = $('#hdnTipoUsuario').val();
				var idUsuario = $('#hdnIdUser').val();
				var palabra = $("#txtBuscarPersonaEventos").val();
				cargandoBuscadorMed();
				$("#divRespuesta").load("ajax/persFiltradasEventos.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:palabra,idUsuario:idUsuario}, function(){
					$('#tblBuscarPersonasEventos').waitMe('hide');
				});
			
			});
			
			/***********termina Eventos*******/
			
			/*************inversiones*********/
			$("#btnGuardarInversion").click(function(){
				var idInversion = $('#hdnIdInversion').val();
				var idUsuario = $('#hdnIdUser').val();
				var idPersona = $('#hdnIdPersona').val();
				var concepto = $('#txtConceptoInversion').val();
				var codigoInversion = $('#lstCodigoInversion').val();
				var fechaInversion = $('#txtFechaInversion').val();
				var cantidadInversion = $('#txtCantidadInversion').val();
				var comentarios = $('#objetivoInversion').val();
				var pantalla = '';
				
				var productos = '';
				for(i=0;i<= $("#hdnTotalChecksInversiones").val(); i++){
					if($("#inversion" + i).prop('checked')){
						productos += $('#inversion'+i).val() + ";";
					}
				}
				productos = productos.slice(0, -1);

				if(concepto == ''){
					alertConceptoInversiones();
					return true;
				}
				
				if($('#divPersonas').is (':visible')){
					pantalla = 'personas';
				}else if($('#divInversionesLista').is (':visible')){
					pantalla = 'inversiones';
				}
				
				$("#divRespuesta").load("ajax/guardarInversion.php",{
					idInversion:idInversion,
					idUsuario:idUsuario,
					idPersona:idPersona,
					concepto:concepto,
					codigoInversion:codigoInversion,
					producto:productos,
					fechaInversion:fechaInversion,
					cantidadInversion:cantidadInversion,
					comentarios:comentarios,
					pantalla:pantalla},function(){
						$("#btnActualizarPers").click();
					});
			});
			
			$("#btnCancelarInversion").click(function(){
				$("#divInversiones").hide();
				$("#divCapa3").hide();
			});
			
			$("#btnEliminarInversionPerson").click(function(){
				$idInversion = $("#hdnIdInversion").val();
				alertEliminarInversion($idInversion);
			});
			/***********termina inversiones*********/
				/**********filtro mercados**************/
				$('#txtBuscarFiltroMercados').keyup(function() {
				palabra = $("#txtBuscarFiltroMercados").val();
				//ids = $("#hdnIds").val();
				idsFiltros = $("#hdnIdsFiltroMercados").val();
				$("#divRespuesta").load("ajax/cargarFiltroMercados.php",{palabra:palabra,idsFiltros:idsFiltros});
			});
			
			$('#btnSeleccionarTodosMercados').click(function(){
				//var ids = $('#hdnIds').val();
				var palabra = $('#txtBuscarFiltroMercados').val();
				$('#divRespuesta').load("ajax/cargarFiltroMercados.php",{palabra:palabra,idsFiltros:'',seleccionarTodo:'si'});
			});
			
			$('#btnQuitarSeleccionMercados').click(function(){
				var palabra = $("#txtBuscarFiltroMercados").val();
				//var ids = $("#hdnIds").val();
				$('#tblMercadosSeleccionadosFiltros').empty();
				$('#hdnIdsFiltroMercados').val('');
				$('#hdnNombresFiltroMercados').val('');
				$('#divRespuesta').load("ajax/cargarFiltroMercados.php",{palabra:palabra,idsFiltros:''});
			});
			
			$('#btnEjecutarFiltroMercados').click(function(){
				$('#sltMercado').text($('#hdnNombresFiltroMercados').val());
				$('#divFiltrosMercados').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnCancelarFiltroMercados').click(function(){
				$('#divFiltrosMercados').hide();
				$('#divCapa3').hide();
			});
			/**********termina filtro mercados*******/
			
			/**********filtro periodos**************/
			$('#txtBuscarFiltroPeriodos').keyup(function() {
				palabra = $("#txtBuscarFiltroPeriodos").val();
				//ids = $("#hdnIds").val();
				idsFiltros = $("#hdnIdsFiltroPeriodos").val();
				$("#divRespuesta").load("ajax/cargarFiltroPeriodos.php",{palabra:palabra,idsFiltros:idsFiltros});
			});
			
			$('#btnSeleccionarTodosPeriodos').click(function(){
				//var ids = $('#hdnIds').val();
				var palabra = $('#txtBuscarFiltroPeriodos').val();
				$('#divRespuesta').load("ajax/cargarFiltroPeriodos.php",{palabra:palabra,idsFiltros:'',seleccionarTodo:'si'});
			});
			
			$('#btnQuitarSeleccionPeriodos').click(function(){
				var palabra = $("#txtBuscarFiltroPeriodos").val();
				//var ids = $("#hdnIds").val();
				$('#tblPeriodosSeleccionadosFiltros').empty();
				$('#hdnIdsFiltroPeriodos').val('');
				$('#hdnNombresFiltroPeriodos').val('');
				$('#divRespuesta').load("ajax/cargarFiltroPeriodos.php",{palabra:palabra,idsFiltros:''});
			});
			
			$('#btnEjecutarFiltroPeriodos').click(function(){
				$('#sltPeriodo').text($('#hdnNombresFiltroPeriodos').val());
				$('#divFiltrosPeriodos').hide();
				$('#divCapa3').hide();
			});
			
			$('#btnCancelarFiltroPeriodos').click(function(){
				$('#divFiltrosPeriodos').hide();
				$('#divCapa3').hide();
			});
			/**********termina filtro mercados*******/
		
		
			
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
		
		/*function abreOtrasActividades(dia,fecha){
			//alert(fecha);
			
			fecha = $('#hdn'+dia).val();
			
			date = fecha.substring(8,10)+'/'+fecha.substring(5,7)+'/'+fecha.substring(0,4);
			
			idUsuario = $('#hdnIdsFiltroUsuarios').val();
			if(idUsuario === undefined){
				idUsuario = $('#hdnIdUser').val();
			}
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				
				planVisita = 'plan';
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				
				planVisita = 'visita';
			}
			
			$('#divBuscarPersonas').hide();
			$('#divBuscarInst').hide();
			$('#divReportarOtrasActividades').show();
			$('#over').show(500);
			$('#fade').show(500);
			$('#txtFechaReportarOtrasActividades').val(date);
			$('#txtFechaReportarOtrasActividadesFin').val(date);
			$('#tdFechaOtrasActividades').hide();
			$('#hdnIdOA').val('');
			$('#sltMultiSelectOA').prop('disabled', false);
			$('#txtFechaReportarOtrasActividades').prop('disabled', false);
			$('#btnGuardarPeriodo').prop('disabled', false);
			$('#txtComentariosOtrasActividades').val('');
			$('#hdnIdUsuarioOA').val('');
			totalChkOA = $('#hdnTotalChkOA').val();
			for(i=0;i<=totalChkOA;i++){
				$('#chkOA'+i).prop("checked", false);
				$('#txtOA'+i).val('');
			}
			
			$("#divRespuesta").load("ajax/cargarOtrasActividades.php",{id:'',planVisita:planVisita,fecha:fecha,idUsuario:idUsuario});
		}*/

		function abreOtrasActividades2(fecha){
			//alert(fecha);
			date = fecha.substring(8,10)+'/'+fecha.substring(5,7)+'/'+fecha.substring(0,4);
			//alert(fecha);
			$('#btnEliminarOtrasActividades').hide();

			$('#sltMultiSelectOA').text('Seleccione');
			//$('#sltMultiSelectOA').val('');
			//$('#txtFechaReportarOtrasActividades').text('');
			//$('#txtFechaReportarOtrasActividadesFin').text('');
			
			idUsuario = $('#hdnIdUser').val();
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				
				planVisita = 'plan';
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				
				planVisita = 'visita';
			}
			
			$('#divBuscarPersonas').hide();
			$('#divBuscarInst').hide();
			$('#divReportarOtrasActividades').show();
			$('#over2').show(500);
			$('#fade').show(500);
			//$('body').addClass('no-scroll');
			$('#txtFechaReportarOtrasActividades').val(date);
			$('#txtFechaReportarOtrasActividadesFin').val(date);
			$('#tdFechaOtrasActividades').hide();
			$('#hdnIdOA').val('');
			$('#sltMultiSelectOA').prop('disabled', false);
			$('#txtFechaReportarOtrasActividades').prop('disabled', false);
			$('#btnGuardarPeriodo').prop('disabled', false);
			$('#txtComentariosOtrasActividades').val('');
			$('#hdnIdUsuarioOA').val('');
			totalChkOA = $('#hdnTotalChkOA').val();
			for(i=0;i<=totalChkOA;i++){
				$('#chkOA'+i).prop("checked", false);
				$('#txtOA'+i).val('');
			}
			
			$("#divRespuesta").load("ajax/cargarOtrasActividades.php",{id:'',planVisita:planVisita,fecha:fecha,idUsuario:idUsuario});
		}
		
		/*function abreBuscarPersona(dia,fecha){
			var ids = $('#hdnIds').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();
			fecha = $('#hdn'+dia).val();
			//alert(fecha);
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				if(fecha < '<?= date("Y-m-d") ?>'){
					alertPlaneaFechasAnt();
					return;
				}
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				if(fecha > '<?= date("Y-m-d") ?>'){
					alertReportarError();
					return;
				}
			}
			$("#hdnDiaCalendario").val(dia);
			$("#txtBuscarPersona").val('');
			$("#divRespuesta").load("ajax/persFiltradas.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario,dia:dia,fecha:fecha});
			$('#divBuscarPersonas').show();
			$('#over').show(500);
			$('#fade').show(500);
		}*/

		function abreBuscarPersona2(fecha){
			//alert(fecha);
			var ids = $('#hdnIds').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();
			$("#txtBuscarPersona").val('');
			cargandoBuscadorMed();
			$("#divRespuesta").load("ajax/persFiltradas.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario,fecha:fecha}, function(){
				$('#tblBuscarPersonas').waitMe('hide');
			});
			$('#divBuscarPersonas').show();
			$('#over2').show(500);
			$('#fade').show(500);
			//$('body').addClass('no-scroll');
		}
		
		/*function abreBuscarInst(dia, fecha){
			//alert(dia);
			var ids = $('#hdnIds').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();
			fecha = $('#hdn'+dia).val();
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				if(fecha < '<?= date("Y-m-d") ?>'){
					alertPlaneaFechasAnt();
					return;
				}
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				if(fecha > '<?= date("Y-m-d") ?>'){
					alertReportarError();
					return;
				}
			}
			$("#hdnDiaCalendario").val(dia);
			$("#txtBuscarInst").val('');
			$("#divRespuesta").load("ajax/instFiltradas.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario,dia:dia,fecha:fecha});
			$('#divBuscarPersonas').hide();
			$('#divReportarOtrasActividades').hide();
			$('#divBuscarInst').show();
			$('#over').show(500);
			$('#fade').show(500);
		}*/

		function abreBuscarInst2(fecha){
			//alert(fecha);
			var ids = $('#hdnIds').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();
			$("#txtBuscarInst").val('');
			$('#buscarFarmacia').hide();
			$('#buscarInst').show();
			$('#buscarHospital').hide();

			cargandoBuscadorInst2();
			$("#divRespuesta").load("ajax/instFiltradas.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario,fecha:fecha}, function(){
				$('.cargaBusquedaInst').waitMe('hide');
			});
			$('#divBuscarPersonas').hide();
			$('#divReportarOtrasActividades').hide();
			$('#divBuscarInst').show();
			$('#over2').show();
			$('#fade').show();
			//$('body').addClass('no-scroll');
		}

		function abreBuscarHospital(fecha){
			//alert(fecha);
			var ids = $('#hdnIds').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();
			$("#txtBuscarHos").val('');
			$('#buscarFarmacia').hide();
			$('#buscarInst').hide();
			$('#buscarHospital').show();

			cargandoBuscadorInst2();
			$("#divRespuesta").load("ajax/hosFiltradas.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario,fecha:fecha}, function(){
				$('.cargaBusquedaInst').waitMe('hide');
			});
			$('#divBuscarPersonas').hide();
			$('#divReportarOtrasActividades').hide();
			$('#divBuscarInst').show();
			$('#over2').show();
			$('#fade').show();
			//$('body').addClass('no-scroll');
		}

		function abreBuscarFarmacia(fecha){
			//alert(fecha);
			var ids = $('#hdnIds').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();
			$("#txtBuscarFar2").val('');
			$('#buscarFarmacia').show();
			$('#buscarInst').hide();
			$('#buscarHospital').hide();

			cargandoBuscadorInst2();
			$("#divRespuesta").load("ajax/farFiltradas.php",{ids:ids,tipoUsuario:tipoUsuario,palabra:'',idUsuario:idUsuario,fecha:fecha}, function(){
				$('.cargaBusquedaInst').waitMe('hide');
			});
			$('#divBuscarPersonas').hide();
			$('#divReportarOtrasActividades').hide();
			$('#divBuscarInst').show();
			$('#over2').show();
			$('#fade').show();
			//$('body').addClass('no-scroll');
		}
		
		function persSeleccionada(nombre, dia){
			$('#tbl'+dia).append('<tr><td>P: '+nombre+'</td></tr>');
			$('#cerrarInformacion').click(); 
		}
		
		/*function nuevoPlan(idPersona, dia, fecha){
			ruta = $('#sltRutaBuscaPersonas').val();
			tipoUsuario = $('#hdnTipoUsuario').val();
			idUser = $('#hdnIdUser').val();
			especialidad = $('#hdnEspecialidadPersona').val();
			cicloActivo = $('#hdnCicloActivo').val();
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				$('#divPlanes').show();
				$('#divRespuesta').load("ajax/cargarPlan.php", {idPersona:idPersona,fechaPlan:fecha,pantalla:'cal',ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser});
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
				$('#divVisitas').show();
				$('#divRespuesta').load("ajax/cargarVisita.php", {idPersona:idPersona,lat:lat,lon:lon,pantalla:'cal',fechaVisita:fecha,ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser,especialidad:especialidad,cicloActivo:cicloActivo});
			}
			$('#cerrarInformacion').click();
			$('#divCapa3').show('slow');
		}*/
		function nuevoPlan(idPersona, fecha){
			ruta = $('#sltRutaBuscaPersonas').val();
			tipoUsuario = $('#hdnTipoUsuario').val();
			idUser = $('#hdnIdUser').val();
			especialidad = $('#hdnEspecialidadPersona').val();
			cicloActivo = $('#hdnCicloActivo').val();
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				$('#divPlanes').show();
				$('#divRespuesta').load("ajax/cargarPlan.php", {idPersona:idPersona,fechaPlan:fecha,pantalla:'cal',ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser});
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
				$('#divVisitas').show();
				$('#divRespuesta').load("ajax/cargarVisita.php", {idPersona:idPersona,lat:lat,lon:lon,pantalla:'cal',fechaVisita:fecha,ruta:ruta,tipoUsuario:tipoUsuario,idUser:idUser,especialidad:especialidad,cicloActivo:cicloActivo});
			}
			//$('#cerrarInformacion').click();
			$('#divBuscarPersonas').hide();
			$('#over2').hide();
			$('#fade').hide();
			$('#divCapa3').show('slow');
			//$('body').addClass('no-scroll');
		}
		
		/*function nuevoPlanInst(idInst, dia, fecha, usuario){
			ruta = $('#sltRutaBuscaInst').val();
			tipoUsuario = $('#hdnTipoUsuario').val();
			//alert(tipoUsuario);
			var idUsuario = $('#hdnIdUser').val();
			var idRepre = usuario;
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				$('#divPlanesInst').show();
				$('#divRespuesta').load("ajax/cargarPlanInst.php", {idInst:idInst,fechaPlan:fecha,idUsuario:idUsuario,pantalla:'cal',ruta:ruta,tipoUsuario:tipoUsuario,repre:idRepre});
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
				var idTipoInst = $('#hdnIdTipoInst').val();
				$('#divVisitasInst').show();
				$('#divRespuesta').load("ajax/cargarVisitaInst.php", {idInst:idInst,lat:lat,lon:lon,pantalla:'cal',fechaVisita:fecha,idUsuario:idUsuario,idTipoInst:idTipoInst,tipoUsuario:tipoUsuario,idRepre:idRepre});
			}
			$('#cerrarInformacion').click();
			$('#divCapa3').show('slow');
		}*/

		function nuevoPlanInst(idInst, fecha, usuario){
			ruta = $('#sltRutaBuscaInst').val();
			tipoUsuario = $('#hdnTipoUsuario').val();
			//alert(tipoUsuario);
			var idUsuario = $('#hdnIdUser').val();
			var idRepre = usuario;
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				$('#divPlanesInst').show();
				$('#divRespuesta').load("ajax/cargarPlanInst.php", {idInst:idInst,fechaPlan:fecha,idUsuario:idUsuario,pantalla:'cal',ruta:ruta,tipoUsuario:tipoUsuario,repre:idRepre});
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				var lat = $('#lblLatitudPersonas').text().replace("Latitud: ","");
				var lon = $('#lblLongitudPersonas').text().replace("Longitud: ","");
				var idTipoInst = $('#hdnIdTipoInst').val();
				$('#divVisitasInst').show();
				$('#divRespuesta').load("ajax/cargarVisitaInst.php", {idInst:idInst,lat:lat,lon:lon,pantalla:'cal',fechaVisita:fecha,idUsuario:idUsuario,idTipoInst:idTipoInst,tipoUsuario:tipoUsuario,idRepre:idRepre});
			}
			$('#cerrarInformacion').click();
			$('#divCapa3').show();
			//$('body').addClass('no-scroll');
		}
		
		function traePlanesVisitasDia(dia, fecha){
			$("#divRespuesta").load("ajax/planesVisitasCalendario.php",{dia:dia,fecha:fecha,idUsuario:$('#hdnIdUser').val()});
		}
		
		function muestraOtrasActividades(id){
			$('#btnEliminarOtrasActividades').show();
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				planVisita = 'plan';
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				planVisita = 'visita';
			}
			$("#divRespuesta").load("ajax/cargarOtrasActividades.php",{id:id,planVisita:planVisita});
			$('#divReportarOtrasActividades').show();
			$('#over2').show(500);
			$('#fade').show(500);
		}
		
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

			lat = crd.latitude;
			lon = crd.longitude;

			$("#hdnLatitude").val(crd.latitude);
			$("#hdnLongitude").val(crd.longitude);
			
		};

		function error(err) {
			$("#hdnLatitude").val('0.0');
			$("#hdnLongitude").val('0.0');
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
			lat = $('#hdnLatitude').val();
			lon = $('#hdnLongitude').val();
			var myLatlng = new google.maps.LatLng(lat, lon);
			var myOptions = {
				zoom: 4,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map($("#"+map).get(0), myOptions);
			//map = new google.maps.Map($("#map_canvas2").get(0), myOptions);
			//map2 = new google.maps.Map($('#map_convas2').get(0), myOptions);
			//var address = 'Insurgentes sur 670';
			//var geocoder = new google.maps.Geocoder();
			//geocoder.geocode({ 'address': address}, geocodeResult);
			//$('#lblLatitudPersonas').text('Latitud: ' + lat);
			//$('#lblLongitudPersonas').text('Longitud: ' + lon);
			$('#txtLatitud').val(lat);
			$('#txtLongitud').val(lon);
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
				alertErrorGeocoding(status);
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
		function enviaIdTr(id) {
			idTrMedico = id;

			var trMedconR = idmedico.substring(0,idmedico.length-1);
			var trMedconR1 = "tr"+trMedconR+"1";
			var trMedconR2 = "tr"+trMedconR+"2";
			var trMedconR3 = "tr"+trMedconR+"3";
			var trMedconR4 = "tr"+trMedconR+"4";
			var trMedconR5 = "tr"+trMedconR+"5";
			
			//alert(trMedconR);

			if(idTrMedico == trMedconR1){
				$('#'+trMedconR1).addClass("div-slt-lista");
				$('#'+trMedconR2).removeClass("div-slt-lista");
				$('#'+trMedconR3).removeClass("div-slt-lista");
				$('#'+trMedconR4).removeClass("div-slt-lista");
				$('#'+trMedconR5).removeClass("div-slt-lista");
			}
			if(idTrMedico == trMedconR2){
				$('#'+trMedconR1).removeClass("div-slt-lista");
				$('#'+trMedconR2).addClass("div-slt-lista");
				$('#'+trMedconR3).removeClass("div-slt-lista");
				$('#'+trMedconR4).removeClass("div-slt-lista");
				$('#'+trMedconR5).removeClass("div-slt-lista");
			}
			if(idTrMedico == trMedconR3){
				$('#'+trMedconR1).removeClass("div-slt-lista");
				$('#'+trMedconR2).removeClass("div-slt-lista");
				$('#'+trMedconR3).addClass("div-slt-lista");
				$('#'+trMedconR4).removeClass("div-slt-lista");
				$('#'+trMedconR5).removeClass("div-slt-lista");
			}
			if(idTrMedico == trMedconR4){
				$('#'+trMedconR1).removeClass("div-slt-lista");
				$('#'+trMedconR2).removeClass("div-slt-lista");
				$('#'+trMedconR3).removeClass("div-slt-lista");
				$('#'+trMedconR4).addClass("div-slt-lista");
				$('#'+trMedconR5).removeClass("div-slt-lista");
			}
			if(idTrMedico == trMedconR5){
				$('#'+trMedconR1).removeClass("div-slt-lista");
				$('#'+trMedconR2).removeClass("div-slt-lista");
				$('#'+trMedconR3).removeClass("div-slt-lista");
				$('#'+trMedconR4).removeClass("div-slt-lista");
				$('#'+trMedconR5).addClass("div-slt-lista");
			}
		} 

		function presentaDatos(idMedInst, id, div, medico, especialidad, idRutaInst){
			$('#divCapa3').show();
			idPersonaReactivar = id;
			tipoUsuario = $('#hdnTipoUsuario').val();
			idUsuario = $('#hdnIdUser').val();
		
			var sizeScreen = $(window).height(); //959
			var sizeNavbar = $('.navbar').outerHeight(true); //90
			var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
			var sizeHeaderMedicos = $('.headerMedicos').outerHeight(true); //15-45
			var sizeHeaderInfoMed = 80;
			var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
			var sizeHeaderInfoInst = 80;
			if(id==""){
				id=$('#hdnMed').val();
			}
							
			if (div == 'divDatosPersonales') {
				idmedico = idMedInst;
				$('#lstYearVisitas').val('0');
				$('#lstYearPlanes').val('0');
				cargandoMedico2();
				cargandoMedico3();
				$('#lkInformacionPersona').click();
				
				$("#divRespuesta").load("ajax/cargarPersona.php", {
					id: id,
					tipoUsuario: tipoUsuario,
					idUsuario: idUsuario
				}, function () {
					$("#cardInfMedicos").waitMe("hide");
					if ($("#tabMed3").hasClass('btn-indigo-slt')) {
						$('#lkMapa').click();
					}

					var sizeTabDatosPer = $('.tabDatosPer').outerHeight(true);
					var addHeight = 20;
					if(sizeHeaderInicio == 15){
						sizeHeaderInicio = sizeHeaderMedicos;
					}
					var heightCardInfoMed = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeHeaderInfoMed - sizeTabDatosPer - addHeight;
					var heightInfoMed = heightCardInfoMed + 'px';
					$('.cardDatosPer').css('height', heightInfoMed);
					$('#divCapa3').hide();
				});
			}
			if (div == 'divDatosInstituciones') {
				idInstTabs = idMedInst;
				$('#lstYearVisitasInst').val('0');
				//$('#lstYearPlanesInst').val('0');
				$('#ui-id-6').click();
				cargandoInst2();
				$("#divRespuesta").load("ajax/cargarInstitucion.php", {
					id: id,
					idUsuario: idUsuario,
					repre: idRutaInst,
					tipoUsuario: tipoUsuario
				}, function () {
					$("#cardInfInst").waitMe("hide");
					if ($("#tabInst2").hasClass('btn-indigo-slt')) {
						$('#lkMapaInstituciones').click();
					}

					var sizeTabDatosInst = $('.tabDatosInst').outerHeight(true);
					var addHeight = 20;
					if(sizeHeaderInicio == 15){
						sizeHeaderInicio = sizeHeaderInst;
					}
					var heightCardInfoInst = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeHeaderInfoInst - sizeTabDatosInst - addHeight;
					var heightInfoInst = heightCardInfoInst + 'px';
					$('.cardDatosInst').css('height', heightInfoInst);
				});
			}

			$('#divDatosPersonales').hide();
			$('#divDatosInstituciones').hide();
			$('#divDatosHospitales').hide();
			$('#divDatosFarmacias').hide();
			$('#divPlanearOtrasActividades').hide();
			$('#divMensaje').hide();
			$('#lblEspecialidad1').text(especialidad);
			$('#lblNombreMedico1').text(medico);
			$('#lblNombreMedicoInactivo').text(medico);
			$('#lblEspecialidad2').text(especialidad);
			$('#lblEspecialidadMedInactivo').text(especialidad);
			$('#lblEspecialidadBaja').text(especialidad);
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
			$('#'+div).show();
			//$('#over').show(500);
			//$('#fade').show(500);
			$('#' + actualTab).click();
			$('#' + actualTabInst).click();
		}
		
		function nuevaPagina(pagina,hoy,ids,visitados){
			var sizeScreen = $(window).height(); //959
			var sizeNavbar = $('.navbar').outerHeight(true); //90
			var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
			var sizeHeaderMedicos = $('.headerMedicos').outerHeight(true); //15-45
				
			$('#hdnPaginaPersonas').val(pagina);

			estatusPersona = $('#sltEstatusFiltro').val();
			nombre = $('#txtNombreFiltro').val();
			apellidos = $('#txtApellidosFiltro').val();
			especialidad = $('#sltEspecialidadFiltro').val();
			categoria = $('#sltCategoriaFiltro').val();
			inst = $('#txtInstitucionFiltro').val();
			dir = $('#txtDireccionFiltro').val();
			del = $('#txtDelegacionFiltro').val();
			estado = $('#txtEstadoFiltro').val();
			tipoUsuario = $('#hdnTipoUsuario').val();
			colonia = $('#txtColoniaFiltro').val();
			cp = $('#txtCPFiltro').val();
			brick = $('#txtBrickFiltro').val();
			/*totalChecks = $('#hdnTotalRutaPersonas').val();
			repre = '';
			for(var i=1; i <= totalChecks; i++){
				if($('#rutaPersonas'+i).prop('checked')){
					repre += $('#rutaPersonas'+i).val()+",";
				}
			}*/
			repre = $('#hdnIdsFiltroUsuarios').val();
			motivoBaja = $('#sltBajasFiltro').val();
			categoriaAlfaSigma = $('#sltCategoriaAlfaSigmaFiltro').val();
			
			nombreList = buscarMedico;
			cargandoMedico();
			$("#tbPersonas").load("ajax/cargarTablaPersonas.php", {
				pagina: pagina,
				hoy: hoy,
				ids: ids,
				visitados: visitados,
				estatusPersona: estatusPersona,
				nombre: nombre,
				apellidos: apellidos,
				nombreList:nombreList,
				especialidad: especialidad,
				categoria: categoria,
				inst: inst,
				dir: dir,
				del: del,
				estado: estado,
				repre: repre,
				tipoUsuario: tipoUsuario,
				colonia: colonia,
				cp: cp,
				brick: brick,
				motivoBaja: motivoBaja,
				categoriaAlfaSigma:categoriaAlfaSigma
			},function(){
				var sizeListMedFoot = $('.listaMedicosTfoot').outerHeight(); //15-45
				var heightMedList = 0;
				var addHeight = 160;
				//var addHeight = 164;

				if(sizeHeaderInicio == 15){
					sizeHeaderInicio = sizeHeaderMedicos;
				}

				heightMedList = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListMedFoot - addHeight;
			
				var heightCardMedList = heightMedList + 'px';
				$('.listaMedicos').css('height', heightCardMedList);

				$('#trmed1').addClass('div-slt-lista');
				$(".cardListMedicos").waitMe("hide");

				if(sinResultados == 0){
					presentaDatos("med1", idMed1, "divDatosPersonales", nombreMed1, especialidadMed1);

					if(tipoUsuario == 2 || tipoUsuario == 5){
						if(motivoBaja != '00000000-0000-0000-0000-000000000000'){
							$('#divMedicosInactivos').show();
							$('#divMedicosInactivos').waitMe('hide');
							$('#cardInfMedicos').hide();
						}else{
							$('#divMedicosInactivos').hide();
							$('#cardInfMedicos').show();
						}
					}else{
						$('#divMedicosInactivos').hide();
						$('#cardInfMedicos').show();
					}
				}
			});
			seleccionadosCambiaRuta = $('#hdnSelecciandoCambiarRuta').val();
			$("#tblCambiarRutaPersonas").load("ajax/cargarTablaPersonasCambiarRuta.php", {
				pagina: pagina,
				hoy: hoy,
				ids: ids,
				visitados: visitados,
				estatusPersona: estatusPersona,
				nombre: nombre,
				apellidos: apellidos,
				nombreList:nombreList,
				especialidad: especialidad,
				categoria: categoria,
				inst: inst,
				dir: dir,
				del: del,
				estado: estado,
				repre: repre,
				seleccionadosCambiaRuta: seleccionadosCambiaRuta,
				colonia: colonia,
				cp: cp,
				brick: brick,
				motivoBaja: motivoBaja
			});
		}
		
		function exportarExcelPersonas(hoy,ids){
			var ancho = 350;
			var alto = 450;
			var x = (screen.width/2)-(ancho/2);
			var y = (screen.height/2)-(alto/2);
			visitados = $('#hdnFiltrosExportar').val();
			//tipoPersona = $('#sltTipoPersonaFiltro').val();
			nombre = $('#txtNombreFiltro').val();
			apellidos = $('#txtApellidosFiltro').val();
			sexo = $('#sltSexoFiltro').val();
			especialidad = $('#sltEspecialidadFiltro').val();
			categoria = $('#sltCategoriaFiltro').val();
			inst = $('#txtInstitucionFiltro').val();
			dir = $('#txtDireccionFiltro').val();
			del = $('#txtDelegacionFiltro').val();
			estado = $('#txtEstadoFiltro').val();
			repre=$('#hdnIdsFiltroUsuarios').val();
			
			//alert(especialidad+' '+categoria+' repre'+repre);
			variables = "hoy="+hoy+"&ids="+ids+"&visitados="+visitados+"&nombre="+nombre+"&apellidos="+apellidos+"&sexo="+sexo+"&especialidad="+especialidad+"&categoria="+categoria+"&inst="+inst+"&dir="+dir+"&del="+del+"&estado="+estado+"&repre="+repre;
			var ventanaExportarPersonas = window.open("exportarExcelPersonas.php?"+variables, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
			ventanaExportarPersonas.focus();
		}
		
		function muestraPlan(idPlan){
			if($('#divCalendario').is (':visible')){
				pantalla = 'cal';
			}else{
				pantalla = '';
			}
			//alert(pantalla);
			$('#btnEliminarPlanPerson').show();
			$('#btnCancelarSigPlan').hide();
			$("#divCapa3").show('slow');
			$("#divPlanes").show();
			idUser = $('#hdnIdUser').val();
			tipoUsuario = $("#hdnTipoUsuario").val();
			$("#divRespuesta").load("ajax/cargarPlan.php", {idPlan:idPlan,pantalla:pantalla,tipoUsuario:tipoUsuario,idUser:idUser});
		}
		
		function muestraVisita(idVisita, idPlan){
			$("#divCapa3").show('slow');
			$("#divVisitas").show();
			$("#btnEliminarVisitaPerson").show();
			//$('body').addClass('no-scroll');
			especialidad = $('#hdnEspecialidadPersona').val();
			cicloActivo = $('#hdnCicloActivo').val();
			fecha = $('#txtFechaVisita').val();
			cargandoVisita();
			$("#divRespuesta").load("ajax/cargarVisita.php", {idVisita:idVisita,idPlan:idPlan,especialidad:especialidad,cicloActivo:cicloActivo}, function(){
				$("#cargandoInfVisitaMed").waitMe('hide');
			});
		}
		
		function eliminarArchivo(idArchivo){
			if(confirm('¿Esta seguro de querer eliminar este archivo?')){
				$('#divRespuesta').load("ajax/eliminaArchivo.php",{idArchivo:idArchivo, idPersona:$('#hdnIdPersona').val(), idUsuario:$('#hdnIdUser').val()});
			}
		}
		
		/*function cargaMuestra(){
			alert($('#lstYear').val());
			$('#divMuestraMedica').load("ajax/cargarMuestra.php",{year:$('#lstYear').val(),idPersona:$('#hdnIdPersona').val()});
		}*/
		var banderaedit;
		function editarPersona(idPersona, ruta) {
			var idUsusario = $('#hdnIdUser').val();
			var tipoUsuario = $("#hdnTipoUsuario").val();
			var estatusMed = $('#sltBajasFiltro').val();
			 banderaedit=1;
			$('#hdnRutaDatosPersonales').val(ruta);
			$('#aPerfilPersona').click();
			$('#divPersona').show();
			$('#divCapa3').show('slow');


			$("#tabsPersona").tabs("option", "active", 0);
			$('#tabPer1').addClass('active');
			$('#tabPer2').removeClass('active');
			$('#tabPer3').removeClass('active');
			$('#tabPer4').removeClass('active');
			$('#tabPer5').removeClass('active');
			$('#tabPer6').removeClass('active');
			$('#tabPer7').removeClass('active');

			//alert(muestraEditaPersona);
			if((tipoUsuario == 2 || tipoUsuario == 5) && muestraEditaPersona == 1){
				if(estatusMed != '00000000-0000-0000-0000-000000000000'){
					$('#btnReactivarPersonaNuevo').show();
				}else{
					$('#btnReactivarPersonaNuevo').hide();
				}
			}else{
				$('#btnReactivarPersonaNuevo').hide();
			}

			$('#divRespuesta').load("ajax/cargarPersonaNueva.php", {
				idUsuario: idUsusario,
				idPersona: idPersona,
				ruta: ruta,
				idUsusario: idUsusario
			});
		}
		
		function eliminarPersona(idPersona, nombre, cargo, datosMedico, ruta){
			var tipoUsuario = $('#hdnTipoUsuario').val();
			var idUsuario = $('#hdnIdUser').val();



			if(tipoUsuario == 4){
				$('#hdnIdPersonaBaja').val(idPersona);
				$('#lblNombreMedEliminar').text(nombre);
				$('#lblTipoMedEliminar').text(cargo);
				$('#lblDatosMedicoMotivoBaja').html(datosMedico);
				$('#divMotivoBaja').show();
				$('#over').show(500);
				$('#fade').show(500);
				//$('body').addClass('no-scroll');
			}else{
				alertEliminarMedico(idUsuario, idPersona, tipoUsuario, ruta);

				/*if(confirm("¿Está seguro de elimiar el médico?")){
					$('#divRespuesta').load("ajax/eliminarMedico.php",{idPersona:idPersona,idUsuario:idUsuario,tipoUsuario:tipoUsuario,ruta:ruta});
				}*/
			}
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
			
			$('#txtTelPersonalPersonaNuevo2').val('');
			$('#txtCorreoPersonalPersonaNuevo2').val('');
			$('#txtCelularPersonaNuevo').val('');
			
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
			$("#tblDatosGnr").show();
			$("#divRespuesta").load("ajax/cargarInstSeleccionada.php",{idInst:id},function(){
				nombreInstOk = 1;
				$('#txtNombreInstPersonaNuevo').removeClass('invalid');
				$('#txtNombreInstPersonaNuevo').removeClass('invalid-slt');
				$('#txtNombreInstPersonaNuevoError').hide();
			});
		}
		
		function pad (str, max) {
			str = str.toString();
			return str.length < max ? pad("0" + str, max) : str;
		}
		
		function actualizaFechaCumple(){
			var dia = $("#sltDiaFechaNacimientoPersonaNuevo").val();
			var mes = $("#sltMesFechaNacimientoPersonaNuevo").val();
			var anio = $("#sltAnioFechaNacimientoPersonaNuevo").val();
			$("#fecha").val(anio+'-'+pad(mes, 2)+'-'+pad(dia, 2));
		}
		
		function seleccionaCambiarRutaPersona(idPersona, chk){
			if($("#"+chk).prop('checked')){
				personasSeleccionadas = $("#hdnSelecciandoCambiarRuta").val() + idPersona + ",";
			}else{
				personasSeleccionadas = $("#hdnSelecciandoCambiarRuta").val().replace(idPersona+",","");
			}
			$("#hdnSelecciandoCambiarRuta").val(personasSeleccionadas);
		}
		
		var expandedInversion = false;
		function showCheckboxesInversiones() {
			var checkboxes = document.getElementById("checkboxesInversiones");
			if (!expandedInversion) {
				checkboxesInversiones.style.display = "block";
				expandedInversion = true;
			} else {
				checkboxesInversiones.style.display = "none";
				expandedInversion = false;
			}
		}
		
		function agregaDesInversiones(texto, check){
			var textoChk = '';
			if($('#'+check).prop('checked')){
				if($("#sltMultiSelectInversiones").text() == 'Selecciona'){
					textoChk = texto + ";";
				}else{
					textoChk = $("#sltMultiSelectInversiones").text() + texto + ";";
				}
			}else{
				textoChk = $("#sltMultiSelectInversiones").text().replace(texto + ";", '');
			}
			$("#sltMultiSelectInversiones").text(textoChk);
		}
		
		function muestraInversion(idInversion){
			$("#divCapa3").show('slow');
			$("#divInversiones").show();
			idUser = $('#hdnIdUser').val();
			tipoUsuario = $("#hdnTipoUsuario").val();
			$("#divRespuesta").load("ajax/cargarInversion.php", {idInversion:idInversion,tipoUsuario:tipoUsuario,idUser:idUser});
		}
		
		/*fin de persona*/
		
		/* fin personas */

		function criteriosReporte(id,variables,reporte){
			$('#btnReporte').show();
			var criterios = variables.split(',');
			tipoUsuario = $('#hdnTipoUsuario').val();
			$('#txtFechaInicioReportes').val('');
			$('#txtFechaFinReportes').val('');
			//$('#sltEsatusReportes').val('00000000-0000-0000-0000-000000000000');
			for(i=1;i<=$("#hdnTotalChecksListadosPersonas").val();i++){
				$("#listadosPersonas"+i).prop("checked", false);
			}
			$('#hdnIdsFiltroProductos').val('');
			$('#hdnNombresFiltroProductos').val('');
			$('#sltProductoReportes').text('');
			$('#tblProductosSeleccionados tbody').empty();
			$('#fecIni').hide();
			$('#fecFin').hide();
			$('#repre').hide();
			$('#producto').hide();
			$('#status').hide();
			$('#status_i').hide();
			$('#periodo').hide();
			for(var i=0; i<criterios.length; i++){
				$('#'+criterios[i]).show();
			}
			$('#hdnReporte').val(reporte);
			
			if(tipoUsuario != 4){
				$('#sltRepreReportes').val('00000000-0000-0000-0000-000000000000');
			}
			$('#txtProductoReportes').val('');
			$('#txtPeriodoReportes').val('');

			//$('#tablaReportes').on('click', function (e) {
			var este = id;
			
			$('#tablaReportes').find('td').removeClass('div-slt-lista');

			$('#'+este).addClass('div-slt-lista');
			/*$('#tablaReportes').click(function(){
				$(this).parent().find('.tablaReportes').removeClass('div-slt-lista');
				$(this).addClass('div-slt-lista');
			});*/
		}
		
		function criteriosListado(id,variables,reporte){
			$('#btnListado').show();
			var criterios = variables.split(',');
			tipoUsuario = $('#hdnTipoUsuario').val();
			$('#txtFechaInicioListados').val('');
			$('#txtFechaFinListados').val('');
			//$('#sltEsatusListados').val('00000000-0000-0000-0000-000000000000');
			$("#sltMultiSelectListadosPersonas").text('Seleccione');
			for(i=1;i<=$("#hdnTotalChecksListadosPersonas").val();i++){
				$("#listadosPersonas"+i).prop("checked", false);
			}
			$('#hdnIdsFiltroProductos').val('');
			$('#hdnNombresFiltroProductos').val('');
			$('#sltProductoListados').text('');
			$('#tblProductosSeleccionados tbody').empty();
			$('#fecIniListados').hide();
			$('#fecFinListados').hide();
			$('#repreListados').hide();
			$('#productoListados').hide();
			$('#statusListados').hide();
			$('#statusListados_i').hide();
			$('#periodoListados').hide();
			for(var i=0; i<criterios.length; i++){
				$('#'+criterios[i]).show();
			}

			$('#hdnListado').val(reporte);
			
			if(tipoUsuario != 4){
				$('#sltRepreListados').val('00000000-0000-0000-0000-000000000000');
			}
			$('#txtProductoListados').val('');
			$('#txtPeriodoListados').val('');

			var este = id;
			$('#tablaListados').find('td').removeClass('div-slt-lista');

			$('#'+este).addClass('div-slt-lista');
		}
		
		/*instituciones*/
		function enviaIdTrInst(id) {
			idTrInst = id;
			//alert(idTrInst);
			//alert(idInstTabs);

			var trTab = idInstTabs.substring(0,idInstTabs.length-1);
			var tr1 = "tr"+trTab+"1";
			var tr2 = "tr"+trTab+"2";
			var tr3 = "tr"+trTab+"3";
			var tr4 = "tr"+trTab+"4";
			var tr5 = "tr"+trTab+"5";
			//alert(tr1);

			if(idTrInst == tr1){
				$('#'+tr1).addClass("div-slt-lista");
				$('#'+tr2).removeClass("div-slt-lista");
				$('#'+tr3).removeClass("div-slt-lista");
				$('#'+tr4).removeClass("div-slt-lista");
				$('#'+tr5).removeClass("div-slt-lista");
			}
			if(idTrInst == tr2){
				$('#'+tr1).removeClass("div-slt-lista");
				$('#'+tr2).addClass("div-slt-lista");
				$('#'+tr3).removeClass("div-slt-lista");
				$('#'+tr4).removeClass("div-slt-lista");
				$('#'+tr5).removeClass("div-slt-lista");
			}
			if(idTrInst == tr3){
				$('#'+tr1).removeClass("div-slt-lista");
				$('#'+tr2).removeClass("div-slt-lista");
				$('#'+tr3).addClass("div-slt-lista");
				$('#'+tr4).removeClass("div-slt-lista");
				$('#'+tr5).removeClass("div-slt-lista");
			}
			if(idTrInst == tr4){
				$('#'+tr1).removeClass("div-slt-lista");
				$('#'+tr2).removeClass("div-slt-lista");
				$('#'+tr3).removeClass("div-slt-lista");
				$('#'+tr4).addClass("div-slt-lista");
				$('#'+tr5).removeClass("div-slt-lista");
			}
			if(idTrInst == tr5){
				$('#'+tr1).removeClass("div-slt-lista");
				$('#'+tr2).removeClass("div-slt-lista");
				$('#'+tr3).removeClass("div-slt-lista");
				$('#'+tr4).removeClass("div-slt-lista");
				$('#'+tr5).addClass("div-slt-lista");
			}
		} 
		
		function nuevaPaginaInst(pagina,hoy,ids,visitados){
			var sizeScreen = $(window).height(); //959
			var sizeNavbar = $('.navbar').outerHeight(true); //90
			var sizeHeaderInicio = $('.block-headerInicio').outerHeight(true); //15-45
			var sizeHeaderInst = $('.headerInst').outerHeight(true); //15-45
			var sizeListInstFoot = 0;
			var sizeListInstFoot2 = 0;
			if(sizeHeaderInicio == 15){
				sizeHeaderInicio = sizeHeaderInst;
			}
			if(sizeListInstFoot == 104){
				sizeListInstFoot2 = 72;
			}
			if(sizeListInstFoot2 == 104){
				sizeListInstFoot = 72;
			}
			if(sizeListInstFoot == 0){
				sizeListInstFoot = 72;
			}
			if(sizeListInstFoot2 == 0){
				sizeListInstFoot2 = 72;
			}

			$('#hdnPaginaInst').val(pagina);
			var tipo = $('#sltTipoInstFiltro').val();
			var nombre = $('#txtNombreInstFiltro').val();
			var calle = $('#txtCalleInstFiltro').val();
			var colonia = $('#txtColoniaInstFiltro').val();
			var ciudad = $('#txtCiudadInstFiltro').val();
			var estado = $('#txtEstadoInstFiltro').val();
			var cp = $('#txtCPInstFiltro').val();
			var repre = $('#hdnIdsFiltroUsuarios').val();
			var geolocalizados = $('input:radio[name=rbGeo]:checked').val();
			//var tabActivo = $("#divGridInstituciones").tabs('option', 'active');
			var tabActivo= tabActivoInst;
			var tipoUsuario = $("#hdnTipoUsuario").val();
			var estatus = $("#sltEstatusFiltrosInst").val();
			var motivoBaja = $("#sltMotivoBajaFiltrosInst").val();

			cargandoInst();
			$("#divRespuesta").load("ajax/cargarTablaInst.php",{pagina:pagina,hoy:hoy,ids:ids,tipo:tipo,nombre:nombre,calle:calle,colonia:colonia,ciudad:ciudad,estado:estado,cp:cp,visitados:visitados,repre:repre,geolocalizados:geolocalizados,tabActivo:tabActivo,tipoUsuario:tipoUsuario,estatus:estatus,motivoBaja:motivoBaja},function(){
				$(".cardListInst").waitMe("hide");

				sizeListInstFoot = $('.listaInstTfoot').outerHeight(); //15-45
				sizeListInstFoot2 = $('.listaInstTfoot2').outerHeight(); //15-45
				var addHeight = 160;
				if(sizeListInstFoot == 104){
					sizeListInstFoot2 = 72;
				}
				if(sizeListInstFoot2 == 104){
					sizeListInstFoot = 72;
				}
				if(sizeListInstFoot == 0){
					sizeListInstFoot = 72;
				}
				if(sizeListInstFoot2 == 0){
					sizeListInstFoot2 = 72;
				}
				var heightInstList = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot - addHeight;
				var heightInstList2 = sizeScreen - sizeNavbar - sizeHeaderInicio - sizeListInstFoot2 - addHeight;
				var heightCardInstList = heightInstList + 'px';
				var heightCardInstList2 = heightInstList2 + 'px';
				$('.listaInstituciones').css('height', heightCardInstList);
				$('.listaInstituciones2').css('height', heightCardInstList2);
			});
		}

		function nuevaPaginaHos(pagina,hoy,ids,visitados){
			$('#hdnPaginaHos').val(pagina);
			var tipo = $('#sltTipoInstFiltro').val();
			var nombre = $('#txtNombreInstFiltro').val();
			var calle = $('#txtCalleInstFiltro').val();
			var colonia = $('#txtColoniaInstFiltro').val();
			var ciudad = $('#txtCiudadInstFiltro').val();
			var estado = $('#txtEstadoInstFiltro').val();
			var cp = $('#txtCPInstFiltro').val();
			var repre = $('#hdnIdsFiltroUsuarios').val();
			var geolocalizados = $('input:radio[name=rbGeo]:checked').val();
			//var tabActivo = $("#divGridInstituciones").tabs('option', 'active');
			var tabActivo = tabActivoInst;
			var tipoUsuario = $("#hdnTipoUsuario").val();
			var estatus = $("#sltEstatusFiltrosInst").val();
			var motivoBaja = $("#sltMotivoBajaFiltrosInst").val();
			//alert(estatus);
			$("#divRespuesta").load("ajax/cargarTablaInst.php",{pagina:pagina,hoy:hoy,ids:ids,tipo:tipo,nombre:nombre,calle:calle,colonia:colonia,ciudad:ciudad,estado:estado,cp:cp,visitados:visitados,repre:repre,geolocalizados:geolocalizados,tabActivo:tabActivo,tipoUsuario:tipoUsuario,estatus:estatus,motivoBaja:motivoBaja});
		}
		
		function muestraPlanInst(idPlan){
			if($('#divCalendario').is (':visible')){
				pantalla = 'cal';
			}else{
				pantalla = '';
			}
			idUsuario = $('#hdnIdUser').val();
			$("#divPlanesInst").show();
			$("#divCapa3").show('slow');
			$("#btnEliminarPlanInst").show();
			tipoUsuario = $("#hdnTipoUsuario").val();
			$("#divRespuesta").load("ajax/cargarPlanInst.php", {idPlan:idPlan,idUsuario:idUsuario,tipoUsuario:tipoUsuario,pantalla:pantalla});
		}
		
		function muestraVisitaInst(idVisita, idPlan){
			idTipoInst = $("#hdnIdTipoInst").val();
			$("#divVisitasInst").show();
			$("#divCapa3").show('slow');
			$("#btnEliminarVisitaInst").show();
			$("#divRespuesta").load("ajax/cargarVisitaInst.php", {idVisita:idVisita, idPlan:idPlan, idTipoInst:idTipoInst});
		}
		
		function editarInst(idInst){
			$("#hdnIdInst").val(idInst);
			$("#divInstitucion").show('slow');
			$("#divCapa3").show('slow');
			var idUsusario = $('#hdnIdUser').val();
			$("#divRespuesta").load("ajax/cargarInstitucionNueva.php", {idUsuario:idUsusario,idInst:idInst});
		}
		
		function eliminarInst(idInst, nombre,tipo,datosInst, ruta){
			var tipoUsuario = $('#hdnTipoUsuario').val();
			
			if(tipoUsuario == 4){
				$('#hdnIdInstBaja').val(idInst);
				$('#lblNombreInstEliminar').text(nombre);
				$('#lblTipoInstEliminar').text(tipo);
				$('#lblDatosInstMotivoBaja').html(datosInst);
				$('#divMotivoBajaInst').show();
				$('#over').show(500);
				$('#fade').show(500);
			}else{
				/*if(confirm('Desea eliminar la Institución!!!')){
					var idUsuario = $('#hdnIdUser').val();
					$('#divRespuesta').load('ajax/eliminarInst.php',{idInst:idInst,idUsuario:idUsuario,tipoUsuario:tipoUsuario});
				}*/
				var idUsuario = $('#hdnIdUser').val();
				alertEliminarInst(idInst, idUsuario, tipoUsuario);
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
			estadoInst = $('#txtEstadoInstFiltro').val();
			cpInst = $('#txtCPInstFiltro').val();
			if(tipoInst === null){
				tipoInst = '';
			}
			variables = "hoy="+hoy+"&ids="+ids+"&visitados="+visitados+"&tipoInst="+tipoInst+"&nombreInst="+nombreInst+"&calleInst="+calleInst+"&coloniaInst="+coloniaInst+"&ciudadInst="+ciudadInst+"&estadoInst="+estadoInst+"&cpInst="+cpInst;
			var ventanaExportarInst = window.open("exportarExcelInst.php?"+variables, "vtnPlanes", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
			ventanaExportarInst.focus();
		}
		
		function validaCantidadVisitasInst(texto, existencia, maximo){
			cantidad = $("#text"+texto).val();
			if(cantidad > existencia){
				alertExistenciaPiezas(existencia);
				return true;
			}
			if(cantidad > maximo){
				alertMasPiezas(maximo);
				return true;
			}
		}
		
		function existenciaVisitasInst(existencia){
			//alert('Existencia: '+existencia);
			alertExistenciaPiezas(existencia);
		}
		
		function llenaSiguienteVisitasInst(combo){
			var productosSeleccionados = '';
			for(var i=1;i<combo+1;i++){
				productosSeleccionados += $("#lstProductoInst"+i).val()+",";
			}
			$("#divRespuesta").load("ajax/llenaComboInst.php",{combo:combo,productos:productosSeleccionados});
		}
		
		var expandedVisitasInst = false;

		function showCheckboxesVisitasInst() {
			var checkboxes = document.getElementById("checkboxesInst");
			if (!expanded) {
				checkboxes.style.display = "block";
				expanded = true;
			} else {
				checkboxes.style.display = "none";
				expanded = false;
			}
		}
			$("#divDepartamento").show();
		
		function editarDepart(idDepart){
			$("#divCapa3").show();
			$("#divRespuesta").load("ajax/cargarDepartamento.php",{idDepto:idDepart});
		}
		
		function eliminarDepart(idDepart){
			idInst = $("#hdnIdInst").val();
			if($("#tblPersonasDepartamentoInstituciones tbody tr").length > 0){
				leyenda = "Este Departamento tiene residentes asociados \n¿está seguro que desea eliminarlo?";
			}else{
				leyenda = "¿Está seguro que desea eliminar este departamento?";
			}
			if (confirm(leyenda)) {
				$("#divRespuesta").load("ajax/eliminarDepartamento.php",{idDepto:idDepart,idInst:idInst});
			}
		}
		
		function guardarPersonaDepto(){
			var idDepto = $("#hdnIDepto").val();
			var idPersonaDepto = $("#hdnIdpersonaDeptoEdit").val();
			if(idPersonaDepto == ''){
				var paterno = $("#txtPaternoPersonaDepto").val();
				var materno = $("#txtMaternoPersonaDepto").val();
				var nombre = $("#txtNombrePersonaDepto").val();
				var especialidad = $("#sltEspecialidadPersonaDepto").val();
			}else{
				var paterno = $("#txtPaternoPersonaDeptoEdit").val();
				var materno = $("#txtMaternoPersonaDeptoEdit").val();
				var nombre = $("#txtNombrePersonaDeptoEdit").val();
				var especialidad = $("#sltEspecialidadPersonaDeptoEdit").val();
			}
			
			if(idDepto == ''){
				alertSelDepartamento();
				return true;
			}
			
			if(paterno == ''){
				alert("Ingrese el apellido paterno");
				$("#txtPaternoPersonaDepto").focus();
				return true;
			}
			
			if(nombre == ''){
				alert('Ingrese el nombre');
				$("#txtNombrePersonaDepto").focus();
				return true;
			}
			
			if(especialidad == ''){
				alert('Seleccione la especialidad');
				$("#sltEspecialidadPersonaDepto").focus();
				return true;
			}
			
			$("#divRespuesta").load("ajax/guardarPersonaDepto.php",{idDepto:idDepto,idPersonaDepto:idPersonaDepto,paterno:paterno,materno:materno,nombre:nombre,especialidad:especialidad});
		}
		
		function seleccionarDepto(idDepto, nombre, idTr){
			$("#hdnIDepto").val(idDepto);
			$("#lblNombreDepto").text(nombre);
			for(i=1;i<=$("#tblDepartamentos tbody tr").length;i++){
				if(i == idTr){
					$("#trDepto"+idTr).css("background-color", "#1E90FF");
					$("#trDepto"+idTr).css("color", "white");
				}else{
					$("#trDepto"+i).css("color", "black");
					if(i % 2 == 0){
						$("#trDepto"+i).css("background-color", "#A3D2FC");
					}else{
						$("#trDepto"+i).css("background-color", "#FFFFFF");
					}
				}
			}
			$("#divRespuesta").load("ajax/cargarPersonasDepto.php",{idDepto:idDepto});
		}
		
		function editarPersonaDepto(tr, paterno, materno, nombre, especialidad, idPersonaDeptoEdit){
			if($('#hdnIdpersonaDeptoEdit').val() != ''){
				alert("Debe cancelar el  cambio anterior");
				return true;
			}
<?php
			echo "sltEspecialidadPersonaDeptoEdit = '<select id=\"sltEspecialidadPersonaDeptoEdit\"><option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>';";
			$rsEsp = llenaCombo($conn, 19, 1);
			while($esp = sqlsrv_fetch_array($rsEsp)){
				echo "var id = '".$esp['id']."';
					var des = '".$esp['nombre']."';
				";
				echo "if('".$esp['nombre']."' == especialidad){
					sltEspecialidadPersonaDeptoEdit += '<option value=\"' + id + '\" selected>' + des + '</option>';
				}else{
					sltEspecialidadPersonaDeptoEdit += '<option value=\"' + id + '\">' + des + '</option>';
				}";
			}
			echo "sltEspecialidadPersonaDeptoEdit += '</select>';";
?>
			txtPaterno = '<input type="text" value="'+paterno+'" id="txtPaternoPersonaDeptoEdit" />';
			txtMaterno = '<input type="text" value="'+materno+'" id="txtMaternoPersonaDeptoEdit" />';
			txtNombre = '<input type="text" value="'+nombre+'" id="txtNombrePersonaDeptoEdit" />';
			boton = '<button type=\"button\" onclick=\"guardarPersonaDepto();\">Guardar</button>';
			$($('#tblPersonasDepartamentoInstituciones').find('tbody > tr')[tr]).children('td')[1].innerHTML = txtPaterno;
			$($('#tblPersonasDepartamentoInstituciones').find('tbody > tr')[tr]).children('td')[2].innerHTML = txtMaterno;
			$($('#tblPersonasDepartamentoInstituciones').find('tbody > tr')[tr]).children('td')[3].innerHTML = txtNombre;
			$($('#tblPersonasDepartamentoInstituciones').find('tbody > tr')[tr]).children('td')[4].innerHTML = sltEspecialidadPersonaDeptoEdit;
			$($('#tblPersonasDepartamentoInstituciones').find('tbody > tr')[tr]).children('td')[5].innerHTML = boton;
			$('#btnAgregarPersonaDepartamentoDatosInstituciones').html('Cancelar');
			$('#hdnIdpersonaDeptoEdit').val(idPersonaDeptoEdit);
		}
		
		function eliminarPersonaDepto(idPersonaDepto){
			idDepto = $('#hdnIDepto').val();
			if(confirm('¿Desea elimiar ese residente?')){
				$('#divRespuesta').load("ajax/eliminarPersonaDepto.php",{idPersonaDepto:idPersonaDepto,idDepto:idDepto});
			}
		}
		
		function seleccionaCambiarRutaInst(idInst, chk){
			if($("#"+chk).prop('checked')){
				instSeleccionadas = $("#hdnSelecciandoCambiarRutaInst").val() + idInst + ",";
			}else{
				instSeleccionadas = $("#hdnSelecciandoCambiarRutaInst").val().replace(idInst+",","");
			}
			$("#hdnSelecciandoCambiarRutaInst").val(instSeleccionadas);
		}
		
		/* termina inst */
		
		/* calendario*/
		function changeCalendar(){
			if($('#btnMonthSelect').hasClass('btn-blue-sel')){
				$("#btnMonthSelect").removeClass("btn-blue-sel");
				$("#btnWeekSelect").addClass("btn-blue-sel");
				$('#divCalendarioCambia').hide();
				$('#divWeek').show();
				
				$("#btnWeekSelect").attr("disabled", true);
				$("#btnMonthSelect").removeAttr("disabled");
				
				
			}else if($('#btnWeekSelect').hasClass('btn-blue-sel')){
				$("#btnWeekSelect").removeClass("btn-blue-sel");
				$("#btnMonthSelect").addClass("btn-blue-sel");
				$('#divWeek').hide();
				$('#divCalendarioCambia').show();
				
				$("#btnMonthSelect").attr("disabled", true);
				$("#btnWeekSelect").removeAttr("disabled");
			}
		}
		
		
		function sumaHoras(){
			suma = 0;

			for(i=1;i<=$('#hdnTotalChkOA').val();i++){
					suma += parseFloat($('#txtOA'+i).val()*1);
			}
			
			if(suma > 8){
				//alert('La suma no puede exceder de 8 hrs.');
				$('#maxHoras').show();
			}
			if(suma <= 8){
				$('#maxHoras').hide();
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
			//alert('hola');
			var fecha = '';
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				planVisita = 'plan';
				fecha = $('#txtFechaPlan').val();
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				planVisita = 'visita';
				fecha = $('#txtFechaVisita').val();
			}
			if($('#hdnTipoUsuario').val == 4){
				var ids = $('#hdnIds').val();
			}else{
				var ids = $('#hdnIds').val()+"','"+'<?= $idUsuario ?>';
			}
			var fecha = $('#hdnFechaCalendario').val();
			//var fecha = $('#txtFechaPlan').val();
			//alert(fecha);
			var idRepre = $('#sltRepreCalendario').val();
			if(! idRepre){
				idRepre = '';
			}

			cargandoCalendario();
			
			$('#divRespuesta').load("ajaxCalendario.php", {fecha:fecha, idUsuario:idRepre,planVisita:planVisita,ids:ids}, function(){
				$(".cardInfCal").waitMe("hide");
			});
			//update_calendar();
			update_calendar2(fecha);
		}
		
		function actualizaCalendarioBoton(planVisita){
			$('#hdnPantallaPlan').val('cal');
			if(planVisita == 'plan'){
				$("#btnPlanCalendario").addClass("seleccionado");
				$("#btnVisitaCalendario").removeClass("seleccionado");
				$('#liOtrasActividades').hide();
			}else if(planVisita == 'visita'){
				$("#btnPlanCalendario").removeClass("seleccionado");
				$("#btnVisitaCalendario").addClass("seleccionado");
				$('#liOtrasActividades').show();
			}	
			fecha = $("#hdnFechaCalendario").val();
			idUsuario = $('#sltRepreCalendario').val();
			if($('#hdnTipoUsuario').val == 4){
				var ids = $('#hdnIds').val();
			}else{
				var ids = $('#hdnIds').val()+"','"+'<?= $idUsuario ?>';
			}
			var repre = $('#hdnIdsFiltroUsuarios').val();
			if(idUsuario === undefined){
				idUsuario = '';
			}
			var repreNombres = $('#hdnNombresFiltroUsuarios').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();

			//alert(idUsuario);

			cargandoCalendario();
			$('#divRespuesta').load("ajaxCalendario.php", {fecha:fecha, idUsuario:idUsuario,planVisita:planVisita,ids:ids,repre:repre,tipoUsuario:tipoUsuario,repreNombres:repreNombres}, function(){
				$(".cardInfCal").waitMe("hide");
			});
		}
		
		function actualizaCalendarioSelect(){
			if($('#btnPlanCalendario').hasClass('seleccionado')){
				planVisita = 'plan';
			}else if($('#btnVisitaCalendario').hasClass('seleccionado')){
				planVisita = 'visita';
			}
			if($('#hdnTipoUsuario').val == 4){
				var ids = $('#hdnIds').val();
			}else{
				var ids = $('#hdnIds').val()+"','"+'<?= $idUsuario ?>';
			}
			var idRepre = $('#sltRepreCalendario').val();
			/*if(idRepre == ''){
				idRepre = $('#hdnNombresFiltroUsuarios').val();
			}
			alert('ca,bio');*/
			//alert('actcal');
			cargandoCalendario();
			$('#divRespuesta').load("ajaxCalendario.php", {fecha:$('#hdnFechaCalendario').val(), idUsuario:idRepre,planVisita:planVisita,ids:ids}, function(){
				$(".cardInfCal").waitMe("hide");
			});
		}
		
		/*function muestraRuteo(){
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
		}*/
		
		/* termina calendario*/
		
		/* visitas */
		
		function validaCantidad(texto, existencia, maximo){
			cantidad = $("#text"+texto).val();

			if(cantidad > existencia){
				//alert("Solo tienes "+existencia+" piezas de ese producto!!!");
				alertExistenciaPiezas(existencia);
				return true;
			}
			if(cantidad > maximo){
				//alert("Sólo puede entregar "+maximo+" piezas de ese producto!!!");
				alertMasPiezas(maximo);
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
		
		function agregaDesVisAcompaInst(texto, check){
			var textoChk = '';
			if($('#'+check).prop('checked')){
				if($("#sltMultiSelectInstVisita").text() == 'Selecciona'){
					textoChk = texto + ";";
				}else{
					textoChk = $("#sltMultiSelectInstVisita").text() + texto + ";";
				}
			}else{
				textoChk = $("#sltMultiSelectInstVisita").text().replace(texto + ";", '');
			}
			$("#sltMultiSelectInstVisita").text(textoChk);
			
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
			}else{
				var checks = $('#hdnTotalChecksInst').val();
				checks = (checks*1)+1;
				for(i=1;i<checks;i++){
					$('#acompaInst'+i).attr('checked', false);
				}
				$('#checkboxesInst').hide();
				$('#sltMultiSelectInst').val('Selecciona');
			}
		}
		
		function cargarCompetidor(idProducto, producto){
			$('#divCompetidores').show();
			$('#divCapa4').show('slow');
			$('#divRespuesta').load("ajax/cargarCompetidor.php", {idProducto:idProducto,producto:producto});
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
			tipoUsuario = $("#hdnTipoUsuario").val();
			if(tipoUsuario == 4){
				idUsuario = $('#hdnIdUser').val();
			}else{
				//alert($('#hdnIdsFiltroUsuarios').val());
				if($('#hdnIdsFiltroUsuarios').val() == ''){
					alertSelRepresentante();
					return true;
				}else{
					if($('#hdnIdsFiltroUsuarios').val().split(",").length > 2){
						alertSelUnRepresentante();
						return true;
					}else{
						var idUsuarioF = $('#hdnIdsFiltroUsuarios').val();
						idUsuario = idUsuarioF.substring(0, idUsuarioF.length-1); 
					}
				}
			}

			$('#divBuscarPersonas').hide();
			$('#divReportarOtrasActividades').hide();
			$('#divBuscarInst').hide();
			$('#divRuteo').show();
			$('#over2').show(500);
			$('#fade').show(500);
			//$('body').addClass('no-scroll');
			load_map('map_canvas3');
			fecha = $("#hdnFechaCalendario").val();
			if($('#btnPlanCalendario').hasClass('btn-account-sel')){
				planVisita = 'plan';
				$('#btnPlanRuteo').addClass("seleccionado");
				$("#btnVisitaRuteo").removeClass("seleccionado");
				$("#tblSimbologiaMarcadoresRuteo").hide();

			
			}else if($('#btnVisitaCalendario').hasClass('btn-account-sel')){
				planVisita = 'visita';
				$('#btnPlanRuteo').removeClass("seleccionado");
				$("#btnVisitaRuteo").addClass("seleccionado");
				$("#tblSimbologiaMarcadoresRuteo").show();
			}
			
			//$('#divRespuesta').load("ajax/marcadoresRuteo.php",{fecha:fecha,idUsuario:idUsuario,planVisita:planVisita });
			
			fecha = year+'-'+month+'-'+day;
			$('#hdnFechaRuteo').val(fecha);
			//idUsuario = $('#hdnIdUser').val();
			if($('#btnPlanRuteo').hasClass('seleccionado')){
				planVisita = 'plan';
			}
			if($('#btnVisitaRuteo').hasClass('seleccionado')){
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
		
		function traeEntradasSalidas(idLote,idRuta){
			//alert(idLote);
			var idUsuario = $('#hdnIdUser').val(); 
			var repre = $('#hdnIdsFiltroUsuarios').val();
			var ids = $('#hdnIds').val(); 
			//cargandoInventario();
			cargandoInventario2();

			$('#divRespuesta').load("ajax/cargarEntradasSalidas.php",
				{
					idLote:idLote,
					idUsuario:idUsuario,
					ids:ids,
					repre:repre,
					idRuta:idRuta
				},function()
				{
					$('.tblEntradaSalidaInv').waitMe("hide");
				}
			);
			/*.done(function(res){
				//$("#divSubirDocumentosDatosPersonales").html(res);
				$("#divRespuesta").html(res);
				$('#divCargando').hide();
				if(repetido){
					//$('#lblMsjSubirArchivo').text('Archivo Existente');
					alert("Archivo existente");
				}else{
					$('#lblMsjSubirArchivo').text('Proceso concluido');
				}
				$('#divToast').show();
				$('#divToast').fadeOut(500);
			});*/
		}
		
		function ajuste(idProducto, fecha, producto){
			/*$('#divConfirmacionInventario').show();
			$('#over').show(500);
			$('#fade').show(500);*/
			$('#lblProductoAjuste').text(fecha + ' ' + producto);
			$('#hdnIdProductoAjuste').val(idProducto);
		}

		function ajusteInv(idProducto, fecha, producto){
			$('#lblProductoAjuste').text(fecha + ' ' + producto);
			$('#hdnIdProductoAjuste').val(idProducto);
			var idProdForm = $("#hdnIdProductoAjuste").val();
			$("#divAjusteMuestra").show();
			$("#over").show('slow');
			$("#fade").show();
			//$('body').addClass('no-scroll');
			$("#divRespuesta").load("ajax/cargarAjusteMaterial.php", {idProdForm:idProdForm});
		}

		function ajusteAceptar(idProducto, fecha, producto){
			$('#lblProductoAjuste').text(fecha + ' ' + producto);
			$('#hdnIdProductoAjuste').val(idProducto);
			var idProdForm = $("#hdnIdProductoAjuste").val();
			$("#"+idProducto+"Acep").addClass('btn-inv-head-focus');
			$("#divRespuesta").load("ajax/guardaAjusteMaterial.php",{idProdForm:idProdForm,cantidad:'',catidadAceptada:'',motivo:'',movimiento:'aceptado'}, function(){
				$("#"+idProducto+"Acep").removeClass('btn-inv-head-focus');
			});
		}

		function ajusteRechazar(idProducto, fecha, producto){
			//$('#lblProductoAjuste').text(fecha + ' ' + producto);
			$('#hdnIdProductoAjuste').val(idProducto);
			$("#"+idProducto+"Rech").addClass('btn-inv-head-focus');
			alertMotivoRechazo(idProducto,fecha, producto);
		}
		
		function pestanaInventario(pestana){
			$('#hdnPestana').val(pestana);
			if($('#btnPenSel').hasClass('btn-blue-sel')){
				$("#btnPenSel").removeClass("btn-blue-sel");
				$("#btnInvSel").addClass("btn-blue-sel");
				
				$("#btnInvSel").attr("disabled", true);
				$("#btnPenSel").removeAttr("disabled");

			}else if($('#btnInvSel').hasClass('btn-blue-sel')){
				$("#btnInvSel").removeClass("btn-blue-sel");
				$("#btnPenSel").addClass("btn-blue-sel");
				
				$("#btnPenSel").attr("disabled", true);
				$("#btnInvSel").removeAttr("disabled");

				if($('#btnPendienteAprobacion').hasClass('seleccionado')){
					if($('#hdnNombresFiltroUsuarios').val() == ''){
						$("#btnEjecutarFiltroInv").click();
					}
				}
			}
		}
		
		function transferirInventario(idStockLote, total){
			var ids = $('#hdnIds').val();
			$('#divTransferirMuestraMedica').show();
			$('#divCapa3').show();
			//cargandoVisita();
			$('#divRespuesta').load('ajax/cargarTransferirMuestraMedica.php',{idStockLote:idStockLote,total:total,ids:ids},function(){
				//$('#cargandoInfVisitaMed').waitMe("hide");
			});
		}
		
		/* fin de inventario*/
		
		/*aprobaciones*/
		
		function muestraAprobacion(id, tipo){
			//alert(id);
			$('#btnAceptarAprobacion').prop("disabled", false);
			if(tipo == 'p'){
				$('#hdnIdPersApproval').val(id);
				$('#hdnIdInstApproval').val('');
			}else if(tipo == 'i'){
				$('#hdnIdInstApproval').val(id);
				$('#hdnIdPersApproval').val('');
			}
			$('#divRespuesta').load('ajax/cargarAprobacion.php',{id:id,tipo:tipo});
			$('#divAprobacion').show();
			$('#over').show(500);
			$('#fade').show(500);
			//$('body').addClass('no-scroll');
		}
		
		function showTabsAprob(){
			if($('#btnInstSel').hasClass('btn-blue-sel')){
				$("#btnInstSel").removeClass("btn-blue-sel");
				$("#btnPersSel").addClass("btn-blue-sel");
				
				$("#btnPersSel").attr("disabled", true);
				$("#btnInstSel").removeAttr("disabled");
			}else if($('#btnPersSel').hasClass('btn-blue-sel')){
				$("#btnPersSel").removeClass("btn-blue-sel");
				$("#btnInstSel").addClass("btn-blue-sel");
				
				$("#btnInstSel").attr("disabled", true);
				$("#btnPersSel").removeAttr("disabled");
			}
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
				//alert('Tu sesión ha expirado.');
				alertSesionExpirada();
				//$('#imgLogout').click();
			}else{
				timer = setTimeout("temporizador()", 1000);
			}
			 
		}

		/* termna timer */
		
		/* firma */
		
		function iniciarFirmas(){
			//comenzar();
			//comenzarInst();
			$('#imgHome').click();
		}

		function limpiar(){
			var pintaH = (((screen.width - 650) / 2) + 50);
			lienzo = document.getElementById('canvasFirmaVisitas');
			ctx = lienzo.getContext('2d');
			ctx.clearRect(pintaH, 440, lienzo.width, lienzo.height);
			document.getElementById("hdnFirma").value = ''; 
			//alert('limpiar');
		}

		function guardarFirma2(){
			var canvasFirmaVisitas2 = document.getElementById('canvasFirmaVisitas2');
			var dataURL = canvasFirmaVisitas2.toDataURL(); 
			///crear canvas en blanco
			var blank = document.createElement('canvas');
			blank.width = canvasFirmaVisitas2.width;
			blank.height = canvasFirmaVisitas2.height;

			//if(canvasFirmaVisitas2.toDataURL() == blank.toDataURL()){
			if ($("#hdnFirma").val() == '') {
				//alert('La firma que intenta guardar está en blanco');
				alertFirmaBlanco();
				return true;
			}else{
				var productoCapturado = false;
				for(i=1;i<=$('#tblMuestras tbody tr').length;i++){
					if($('#text'+i).val() > 0){
						existencia = $("#hdnExistencia"+i).val();
						maximo = $("#hdnMaximo"+i).val();
						cantidad = $("#text"+i).val();
						if(parseInt(cantidad, 10) > parseInt(existencia, 10)){
							//alert("Solo tienes "+existencia+" piezas de ese producto!!!");
							alertExistenciaPiezas(existencia);
							$("#btnGuardarVisitas").prop("disabled", false);
							return;
						}
						if(parseInt(cantidad, 10) > parseInt(maximo, 10)){
							//alert("Sólo puede entregar "+maximo+" piezas de ese producto!!!");
							alertMasPiezas(maximo);
							$("#btnGuardarVisitas").prop("disabled", false);
							return;
						}
						productoCapturado = true;
						//break
					}
				}
				if(! productoCapturado){
					//alert('Debe entregar al menos un producto para guardar la firma');
					alertErrorFirmaProd();
					return true;
				}
				document.getElementById("hdnFirma").value = dataURL;
				for(i=1;i<=$('#tblMuestras tbody tr').length;i++){
					$('#text'+i).prop("disabled", "true");
				}
				$('#btnLimpiarFirma').prop("disabled", "true");
				$('#btnGuardarFirma').prop("disabled", "true");
				//$('#canvasFirmaVisitas2').prop("disabled", "true");
				$('#tblFirma').hide();
				$('#imgFirma').show();
				$('#imgFirma').attr('src', dataURL);
			}
		}
		
		function comenzarInst(){
			var pintaH = (((screen.width - 650) / 2) + 50) * -1;
			lienzo = document.getElementById('canvasFirmaVisitasInst');
			document.addEventListener('mousedown',pulsaRaton,false);
			document.addEventListener('mousemove',mueveRaton,false);
			document.addEventListener('mouseup',levantaRaton,false);
		}
		
		function limpiarInst(){
			var pintaH = (((screen.width - 650) / 2) + 50);
			lienzo = document.getElementById('canvasFirmaVisitasInst');
			document.getElementById("hdnFirmaInst").value = ''; 
		}
		
		function guardarFirmaInst(){
			var canvasFirmaVisitasInst = document.getElementById('canvasFirmaVisitasInst');
			var dataURL = canvasFirmaVisitasInst.toDataURL();
			
			///canvas en blanco
			var blankInst = document.getElementById('canvasInst');
			/*blankInst.width = canvasFirmaVisitasInst.width;
			blankInst.height = canvasFirmaVisitasInst.height;*/

			if(canvasFirmaVisitasInst.toDataURL() == blankInst.toDataURL()){
				//alert('La firma que intenta guardar está en blanco');
				alertFirmaBlanco();
				return true;
			}else{
				var productoCapturadoInst = false;
				for(i=1;i<=$('#tblMuestrasVisitasInst tbody tr').length;i++){
					if($('#textInst'+i).val() > 0){
						existenciaInst = $("#hdnExistenciaInst"+i).val();
						maximoInst = $("#hdnMaximoInst"+i).val();
						cantidadInst = $("#textInst"+i).val();
						if(parseInt(cantidadInst, 10) > parseInt(existenciaInst, 10)){
							//alert("Solo tienes "+existenciaInst+" piezas de ese producto!!!");
							alertExistenciaPiezas(existenciaInst);
							//$("#btnGuardarVisitasInst").prop("disabled", false);
							return;
						}
						if(parseInt(cantidadInst, 10) > parseInt(maximoInst, 10)){
							//alert("Sólo puede entregar "+maximoInst+" piezas de ese producto!!!");
							alertMasPiezas(maximoInst);
							//$("#btnGuardarVisitasInst").prop("disabled", false);
							return;
						}
						productoCapturadoInst = true;
						//break;
					}
				
				}
				if(! productoCapturadoInst){
					//alert('Debe entregar al menos un producto para guardar la firma');
					alertErrorFirmaProd();
					return true;
				}
				document.getElementById("hdnFirmaInst").value = dataURL; 
				for(i=1;i<=$('#tblMuestrasVisitasInst tbody tr').length;i++){
					$('#textInst'+i).prop("disabled", "true");
				}
				$('#btnLimpiarFirmaInst').prop("disabled", "true");
				$('#btnGuardarFirmaInst').prop("disabled", "true");
				$('#tblFirmaInst').hide();
				$('#imgFirmaInst').show();
				$('#imgFirmaInst').attr('src', dataURL);
			}
		}

		function guardarFirmaInst2(){
			var canvasFirmaVisitasInst2 = document.getElementById('canvasFirmaVisitasInst2');
			var dataURL = canvasFirmaVisitasInst2.toDataURL(); 
			///crear canvas en blanco
			var blankInst = document.createElement('canvasInst2');
			blankInst.width = canvasFirmaVisitasInst2.width;
			blankInst.height = canvasFirmaVisitasInst2.height;

			if ($("#hdnFirmaInst").val() == '') {
				alertFirmaBlanco();
				return true;
			
			}else{
				var productoCapturadoInst = false;
				for(i=1;i<=$('#tblMuestrasVisitasInst tbody tr').length;i++){
					if($('#textInst'+i).val() > 0){
						existenciaInst = $("#hdnExistenciaInst"+i).val();
						maximoInst = $("#hdnMaximoInst"+i).val();
						cantidadInst = $("#textInst"+i).val();
						if(parseInt(cantidadInst, 10) > parseInt(existenciaInst, 10)){
							//alert("Solo tienes "+existenciaInst+" piezas de ese producto!!!");
							alertExistenciaPiezas(existenciaInst);
							//$("#btnGuardarVisitasInst").prop("disabled", false);
							return;
						}
						if(parseInt(cantidadInst, 10) > parseInt(maximoInst, 10)){
							//alert("Sólo puede entregar "+maximoInst+" piezas de ese producto!!!");
							alertMasPiezas(maximoInst);
							//$("#btnGuardarVisitasInst").prop("disabled", false);
							return;
						}
						productoCapturadoInst = true;
						//break;
					}
				
				}
				if(! productoCapturadoInst){
					//alert('Debe entregar al menos un producto para guardar la firma');
					alertErrorFirmaProd();
					return true;
				}
				document.getElementById("hdnFirmaInst").value = dataURL; 
				for(i=1;i<=$('#tblMuestrasVisitasInst tbody tr').length;i++){
					$('#textInst'+i).prop("disabled", "true");
				}
				$('#btnLimpiarFirmaInst').prop("disabled", "true");
				$('#btnGuardarFirmaInst').prop("disabled", "true");
				$('#tblFirmaInst').hide();
				$('#imgFirmaInst').show();
				$('#imgFirmaInst').attr('src', dataURL);
			}
		}
		
		/* fin firma */
		
		/************* filtros usuarios ***********/
		
		function filtrosUsuarios(div) {
			// rutasSlt();
			var idUser = $('#hdnIdUser').val();
			var ids = $('#hdnIds').val();
			if(! $('#tblUsuariosSeleccionados tr').length){
				$('#hdnIdsFiltroUsuarios').val('');
				$('#hdnNombresFiltroUsuarios').val('');
			}
			var idsFiltrados = $('#hdnIdsFiltroUsuarios').val();
			$('#hdnPantallaFiltroUsuarios').val(div);
			$('#txtBuscarFiltroUsuarios').val('');
			$('#divFiltrosUsuarios').show();
			$('#divCapa3').show('slow');

			$('#divRespuesta').load('ajax/cargarFiltroUsuarios.php',{idUser:idUser,ids:ids,palabra:'',pantalla:div,idsFiltros:idsFiltrados},function(){
				$('#txtBuscarFiltroUsuarios').click();
				$('#txtBuscarFiltroUsuarios').focus();
			});
		}

		
		function agregaDesRutas(texto, idchk, slt){
			var textoFinal = '';
			if($('#'+slt).text() != 'Seleccione'){
				textoFinal = $('#'+slt).text() + ',';
			}
			textoFinal += texto;
			$('#'+slt).text(textoFinal);
		}
		
		function seleccionarTodosRutas(chkTodosRutas,chks,idsChecks){
			totalChks = $('#'+chks).val();
			for(var i = 0;i <= totalChks;i++){
				$('#'+idsChecks+i).prop('checked', $('#'+chkTodosRutas).prop('checked'));
			}
		}
		
		function usuarioSeleccionado(id, nombre){
			var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroUsuarios').val();
			var renglon = "trUsuarioSeleccionado" + $("#tblUsuariosSeleccionados tr").length;
			var idsFiltros = $('#hdnIdsFiltroUsuarios').val() + id + ',';
			var nombresFiltros = $('#hdnNombresFiltroUsuarios').val() + nombre + ',';
			$('#tblUsuariosSeleccionados').append('<tr class="pointer" id="'+renglon+'" onclick="eliminarSeleccionado(\''+renglon+'\',\''+id+'\',\''+nombre+'\');"><td>'+nombre+'</td></tr>');
			$('#hdnIdsFiltroUsuarios').val(idsFiltros);
			$('#hdnNombresFiltroUsuarios').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroUsuarios.php",{ids:ids,palabra:palabra,idsFiltros:idsFiltros});
		}
		
		function eliminarSeleccionado(fila, id, nombre){
			var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroUsuarios').val();
			var idsFiltros = $('#hdnIdsFiltroUsuarios').val().replace(id+",","");
			var nombresFiltros = $('#hdnNombresFiltroUsuarios').val().replace(nombre + ',','');
			$('#'+fila).remove();
			$('#hdnIdsFiltroUsuarios').val(idsFiltros);
			$('#hdnNombresFiltroUsuarios').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroUsuarios.php",{ids:ids,palabra:palabra,idsFiltros:idsFiltros});
		}
		
		/*************termina filtros usuarios ***********/
		
		/***************filtro Mensajes******************/
		
		function filtrosUsuariosMensajes() {
			// rutasSlt();
			var idUser = $('#hdnIdUser').val();
			var ids = $('#hdnIds').val();
			if(! $('#tblUsuariosSeleccionadosFiltrosMensajes tr').length){
				$('#hdnIdsFiltroUsuariosMensajes').val('');
				$('#hdnNombresFiltroUsuariosMensaje').val('');
			}
			var idsFiltrados = $('#hdnIdsFiltroUsuariosMensajes').val();
			$('#txtBuscarFiltroUsuariosMensaje').val('');
			//$('#divFiltrosUsuarios').show();
			//$('#divCapa3').show('slow');

			$('#divRespuesta').load('ajax/cargarFiltroMensaje.php',{idUser:idUser,palabra:'',idsFiltros:idsFiltrados},function(){
				$('#txtBuscarFiltroUsuariosMensaje').click();
				$('#txtBuscarFiltroUsuariosMensaje').focus();
			});
		}

		
		function agregaDesRutas(texto, idchk, slt){
			var textoFinal = '';
			if($('#'+slt).text() != 'Seleccione'){
				textoFinal = $('#'+slt).text() + ',';
			}
			textoFinal += texto;
			$('#'+slt).text(textoFinal);
		}
		
		function seleccionarTodosRutas(chkTodosRutas,chks,idsChecks){
			totalChks = $('#'+chks).val();
			for(var i = 0;i <= totalChks;i++){
				$('#'+idsChecks+i).prop('checked', $('#'+chkTodosRutas).prop('checked'));
			}
		}
		
		function usuarioSeleccionadoMensaje(id, nombre){
			var idRemitente = $('#hdnIdUser').val();
			var palabra = $('#txtBuscarFiltroUsuariosMensaje').val();
			var renglon = "trUsuarioSeleccionadoMensaje" + $("#tblUsuariosSeleccionadosFiltrosMensajes tr").length;
			var idsFiltros = $('#hdnIdsFiltroUsuariosMensajes').val() + id + ',';
			var nombresFiltros = $('#hdnNombresFiltroUsuariosMensaje').val() + nombre + ',';
			$('#tblUsuariosSeleccionadosFiltrosMensajes').append('<tr class="pointer" id="'+renglon+'" onclick="eliminarSeleccionadoMensaje(\''+renglon+'\',\''+id+'\',\''+nombre+'\');"><td>'+nombre+'</td></tr>');
			$('#hdnIdsFiltroUsuariosMensajes').val(idsFiltros);
			$('#hdnNombresFiltroUsuariosMensaje').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroMensaje.php",{idRemitente:idRemitente,palabra:palabra,idsFiltros:idsFiltros});
		}
		
		function eliminarSeleccionadoMensaje(fila, id, nombre){
			var idRemitente = $('#hdnIdUser').val();
			var palabra = $('#txtBuscarFiltroUsuariosMensaje').val();
			var idsFiltros = $('#hdnIdsFiltroUsuariosMensajes').val().replace(id+",","");
			var nombresFiltros = $('#hdnNombresFiltroUsuariosMensaje').val().replace(nombre + ',','');
			$('#'+fila).remove();
			$('#hdnIdsFiltroUsuariosMensajes').val(idsFiltros);
			$('#hdnNombresFiltroUsuariosMensaje').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroMensaje.php",{idRemitente:idRemitente,palabra:palabra,idsFiltros:idsFiltros});
		}
		
		/*************termina filtro usuarios************/


		/*************filtro mercados********************/
		
		function filtroMercados(){
			idsFiltros = $('#hdnIdsFiltroMercados').val();
			$('#divFiltrosMercados').show();
			$('#divCapa3').show();
			$('#divRespuesta').load("ajax/cargarFiltroMercados.php",{palabra:'',idsFiltros:idsFiltros});
		}
		
		function mercadoSeleccionado(id, nombre){
			var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroMercados').val();
			//alert($("#tblProductosSeleccionados tr").length);
			var renglon = "trMercadoSeleccionado" + $("#tblMercadosSeleccionadosFiltros tr").length;
			var idsFiltros = $('#hdnIdsFiltroMercados').val() + id + ',';
			var nombresFiltros = $('#hdnNombresFiltroMercados').val() + nombre + ',';
			$('#tblMercadosSeleccionadosFiltros').append('<tr id="'+renglon+'" onclick="eliminarMercadoSeleccionado(\''+renglon+'\',\''+id+'\',\''+nombre+'\');"><td>'+nombre+'</td></tr>');
			$('#hdnIdsFiltroMercados').val(idsFiltros);
			$('#hdnNombresFiltroMercados').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroMercados.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		function eliminarMercadoSeleccionado(fila, id, nombre){
			//var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroMercados').val();
			var idsFiltros = $('#hdnIdsFiltroMercados').val().replace(id+",","");
			var nombresFiltros = $('#hdnNombresFiltroMercados').val().replace(nombre + ',','');
			//alert(fila);
			$('table#tblMercadosSeleccionadosFiltros tr#'+fila).remove();
			$('#hdnIdsFiltroMercados').val(idsFiltros);
			$('#hdnNombresFiltroMercados').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroMercados.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		/*************termina filtro mercados************/
		
		/*************filtro periodos********************/
		
		function filtroPeriodos(){
			idsFiltros = $('#hdnIdsFiltroPeriodos').val();
			$('#divFiltrosPeriodos').show();
			$('#divCapa3').show();
			$('#divRespuesta').load("ajax/cargarFiltroPeriodos.php",{palabra:'',idsFiltros:idsFiltros});
		}
		
		function periodoSeleccionado(id, nombre){
			var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroPeriodos').val();
			//alert($("#tblProductosSeleccionados tr").length);
			var renglon = "trPeriodoSeleccionado" + $("#tblPeriodosSeleccionadosFiltros tr").length;
			var idsFiltros = $('#hdnIdsFiltroPeriodos').val() + id + ',';
			var nombresFiltros = $('#hdnNombresFiltroPeriodos').val() + nombre + ',';
			$('#tblPeriodosSeleccionadosFiltros').append('<tr id="'+renglon+'" onclick="eliminarPeriodoSeleccionado(\''+renglon+'\',\''+id+'\',\''+nombre+'\');"><td>'+nombre+'</td></tr>');
			$('#hdnIdsFiltroPeriodos').val(idsFiltros);
			$('#hdnNombresFiltroPeriodos').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroPeriodos.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		function eliminarPeriodoSeleccionado(fila, id, nombre){
			//var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroPeriodos').val();
			var idsFiltros = $('#hdnIdsFiltroPeriodos').val().replace(id+",","");
			var nombresFiltros = $('#hdnNombresFiltroPeriodos').val().replace(nombre + ',','');
			//alert(fila);
			$('table#tblPeriodosSeleccionadosFiltros tr#'+fila).remove();
			$('#hdnIdsFiltroPeriodos').val(idsFiltros);
			$('#hdnNombresFiltroPeriodos').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroPeriodos.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		/*************termina filtro periodos************/

		/************filtros ciclos reportes ************/
		
		function filtroProductos(){
			idsFiltros = $('#hdnIdsFiltroProductos').val();
			$('#divFiltrosProductos').show();
			$('#divCapa3').show();
			$('#divRespuesta').load("ajax/cargarFiltroProductos.php",{palabra:'',idsFiltros:idsFiltros});
		}
		
		function productoSeleccionado(id, nombre){
			var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroProductos').val();
			//alert($("#tblProductosSeleccionados tr").length);
			var renglon = "trProductoSeleccionado" + $("#tblProductosSeleccionadosFiltros tr").length;
			var idsFiltros = $('#hdnIdsFiltroProductos').val() + id + ',';
			var nombresFiltros = $('#hdnNombresFiltroProductos').val() + nombre + ',';
			$('#tblProductosSeleccionadosFiltros').append('<tr id="'+renglon+'" onclick="eliminarProductoSeleccionado(\''+renglon+'\',\''+id+'\',\''+nombre+'\');"><td>'+nombre+'</td></tr>');
			$('#hdnIdsFiltroProductos').val(idsFiltros);
			$('#hdnNombresFiltroProductos').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroProductos.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		function eliminarProductoSeleccionado(fila, id, nombre){
			//var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroProductos').val();
			var idsFiltros = $('#hdnIdsFiltroProductos').val().replace(id+",","");
			var nombresFiltros = $('#hdnNombresFiltroProductos').val().replace(nombre + ',','');
			//alert(fila);
			$('table#tblProductosSeleccionadosFiltros tr#'+fila).remove();
			$('#hdnIdsFiltroProductos').val(idsFiltros);
			$('#hdnNombresFiltroProductos').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroProductos.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		/*function cicloSeleccionado(id, nombre){
			var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroCiclos').val();
			var renglon = "trCicloSeleccionado" + $("#tblCiclosSeleccionados tr").length;
			var idsFiltros = $('#hdnIdsFiltroCiclos').val() + id + ',';
			var nombresFiltros = $('#hdnNombresFiltroCiclos').val() + nombre + ',';
			$('#tblCiclosSeleccionados').append('<tr id="'+renglon+'" onclick="eliminarCicloSeleccionado(\''+renglon+'\',\''+id+'\',\''+nombre+'\');"><td>'+nombre+'</td></tr>');
			$('#hdnIdsFiltroCiclos').val(idsFiltros);
			$('#hdnNombresFiltroCiclos').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroCiclos.php",{palabra:palabra,idsFiltros:idsFiltros});
		}
		
		function eliminarCicloSeleccionado(fila, id, nombre){
			//var ids = $('#hdnIds').val();
			var palabra = $('#txtBuscarFiltroCiclos').val();
			var idsFiltros = $('#hdnIdsFiltroCiclos').val().replace(id+",","");
			var nombresFiltros = $('#hdnNombresFiltroCiclos').val().replace(nombre + ',','');
			$('#'+fila).remove();
			$('#hdnIdsFiltroCiclos').val(idsFiltros);
			$('#hdnNombresFiltroCiclos').val(nombresFiltros);
			$('#divRespuesta').load("ajax/cargarFiltroCiclos.php",{palabra:palabra,idsFiltros:idsFiltros});
		}*/
		
		/************termina filtro ciclos reportes *****/
		
		/***********funciones reportes*****************/
		
		function nuevaPaginaListados(pagina, ids, listado, estatus){
			$("#divCargando").show();
			$('#divRespuesta').load("ajax/cargar" + listado + ".php",{pagina:pagina,ids:ids,estatus:estatus});
		}
		
		/************termina funciones reportes*************/
		
		function cambiarFecha(texto){
			fecha = $('#'+texto).val().substring(6,10)+"-"+$('#'+texto).val().substring(3,5)+"-"+$('#'+texto).val().substring(0,2);
			$('#'+texto).val(fecha);
		}
		
		function cerrarInformacion(){
			$('#cerrarInformacion').click();
		}
		
		function MM_openBrWindow(theURL,winName,features) { //v2.0
		  $('#divRespuesta').load("ajax/descargaArchivo.php",{file:theURL});
		}
		/*function cerrarInformacion(){
			$('#cerrarInformacion').click();
		}*/

		function start(){
			iniciarFirmas();
		}
	
		var expandedListadosPersonas = false;
		var expandedListadosInst = false;

		function showCheckboxesListadosPersonas() {
			var checkboxes = document.getElementById("checkboxesListadosPersonas");
			if (!expandedListadosPersonas) {
				checkboxes.style.display = "block";
				expandedListadosPersonas = true;
			} else {
				checkboxes.style.display = "none";
				expandedListadosPersonas = false;
			}
		}

		function showCheckboxesListadosInst() {
			var checkboxes = document.getElementById("checkboxesListadosInst");
			if (!expandedListadosInst) {
				checkboxes.style.display = "block";
				expandedListadosInst = true;
			} else {
				checkboxes.style.display = "none";
				expandedListadosInst = false;
			}
		}
		
		function agregaDesListadosPersonas(texto, check){
			var textoChk = '';
			if($('#'+check).prop('checked')){
				if($("#sltMultiSelectListadosPersonas").text() == 'Seleccione'){
					textoChk = texto + ";";
				}else{
					textoChk = $("#sltMultiSelectListadosPersonas").text() + texto + ";";
				}
			}else{
				textoChk = $("#sltMultiSelectListadosPersonas").text().replace(texto + ";", '');
			}
			$("#sltMultiSelectListadosPersonas").text(textoChk);
		}
		
		/*eventos*/
		function traeAsistentesEventos(idEvento, tr){
			$('#tblEventos tbody tr').removeClass("tablaSeleccionado");
			$("#"+tr).addClass("tablaSeleccionado");
			$('#hdnTrSeleccionadaEvento').val(tr);
			$('#divRespuesta').load("ajax/cargarVisitantesEvento.php",{idEvento:idEvento});
		}
		
		function marcarAsistir(idEvento, idEventoPersona){
			asistentes = $('#hdnAsistentes').val();
			faltantes = $('#hdnFaltantes').val();
			idEvenPers = idEventoPersona;
			if(asistentes.indexOf(idEventoPersona) > -1){
				attended = 0;
			}else{
				attended = 1;
			}
			$('#divRespuesta').load("ajax/guardaAsistenteEvento.php",{idEvento:idEvento,idEvenPers:idEvenPers,attended:attended});
		}
		
		function invitarEvento(idPersona, div){
			invitados = $('#hdnIvitadosEventos').val();
			if($('#' + div).hasClass('circuloRojo')){
				$('#' + div).removeClass('circuloRojo');
				$('#' + div).addClass('circuloVerde');
				invitados += idPersona + ";";
			}else{
				$('#' + div).removeClass('circuloVerde');
				$('#' + div).addClass('circuloRojo');
				invitados = invitados.replace(idPersona + ";", "");
			}
			$('#hdnIvitadosEventos').val(invitados);
		}
		
		function editarEvento(idEvento, tr){
			idUsuario = $('#hdnIdUser').val();
			$('#divEventoEditar').show();
			$('#divCapa3').show('slow');
			$('#divRespuesta').load("ajax/cargarEvento.php",{idEvento:idEvento,idUsuario:idUsuario,renglonSeleccionado:tr});
		}
		
		function eliminarEvento(idEvento, tr){
			idUsuario = $('#hdnIdUser').val();
			var tipoUsuario = $('#hdnTipoUsuario').val();
			if(confirm("¿Desea eliminar este evento?")){
				$.ajax({
					type: "post",
					url:"ajax/eliminarEvento.php",
					data:{
						idEvento:idEvento, renglonSeleccionado:tr, idUsuario:idUsuario
					},success:function(datosResp){
						alert(datosResp);
						$('#divRespuesta').load("ajax/cargarEventos.php",{tipoUsuario:tipoUsuario}, function(){
							$('#inventario').waitMe("hide");
						});
					}
				});
				
			}
		}
		
		function calcularCampoAlfa(){
			var flonorm = $('select[id=sltFlonorm]').val();
			var vessel = $('select[id=sltVessel]').val();
			var ateka = $('select[id=sltAteka]').val();
			var zirfos = $('select[id=sltZirfos]').val();
			var esoxx = $('select[id=sltEsoxx]').val();
			
			
			$.ajax({
				type: "post",
				url:"ajax/calcularCategoria.php",
				data:{
					flonorm:flonorm, vessel:vessel, ateka:ateka, zirfos:zirfos, esoxx:esoxx
				},success:function(datosResp){
					var opcion = parseInt(datosResp, );
					switch(opcion){
						case 1:
							$('#sltCatAlfa').val("C8B11F59-2D13-4AD5-A5C5-4F64C71F6476");
							break;
						case 2:
							$('#sltCatAlfa').val("57EEEE95-92E9-4AE5-B084-44DA44A2A27D");
							break;
						case 3:
							$('#sltCatAlfa').val("020DB855-B7E4-463A-925B-66C9D6595076");
							break;
						case 4:
							$('#sltCatAlfa').val("562F940A-432D-4104-8B35-0F6D0DDDDE8E");
							break;
						case 5:
							$('#sltCatAlfa').val("DC4E8587-F9B2-4CA2-BF8D-FF21260A6D5A");
							break;
						default:
							$('#sltCatAlfa').val("00000000-0000-0000-0000-000000000000");
							break;
					}
				}
			});
		}
		
		function eliminarPersonaEvento(idPersonaEvento){
			invitados = $('#hdnIvitadosEventos').val();
			idEvento = $('#hdnIdEventoEditar').val();
			
			//lo encuentra 
			if(invitados.indexOf(idPersonaEvento) > -1){
				invitados = invitados.replace(idPersonaEvento + ';' ,'');
				idPersonaEvento = "";
				
			}
			$("#divRespuesta").load("ajax/cargarTablaInvitadosEventos.php",{invitados:invitados, idEvento:idEvento, idPersona:idPersonaEvento});
		}
		
		function llenaIdEventoAgregar(id, tr){
			$("#hdnIdEventoAgregar").val(id);
			$('#tblEventosAgregar tbody tr').removeClass("tablaSeleccionado");
			$("#"+tr).addClass("tablaSeleccionado");
			
		}
		
		
		/*termina eventos*/
		/*inicia inversiones*/
		
		function editarInversion(idInversion){
			$("#divInversiones").show();
			$("#divCapa3").show();
			$("#divRespuesta").load("ajax/cargarInversion.php",{idInversion:idInversion});
		}
		
		/*termina inversiones*/
		
		/*inicia mensajes*/
		
		function muestraMensaje(idMensaje, tabMsj){
			$("#divRespuesta").load("ajax/cargarMensaje.php",{idMensaje:idMensaje,tabMsj:tabMsj});
		}
		
		function eliminarMensaje(idMensaje, tabMsj){
			$("#divRespuesta").load("ajax/eliminarMensaje.php",{idMensaje:idMensaje,tabMsj:tabMsj});
		}
		
		/*termina mensajes*/
		
		//btnCheck1
			
		function insertCheck(){
			var type=1;
			 
			idUsuario = $('#hdnIdUser').val();
			valchek= $("#hdnCheck").val();
			var lat = $("#hdnLatitude").val();
			var lon = $("#hdnLongitude").val();
			
			$('#divRespuesta').load("ajax/guardarCheck.php",{idUsuario:idUsuario,valchek:valchek,lat:lat,lon:lon,type:type});
		}
	</script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body onLoad="start();" class="theme-indigo">
	<!-- Page Loader -->
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-red">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
			<p>Por favor espere...</p>
		</div>
	</div>
	<!-- #END# Page Loader -->
	<input type="hidden" id="txtCanvas" value="" />
	<input type="hidden" id="hdnLatitude" value="" />
	<input type="hidden" id="hdnLongitude" value="" />
	<input type="hidden" id="hdnCicloActivo" value="<?= $cicloActivo ?>" />
	<input type="hidden" id="hdnMed" value="" />


	<!-- Overlay For Sidebars -->
	<div class="overlay"></div>
	<!-- #END# Overlay For Sidebars -->
	<!-- Search Bar -->
	<div class="search-bar">
		<div class="search-icon">
			<i class="material-icons">search</i>
		</div>
		<input type="text" placeholder="Buscar...">
		<div class="close-search">
			<i class="material-icons">close</i>
		</div>
	</div>
	<!-- #END# Search Bar -->
	<!-- Top Bar -->
<?php
		$queryTipoUsuario = "select * from users where rec_stat = 0 and user_snr = '".$_GET['idUser']."'";
		$rsTipoUsuario = sqlsrv_query($conn, $queryTipoUsuario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsTipoUsuario) > 0){
			while($row = sqlsrv_fetch_array($rsTipoUsuario)){
				$tipoUsuario = $row['USER_TYPE'];
				$rutaEtiqueta = $row['LNAME']." ".$row['FNAME'];
			}
			$rutaEtiqueta = strtolower($rutaEtiqueta);
			$nombreUsuario = explode(" ",$rutaEtiqueta);
?>
	<nav class="navbar">
		<div class="container-fluid p-l-0">
			<div class="navbar-header">
				<li class="imageUserInfo">
					<div class="m-t-5">
						<div class="image">
							<img src="images/user.png" alt="User" />
						</div>
					</div>
				</li>
				<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"
				 aria-expanded="false"></a>
				<a href="javascript:void(0);" class="bars"></a>
				<div class="div-navbar-brand" style="display:none;">
				</div>
				<a class="navbar-brand">
					<div class="div-logo-smart"><img class="logo-smart" src="images/logoSmart.png" /></div>
				</a>
			</div>
			<div class="collapse navbar-collapse bg-indigo" id="navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="align-right">
						<div style="margin-top: 16px;" class="infoUser">
							<div class="font-13 name">
								<?php if($tipoUsuario == 2){ ?>
									<?= utf8_encode($rutaEtiqueta) ?>
								<?php } else{ ?>
									<?= utf8_encode($rutaEtiqueta) ?>
									<!--$nombreUsuario[0]  -  //$nombreUsuario[4] ?>  //$nombreUsuario[2] -->
								<?php } ?>
							</div>
							<div class="font-11">
								Ciclo:
								<?= $cicloActivo ?>
							</div>
						</div>
					</li>
					<li class="imageUser">
						<div style="margin-top: 13px;">
							<div class="image">
								<img src="images/user.png" alt="User" />
							</div>
						</div>
					</li>
					<li><span class="separator"> | </span></li>
					<li>
						<div class="timerUser">
							<span id="lblTimer">2:00:00</span>
						</div>
					</li>
					<li><span class="separator"> | </span></li>
					<!-- Settings-->
					<li data-toggle="tooltip" data-placement="bottom" title="Configuración">
						<a id="imgConfig" style="cursor:pointer;color: rgba(255, 255, 255, 0.51);"><i class="material-icons">settings</i></a>
					</li>
					<!-- #END# Settings -->
					<!-- sign out-->
					<li data-toggle="tooltip" data-placement="bottom" title="Salir">
						<a id="imgLogout" style="cursor:pointer;color: rgba(255, 255, 255, 0.51);"><i class="material-icons">input</i></a>
					</li>
					<!-- #END# sign out -->
					<!--<li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>-->
				</ul>
			</div>
		</div>
	</nav>
	<!-- #Top Bar -->
	<section>
		<!-- Left Sidebar -->
		<aside id="leftsidebar" class="sidebar">
			<!-- Menu -->
			<div class="menu">
				<ul class="list">
					<li class="header">MENÚ PRINCIPAL</li>
					<li id="imgHome" class="hover2 active">
						<a>
							<i class="material-icons font-28">home</i>
							<span class="font-17">Inicio</span>
						</a>
					</li>
					<!--<li class="menu-no-overlay">-->
					<li>
						<a href="javascript:void(0);" class="menu-toggle">
							<i class="material-icons font-28">group</i>
							<span class="font-17">Cuentas</span>
						</a>
						<ul class="ml-menu">
							<li id="imgPersonas" class="hover2">
								<a>
									<i class="fas fa-user-md font-22"></i>
									<span class="font-17">Médicos</span>
								</a>
							</li>
							<li id="imgInstituciones" class="hover2" style="display:none;">
								<a>
									<i class="fas fa-building font-22"></i>
									<span class="font-17">Instituciones</span>
								</a>
							</li>
							<li id="imgHospitales" class="hover2">
								<a>
									<i class="fas fa-hospital font-22 m-l--4 m-r-4"></i>
									<span class="font-17">Hospitales</span>
								</a>
							</li>
							<li id="imgFarmacias" class="hover2">
								<a>
									<i class="fas fa-pills font-22 m-l--6"></i>
									<span class="font-17">Farmacias</span>
								</a>
							</li>
						</ul>
					</li>

<?php
				if($tipoUsuario == 2 || $tipoUsuario == 5){
?>
					<li id="imgAprobaciones" class="hover2">
						<a>
							<i class="material-icons font-28">assignment_turned_in</i>
							<span class="font-17">Aprobaciones</span>
						</a>
					</li>
<?php
				}
			}
?>
					<li id="imgCalendario" class="hover2">
						<a>
							<i class="fas fa-calendar-alt p-t-5 font-22 p-l-5"></i>
							<span class="font-17">Calendario</span>
						</a>
					</li>
					<li id="imgEventos" class="hover2">
						<a>
							<i class="fas fa-calendar-check p-t-5 font-22 p-l-5"></i>
							<span class="font-17">Eventos</span>
						</a>
					</li>
					<li id="imgInversiones" class="hover2">
						<a>
							<i class="fas fa-dollar-sign p-t-5 font-22 p-l-5"></i>
							<span class="font-17">Inversiones</span>
						</a>
					</li>
					<li id="imgMensajes" class="hover2">
						<a>
							<i class="fas fa-envelope-square p-t-5 font-22 p-l-5"></i>
							<span class="font-17">Mensajes</span>
						</a>
					</li>
					<!--<li class="hover2">
							<a href="javascript:void(0);" >
								<i class="material-icons">ondemand_video</i>
								<span>CLM</span>
							</a>
						</li>-->
					<li id="imgGeo" class="hover2">
						<a>
							<i class="material-icons font-28">place</i>
							<span class="font-17">Localizador</span>
						</a>
					</li>
					<li id="imgDocumentosEntregados" class="hover2">
						<a>
							<i class="material-icons font-28">cloud_upload</i>
							<span class="font-17">Documentos</span>
						</a>
					</li>
					<li id="imgInventario" class="hover2">
						<a>
							<i class="material-icons font-28">shopping_cart</i>
							<span class="font-17">Inventario</span>
						</a>
					</li>
					<li id="imgReportes" class="hover2">
						<a>
							<i class="fas fa-chart-line p-t-7 font-22"></i>
							<span class="font-17">Reportes</span>
						</a>
					</li>
					<li id="imgListados" class="hover2">
						<a>
							<i class="far fa-list-alt p-t-7 font-22"></i>
							<span class="font-17">Listados</span>
						</a>
					</li>
					<!--<li class="hover2">
							<a href="javascript:void(0);">
								<i class="material-icons">monetization_on</i>
								<span>Ventas</span>
							</a>
							
						</li>-->
				</ul>
			</div>
			<!-- #Menu -->
			<!-- Footer -->
			<div class="legal">
				<div class="copyright">
					<a href="javascript:void(0);">Smart Scale - For Precise Decisions</a>.
				</div>
				<div class="version">
					&copy; 2019
				</div>
			</div>
			<!-- #Footer -->
		</aside>
		<!-- #END# Left Sidebar -->
	</section>


	<div id="divSecundario">

		<input type="hidden" id="hdnTipoUsuario" value="<?= $tipoUsuario ?>" />
		<div id="divRespuesta"></div>

		<div id="divInicio" style="display:block;">
			<?php 
				include "inicio.php"; 
			?>
		</div>

		<div id="divInstituciones" style="display:none;">
			<?php 
				include "instituciones.php";
			?>
		</div>

		<div id="divPersonas" style="display:none;">
			<?php 
				include "personas.php"; 
			?>
		</div>

		<div id="divCalendario" style="display:none;overflow:scroll;">
			<?php 
				include "calendario.php"; 
			?>
		</div>
		
		<div id="divEvento" style="display:none;overflow:auto;">
			<?php 
				include "eventos.php"; 
			?>
		</div>
		
		<div id="divInversionesLista" style="display:none;overflow:auto;">
			<?php 
				include "inversion.php"; 
			?>
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

		<div id="divListados" style="display:none;">
			<?php include "listados.php"; ?>
		</div>

		<div id="divReportes" style="display:none;">
			<?php include "reportes.php"; ?>
		</div>

		<div id="divGeo" style="display:none;">
			<?php include "localizador.php" ?>
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

		<div id="divReporteador" style="display:none;">
			<?php include "reporteador.php"; ?>
		</div>

		<div id="divListador" style="display:none;">
			<?php include "listado.php"; ?>
		</div>
	</div>

	<!--<div id="divCapa3" style="visibility: hidden; display:block;">-->
		<div id="divCapa3" style="display:none;">
		<div id="divPlanes">
			<?php include "planes.php"; ?>
		</div>

		<div id="divVisitas" style="display:none;">
			<?php include "visitas.php"; ?>
		</div>

		<div id="divPersona" style="display:none;" class="cardCapa3">
			<?php include "persona.php"; ?>
		</div>

		<div id="divPlanesInst" style="display:none;">
			<?php include "planesInst.php"; ?>
		</div>

		<div id="divVisitasInst" style="display:none;">
			<?php include "visitasInst.php"; ?>
		</div>

		<div id="divInstitucion" style="display:none;">
			<?php include "institucion.php"; ?>
		</div>

		<div id="divFiltrosUsuarios" style="display:none;">
			<?php include "filtroUsuarios.php"; ?>
		</div>

		<div id="divDepartamento" style="display:none;">
			<?php include "departamento.php"; ?>
		</div>

		<div id="divFiltrosProductos" style="display:none;">
			<?php include "filtroProductos.php"; ?>
		</div>
		
		<div id="divFiltrosMercados" style="display:none;">
			<?php include "filtroMercados.php"; ?>
		</div>
		
		<div id="divFiltrosPeriodos" style="display:none;">
			<?php include "filtroPeriodos.php"; ?>
		</div>

		<!--<div id="divFiltrosCiclos" style="display:none;">
			<?php //include "filtroCiclos.php"; ?>
		</div>-->
		
		<div id="divEventoEditar" style="display:none;">
			<?php include "evento.php"; ?>
		</div>
		
		<div id="divEventosAgregar" hidden>
			<?php include "eventosAgregar.php"; ?>
		</div>
		
		<div id="divTransferirMuestraMedica" style="width:50%;" hidden>
			<?php include "transferirMuestraMedica.php"; ?>
		</div>
		<div id="divInversiones" hidden>
			<?php include "inversiones.php"; ?>
		</div>
		
		<div id="divMensaje" hidden>
			<?php include "mensaje.php"; ?>
		</div>
		
	</div>

	<div id="divCapa4">
		<div id="divCompetidores" style="display:none;">
			<?php include "competidores.php"; ?>
		</div>

		<div id="divFirma" style="display:none;">
			<?php include "firma.php"; ?>
		</div>
		
		<div id="divBuscarPersonasEventos" style="display:none;">
			<?php include "buscarPersonaEventos.php"; ?>
		</div>
		
		<div id="divFiltrosMensaje" style="display:none;">
			<?php include "filtroMensaje.php"; ?>
		</div>
	</div>

	<div id="divCargando">
		<!--<div id="divCargandoGif" class="loader"></div>
			<label id="lblLeyendaCargandoArchivo">Subiendo archivo, por favor espere...</label>-->
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-blue">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>


			</div>
			<p id="lblLeyendaCargandoArchivo"></p>
		</div>
	</div>

	<div id="divToast">
		<div id="divToastP"><label id="lblMsjSubirArchivo"></label></div>
	</div>


	<!-- lightbox -->
	<div id="over" class="overbox">
		<div>
			<img src="iconos/close.png" id="cerrarInformacion" width="30px" title="Cerrar" style="display:none;" />
			<!--<div id="divDatosPersonales" style="display:none;">
						<?php //include "datosPersonales.php"; ?>
					</div>-->
			<!--<div id="divDatosInstituciones"  style="display:none;">
						<?php //include "datosInstituciones.php"; ?>
					</div>-->
			<div id="divPlanearOtrasActividades" style="display:none;">
				<?php include "planearOtrasActividades.php"; ?>
			</div>
			<div id="divMensaje" style="display:none;">
				<?php include "mensaje.php"; ?>
			</div>
			<div id="divSupervision" style="display:none;">
				<?php include "supervision.php"; ?>
			</div>

			<div id="divAjusteMuestra" style="display:none;">
				<?php include "ajusteMaterial.php"; ?>
			</div>

			<div id="divConfirmacionInventario" style="display:none">

				<br><br><br>
				<table border="0" bgcolor="#FFFFFF" id="tblAjustInv" align="center">
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
					<tr id="trMotivoRechazoMuestra" style="display:none;">
						<td colspan="3" align="center">
							<table border="0" width="60%">
								<tr>
									<td>
										Motivo de rechazo:
									</td>
									<td>
										<select id="sltMotivoRechazoMuestra">
											<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
												$rsMotivo = llenaCombo($conn, 374, 638);
												while($motivo = sqlsrv_fetch_array($rsMotivo)){
													echo '<option value="'.$motivo['id'].'">'.utf8_encode($motivo['nombre']).'</option>';
												}
?>
										</select>
									</td>
								</tr>
								<tr>
									<td align="center">
										<button id="btnAceptarMotivoRechazoMuestra">Aceptar</button>
									</td>
									<td align="center">
										<button id="btnCancelarMotivoRechazoMuestra">Cancelar</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<!--<tr id="trBotonesRechazoMuestra">
						<td align="center"><button id="btnAjustarInv">Ajustar</button></td>
						<td align="center"><button id="btnAceptarAjusteInv">Aceptar</button></td>
						<td align="center"><button id="btnCancelarAjusteInv">Rechazar</button></td>
					</tr>-->
				</table>

			</div>

			<div id="divAprobacion" style="display:none;">
				<div class="row">
					<div id="divContAprobacion" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
						<div class="card m-b--15 card-add-new">
							<div class="header row padding-0">
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-10 m-t-10 m-b-10">
									<button type="button" id="btnAceptarAprobacion"
										class="btn btn-success waves-effect btn-green">
										<i class="far fa-thumbs-up font-18"></i><span class="top-0">Aceptar</span>
									</button>
									<button type="button" id="btnRechazarAprobacion"
										class="btn btn-danger waves-effect btn-red m-l-10">
										<i class="far fa-thumbs-down font-18"></i><span class="top-0">Rechazar</span>
									</button>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-4 col-xs-10 m-t-10 m-b-10">
									<div class="form-group">
										<input type="text" value="" id="txtCodigoPersonaAprobaciones" class="form-control" placeholder="Cod_Pers" required />
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
									<p id="btnCancelarAprobacion" class="pointer p-t-5 btn-close-per" onClick="cerrarInformacion();">
										<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
									</p>
								</div>
								<input type="hidden" id="hdnIdPersApproval" />
								<input type="hidden" id="hdnIdInstApproval" />
							</div>
							<div class="body">
								<div class="aprobacion-div add-scroll-y">
									<div id="divMotivoRechazo" class="row" style="display:none;">
										<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Motivo:</label>
												<select id="sltMotivoRechazo" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
													<?php
															$rsMotivoRechazo = llenaCombo($conn, 297, 516);
															while($regMotivoRechazo = sqlsrv_fetch_array($rsMotivoRechazo)){
																echo '<option value="'.$regMotivoRechazo['id'].'">'.$regMotivoRechazo['nombre'].'</option>';
															}
						?>
												</select>
											</div>
											<button id="btnAceptarRechazo" class="btn bg-indigo waves-effect btn-indigo">
												Aceptar
											</button>
											<button id="btnCancelarRechazo" class="btn bg-indigo waves-effect btn-indigo">
												Cancelar
											</button>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive">
												<table id="tblAprobacion" class="table table-striped">
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="divMotivoBaja" style="display:none;">
				<div class="row m-r--15 m-l--15">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
						<div id="divContMotivoBaja" class="card m-b--15 card-add-new">
							<input type="hidden" id="hdnIdPersonaBaja" value="" />
							<div class="header row padding-0">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 m-t-15">
									<h2>Eliminar Médico</h2>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 align-right m-t-10">
									<p id="btnCancelarBaja" class="pointer p-t-5  btn-close-per">
										<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
									</p>
								</div>
							</div>
							<div class="body padding-0">

								<div class="row">
									<div class="add-scroll-y" style="height:95%;">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20">
											<div class="bg-cyan nombre-cuenta font-16">
												<i class="fas fa-user-md font-16"></i>
												<span id="lblNombreMedEliminar" class="p-l-5"></span>
											</div>
											<div class="card margin-0 card-plan-visita">
												<div class="body">
													<div id="divDatosMedicoMotivoBaja">
														<p><span id="lblTipoMedEliminar" class="bg-grey label-esp"></span></p>
														<span id="lblDatosMedicoMotivoBaja"></span>
													</div>
												</div>
											</div>
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
											<label class="col-red">Motivo de la baja * </label>
											<select id="sltMotivoBaja" class="form-control">
												<option value="">Seleccione</option>
												<?php
												//$rsMotivoBaja = llenaComboBajas($conn, 14, 264);
												$rsMotivoBaja = llenaComboBajas($conn, 19, 11);
												while($regMotivoBaja = sqlsrv_fetch_array($rsMotivoBaja)){
													echo '<option value="'.$regMotivoBaja['id'].'">'.utf8_encode($regMotivoBaja['nombre']).'</option>';
												}
?>
											</select>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
											<label>Comentarios adicionales</label>
											<textarea id="txtComentariosBaja" rows="4" class="text-notas2"></textarea>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-center">
											<button id="btnAceptarBaja" class="btn bg-indigo waves-effect btn-indigo">Eliminar</button>
											<!--<button id="btnCancelarBaja" class="btn bg-indigo waves-effect btn-indigo">Cancelar</button>-->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="divMotivoBajaInst" style="display:none;">
				<div class="row m-r--15 m-l--15">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
						<div id="divContMotivoBajaInst" class="card m-b--15 card-add-new">
							<input type="hidden" id="hdnIdInstBaja" value="" />
							<div class="header row padding-0">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 m-t-15">
									<h2>Eliminar Institución</h2>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 align-right m-t-10">
									<p id="btnCancelarBajaInst" class="pointer p-t-5  btn-close-per">
										<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
									</p>
								</div>
							</div>
							<div class="body padding-0">

								<div class="row">
									<div class="add-scroll-y" style="height:95%;">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-t-20">
											<div class="bg-cyan nombre-cuenta font-16">
												<i class="fas fa-building font-16"></i>
												<span id="lblNombreInstEliminar" class="p-l-5"></span>
											</div>
											<div class="card margin-0 card-plan-visita">
												<div class="body">
													<div id="divDatosInstMotivoBaja">
														<p><span id="lblTipoInstEliminar" class="bg-grey label-esp"></span></p>
														<span id="lblDatosInstMotivoBaja"></span>
													</div>
												</div>
											</div>
										</div>

										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
											<label class="col-red">Motivo de la baja * </label>
											<select id="sltMotivoBajaInst" class="form-control">
												<option value="">Seleccione</option>
												<?php
												$rsMotivoBaja = llenaComboBajas($conn, 14, 6);
												while($regMotivoBaja = sqlsrv_fetch_array($rsMotivoBaja)){
													echo '<option value="'.$regMotivoBaja['id'].'">'.utf8_encode($regMotivoBaja['nombre']).'</option>';
												}
	?>
											</select>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
											<label>Comentarios adicionales</label>
											<textarea id="txtComentariosBajaInst" rows="4" class="text-notas2"></textarea>
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-center">
											<button id="btnAceptarBajaInst" class="btn bg-indigo waves-effect btn-indigo">Eliminar</button>
											<!--<button id="btnCancelarBaja" class="btn bg-indigo waves-effect btn-indigo">Cancelar</button>-->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="divCopiarPlanes" style="display:none;">
				<?php include "copiarPlanes.php"; ?>
			</div>

			<div id="divRutaNueva" style="display:none;">
				<?php include "rutaNueva.php"; ?>
			</div>

			<div id="divPlanesRapidos" style="display:none;">
				<?php 
					//include "planesRapidos.php"; 
				?>
			</div>

		</div>
	</div>

	<div id="over2" class="overbox2">
		<div id="divAprobacionesPers" style="display:none;">
			<?php include "aprobacionesPers.php" ?>
		</div>
		<div id="divAprobacionesInst" style="display:none;">
			<?php include "aprobacionesInst.php" ?>
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
	</div>
	<div id="fade" class="fadebox">&nbsp;</div>
	<!-- fin de lightbox -->

	<!-- Jquery Core Js -->
	<script src="external/jquery/jquery.js"></script>
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
	<script src="jquery-ui.js"></script>

	<script>
		function notificationArchivoSubido() {
			$.notify({
				icon: 'glyphicon glyphicon-ok-sign',
				message: 'El archivo ha sido subido exitosamente'
			},{
				type: "success",
				allow_dismiss: true,
				placement: {
					from: "top",
					align: "center"
				},
				template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
					'<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
					'<span data-notify="icon" class="m-r-5 font-13"></span> ' +
					'<span data-notify="title">{1}</span> ' +
					'<span data-notify="message">{2}</span>' +
					'</div>'
			});
		}

		$(function(){
			$("#formuploadajax").on("submit", function(e){
				//$('#lblLeyendaCargandoArchivo').text('Subiendo archivo, por favor espere...');
				//$('#divCargando').show();
				cargandoDocumentos();
				$("#btnArchivo").attr("disabled", true);
				$("#btnEnviarArchivo").attr("disabled", true);
				$("#btnFiltrarDocs").attr("disabled", true);
					
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
					$('#divCargando').hide();
					if(repetido){
						//$('#lblMsjSubirArchivo').text('Archivo Existente');
						alertExisteArchivo();
					}else{
						//$('#lblMsjSubirArchivo').text('Proceso concluido');
						//alert('El archivo ha sido subido exitosamente');
						notificationArchivoSubido();
					}
					/*$('#divToast').show();
					$('#divToast').fadeOut(500);*/
					$("#tblSubirDocumentosDatosPersonales").waitMe("hide");
					$("#btnArchivo").removeAttr("disabled");
					$("#btnEnviarArchivo").removeAttr("disabled");
					$("#btnFiltrarDocs").removeAttr("disabled");
				});
			});
		});

		$("#divGridInstituciones").tabs();
		$("#tabFiltros").tabs();
		$("#tabFiltros2").tabs();
		$("#tabFiltrosInstituciones").tabs();
		$("#tabsInstituciones").tabs();
		$("#tabsDepartamentos").tabs();
		$("#tabsInventario").tabs();
		$("#tabSupervision").tabs();
		$("#tabOtrasActividades").tabs();
		$("#divAprobacionesGerente").tabs();
		$("#divGridPersonas").tabs();
		$("#tabsVisitas").tabs();
		$("#tabsPersona").tabs();
		$("#tabsVisitasInst").tabs();
		$("#tabsInst").tabs();
		$('#tabsDatosPersonales').tabs();
		$('#tabsPersonasInst').tabs();
		$('#tabsPerfilPersona').tabs();
		$('#tabsPerfilInst').tabs();
		$('#tabsEventoEditar').tabs();
		$('#tabsMensajes').tabs();

		$(function($) {
			$.fn.hasScrollBar = function() { 
				return this.get(0).scrollHeight > this.height(); 
			}
		});

		$(function () {
			$("#txtFechaInicioInstituciones").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			$("#txtFechaTerminoInstituciones").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			$("#txtFechaOtrasActividades").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			$("#txtFechaOtrasActividadesFinal").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			$('#txtFechaInicioListados').datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$('#txtFechaFinListados').datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaInicioReportes").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			var fechaInicioRep = new Date();
			fechaInicioRep = $("#txtFechaInicioReportes").val();
			//alert(fechaInicioRep);
			$("#txtFechaFinReportes").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				minDate: fechaInicioRep,
				format: 'yyyy-mm-dd'
			});
			//$("#txtFechaFinReportes").datepicker("setDate", fechaInicioRep);
		});

		$(function () {
			var fechaInicioRep = new Date();
			fechaInicioRep = $("#txtFechaInicioReportes").val();
			
			$("#txtFechaFinReportes").datepicker("setDate", fechaInicioRep);
		});

		$(function () {
			$("#txtFechaSupervision").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			$("#txtFechaSiguienteSupervision").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			$("#txtFechaConclusiones").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true
			});
		});

		$(function () {
			var date = new Date(); 
			var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
			$("#txtFechaReportarOtrasActividades").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'dd-mm-yyyy'
			});
		});

		$(function () {
			$("#txtFechaReportarOtrasActividadesFin").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'dd-mm-yyyy'
			});
		});

		$(function () {
			$("#txtFechaPlan").datepicker({
				format: 'yyyy-mm-dd',
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date()
			});
		});

		$(function () {
			$("#txtFechaVisita").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaPlanInst").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date(),
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaIcopiarPlanes").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date(),
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaFcopiarPlanes").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date(),
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaOcopiarPlanes").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date(),
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaVisitasInst").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaInicialEventoEditar").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		});

		$(function () {
			$("#txtFechaFinalEventoEditar").datepicker({
				changeMonth: false,
				changeYear: false,
				todayBtn: "linked",
				language: "es",
				autoclose: true,
				todayHighlight: true,
				format: 'yyyy-mm-dd'
			});
		})

		$(document).ready(function () {
			$('[data-toggle="tooltip"]').tooltip({
				trigger: "hover"
			});
		});

		$(function () {
			$('#tblFirma').signaturePad({
				drawOnly: true,
				drawBezierCurves: true,
				lineTop: 220,
				variableStrokeWidth:true
			});
		});

		$(function () {
			$('#tblFirmaInst').signaturePad({
				drawOnly: true,
				drawBezierCurves: true,
				lineTop: 220,
				variableStrokeWidth:true
			});
		});
		
		/*$(function () {
			$('#sltMercado').selectpicker();
		});*/
		
		
		
	</script>

	
	<!--<script src="plugins/jquery/jquery.min.js"></script>-->

	<!-- Bootstrap Core Js -->
	<script src="plugins/bootstrap/js/bootstrap.js"></script>

	<!-- Select Plugin Js -->
	<!--<script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>-->

	<!-- Slimscroll Plugin Js -->
	<script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- Waves Effect Plugin Js -->
	<script src="plugins/node-waves/waves.js"></script>

	<!-- SweetAlert Plugin Js -->
	<script src="plugins/sweetalert/sweetalert.min.js"></script>

	<!-- Jquery CountTo Plugin Js -->
	<script src="plugins/jquery-countto/jquery.countTo.js"></script>

	<!-- Jquery Validation Plugin Css -->
	<script src="plugins/jquery-validation/jquery.validate.js"></script>

	<!-- JQuery Steps Plugin Js -->
	<!--<script src="plugins/jquery-steps/jquery.steps.js"></script>-->

	<!-- Wait Me Plugin Js -->
	<script src="plugins/waitme/waitMe.js"></script>

	<!--Sumo Select-->
	<script src="plugins/sumo-select/jquery.sumoselect.min.js"></script>

	<!-- Bootstrap Material Datetime Picker Plugin Js -->
	<!--	<script src="plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>-->

	<!-- Bootstrap Datepicker Plugin Js -->
	<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js" charset="UTF-8"></script>

	<!-- Bootstrap Notify Plugin Js -->
	<script src="plugins/bootstrap-notify/bootstrap-notify.js"></script>

	<!-- Custom Js -->
	<script src="js/admin.js"></script>
	<script src="js/pages/index.js"></script>
	<script src="js/demo.js"></script>
	<script src="js/pages/tables/jquery-datatable.js"></script>
	<script src="js/pages/forms/basic-form-elements.js"></script>
	<script src="js/pages/cards/colored.js"></script>
	<script src="js/pages/forms/basic-form-elements.js"></script>
	<script src="js/pages/ui/dialogs.js?v=1.0"></script>
	<script src="js/pages/ui/notifications.js"></script>
	<script src="js/pages/forms/form-validation.js"></script>

	<!--Signature-->
	<script src="plugins/signature-pad-master/jquery.signaturepad.js"></script>
	<script src="plugins/signature-pad-master/assets/json2.min.js"></script>

	<!-- Jquery DataTable Plugin Js -->
	<script src="plugins/jquery-datatable/jquery.dataTables.js"></script>
	<script src="plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
	<script src="plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>
</body>

</html>