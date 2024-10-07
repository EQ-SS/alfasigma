<?php
require('calendario/calendario.php');
$meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
?>
<!DOCTYPE html>
	<head>
		<link href="jquery-ui.css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<!--<script type="text/javascript" src="js/jquery-1.3.1.min.js"></script>-->
		<script type="text/javascript" src="calendario/js/jquery.functions.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyD-tf5PgJGx6iHEtZ-4W0ynr-Fgfzarch0"></script>
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
					$('#'+div).show();
					if(div == 'divGeo'){
						//alert('hi');
						initialize();
					}
				}
				
				$('#imgHome').click(function(){
					aparece('divInicio');
				});
				
				$('#imgPersonas').click(function(){
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
					aparece('divGeo');
				});
				
				$('#imgConfig').click(function(){
					aparece('divConfig');
				});
				
				/* fin menu prindipal*/
				
				/*personas*/
				
				$('#lstProductoDatosPersonales').change(function(){
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
				
				/*fin de personas*/
				
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
				
				$('#imgFiltrarInstitucion').click(function(){
					$('#divFiltrosInstituciones').show();
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
					$('#fade').hide();
				});
				
				$('#imgAgregarPlan').click(function(){
					var ancho = 600;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("planes.php", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
				
				$('#imgAgregarVisita').click(function(){
					var ancho = 700;
					var alto = 600;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("visitas.php", "vtnVisitas", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				});
				
				$('#imgAgregarPersona').click(function(){
					var ancho = 900;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("persona.php", "vtnPersona", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				});
				
				$('#imgAgregarInstitucion').click(function(){
					var ancho = 900;
					var alto = 600;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("institucion.php", "vtnInstitucion", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
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
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("persona.php", "vtnPersona", "width="+ancho+",height="+alto+",top="+y+",left="+x+",menubar=no,resizable=no");
				});
				
				$('#imgAgregarPlanInstituciones').click(function(){
					var ancho = 600;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("planes.php", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
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
					$('#over').show(1000);
					$('#fade').show(1000);
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
				
				/*inventario*/
				$('#imgApareceInventario').click(function(){
					$('#trBuscaInventario').show();
					$('#imgOcultaInventario').show();
					$('#imgApareceInventario').hide();
				});
				
				$('#imgOcultaInventario').click(function(){
					$('#trBuscaInventario').hide();
					$('#imgOcultaInventario').hide();
					$('#imgApareceInventario').show();
				});
				/* fin de inventario*/
				
				$('#lkMapa').click(function(){
					load_map('map_canvas');
					$('#txtCanvas').val('map_canvas');
				});
				
				$('#lkMapaInstituciones').click(function(){
					load_map('map_canvas2');
					$('#txtCanvas').val('map_canvas2');
				});
				
			});			
			
			/*mapa*/
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
				getDireccion(markerLatLng.lat(), markerLatLng.lng());
			}
			
			function load_map(map) {
				//alert(mapa);
				var myLatlng = new google.maps.LatLng(20.68009, -101.35403);
				var myOptions = {
					zoom: 4,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map = new google.maps.Map($("#"+map).get(0), myOptions);
				//map = new google.maps.Map($("#map_canvas2").get(0), myOptions);
				//map2 = new google.maps.Map($('#map_convas2').get(0), myOptions);
			}
			 
			$('#search').live('click', function() {
				// Obtenemos la dirección y la asignamos a una variable
				var address = $('#address').val();
				// Creamos el Objeto Geocoder
				var geocoder = new google.maps.Geocoder();
				// Hacemos la petición indicando la dirección e invocamos la función
				// geocodeResult enviando todo el resultado obtenido
				geocoder.geocode({ 'address': address}, geocodeResult);
			});
			
			$('#searchInstituciones').live('click', function() {
				// Obtenemos la dirección y la asignamos a una variable
				var address = $('#addressInstituciones').val();
				// Creamos el Objeto Geocoder
				var geocoder = new google.maps.Geocoder();
				// Hacemos la petición indicando la dirección e invocamos la función
				// geocodeResult enviando todo el resultado obtenido
				geocoder.geocode({ 'address': address}, geocodeResult);
			});
			 
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
			
					google.maps.event.addListener(marker, 'click', function(){
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
			function initialize() {
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
			}
			/*termina radar*/
			
			function presentaDatos(id, div, medico, especialidad){
				$('#divDatosPersonales').hide();
				$('#divDatosInstituciones').hide();
				$('#divPlanearOtrasActividades').hide();
				$('#divMensaje').hide();
				$('#'+div).show();
				$('#over').show(1000);
				$('#fade').show(1000);
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
			}

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
			
			/*radar*/
			
			function initialize() {
 
				var latlng = new google.maps.LatLng(19.394614, -99.1719215);
				var mapOptions = {
					zoom: 16,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
				}
				var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
				setMarkers(map, marcadores);
			}
 
			var marcadores = [
				['Dr. Hernández',19.39595995659153,-99.17081642988586,'Dr. Hernández'],
				['Dra. Delgado',19.397235063093895, -99.17466808203125,'Dra. Delgado'],
				['Dr. Juarez',19.396627870767425, -99.17262960317993,'Dr. Juarez'],
				['Dra. Luna',19.394836640204566, -99.17376685980224,'Dra. Luna'],
				['Dr. Villegas',19.39411811844976, -99.16756559255981,'Dr. Villegas'],
			];

			var infowindow;
			function setMarkers(map, marcadores) {
				for (var i = 0; i < marcadores.length; i++) {
					var myLatLng = new google.maps.LatLng(marcadores[i][1], marcadores[i][2]);
					var marker = new google.maps.Marker({
						position: myLatLng,
						map: map,
						title: marcadores[i][0],
					});
					(function(i, marker) {
						google.maps.event.addListener(marker,'click',function() {
							if (!infowindow) {
								infowindow = new google.maps.InfoWindow();
							}
							infowindow.setContent(marcadores[i][3]);
							infowindow.open(map, marker);
						});
					})(i, marker);
				}
			};
			/*termina radar*/
		</script>
		
		<style type="text/css">
			#mapa { height: 500px; }
			body{
				font-family: Arial;
				font-size: 12px;
				background: #68B0AB;
			}
			h1{
				color: blue;
				text-decoration: underline;
			}			
			
			img{
				cursor: pointer;
			}
			
			button{
				border-radius: 5px;
			}
			
			#divMenu {
				border-radius: 10px;
				width: 1000px;
				height: 50px;
				background: #E6E6E6;
				box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
				box-shadow: 0 0 20px rgba(0,0,0,0.8);
			}
				
			#divInicio, 
			#divPersonasDatos, 
			#divCalendario, 
			#divCiclos, 
			#divMensajes, 
			#divInventario, 
			#divReportes, 
			#divGeo,
			#divConfig{
				width: 1000px;
				height: 500px;
				background: white;
				box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
				box-shadow: 0 0 20px rgba(0,0,0,0.8);
			}
						
			#divPersonas, #divInstituciones{
				width: 100%;
				height: 550px;
			}
						
			.zoomIt{
				display:block!important;
				-webkit-transition:-webkit-transform 1s ease-out;
				-moz-transition:-moz-transform 1s ease-out;
				-o-transition:-o-transform 1s ease-out;
				-ms-transition:-ms-transform 1s ease-out;
				transition:transform 1s ease-out;
			}
			.zoomIt:hover{
				-moz-transform: scale(1.1);
				-webkit-transform: scale(1.1);
				-o-transform: scale(1.1);
				-ms-transform: scale(1.1);
				transform: scale(1.5);
				cursor: pointer;
			}
			
			.tituloModulo{
				font-family: Arial;
				color: grey;
				font-size:20px;
				font-weight: bold;
				text-decoration: underline;
			}
			
			.fadebox {
				display: none;
				position: absolute;
				top: 0%;
				left: 0%;
				width: 100%;
				height: 100%;
				background-color: black;
				z-index:1001;
				-moz-opacity: 0.6;
				opacity:.6;
				filter: alpha(opacity=60);
			}
			.overbox {
				display: none;
				position: absolute;
				top: 10%;
				left: 5%;
				width: 90%;
				height: 70%;
				z-index:1002;
				overflow: auto;
			}
			
			#tabs, #tabsFiltros, #tabFiltrosInstituciones{
				font-family: Arial;
				font-size: 12px;
			}
			
			#tblRepresentantes, #tblPlan, #tblVisitas, #tblMultimedia, #tblHorario, #tblInicio {
				font-family: Arial;
				font-size: 12px
			}
			
			#tblRepresentantes thead, #tblPlan thead, #tblVisitas thead, #tblMultimedia thead{
				font-weight: bold;
				font-size: 14px;
				color: #FFFFFF;
				background: gray;
			}
			
			.negrita{
				font-weight: bold;
				
			}
			
			.tablas{
				font-family: Arial;
				font-size: 12px;
			}
			
			.titulo{
				font-family: Arial;
				font-size: 14px;
				color: #FFFFFF;
				background: gray;
				font-weight: bold;
			}		
			
			.grid{
				font-family: Arial;
				font-size: 12px;
				border-spacing: 0px 0px;
			}
			
			.grid thead, .grid tfoot{
				background:#C0C0C0;
				font-size: 14px;
				font-weight:bold;
			}
			
			.grid tbody tr:nth-child(2n){
				background: #F0F8FF;
			}
			
			.grid tbody tr:nth-child(2n+1){
				background: #B0E0E6;
			}
			
			.grid tbody tr:hover{
				background:#1E90FF;
				color:#FFFFFF;
			}
			
			.grid tbody td{
				padding: 0px 10px 0px 0px;
			}
			
			.borde{
				background: url("iconos/cal2.png");
				text-align: center;
				background-repeat: no-repeat;
				background-position: center center;
				background-size: 100% 100%;
			}
			
			.lblMedicos{
				font-size: 10px;
				color: blue;
				font-weight: bold;
			}
			
			.botones{
				font-family: Arial;
				font-size: 13px;
				font-weight: bold;
				border-spacing: 0px 5px;
			}
			
			.botones td{
				border: 1px black solid;
				height: 20px;
				padding: 0px 10px 0px 0px;
			}
			
			.botones td:hover{
				background:#1E90FF;
				color:#FFFFFF;
				cursor: pointer;
			}
			
			.imgTitulo{
				width: 30px;
				height: 30px;
			}
			
			.imgBoton{
				width: 25px;
				height: 25px;
			}
			
		</style>
	</head>
	<body>
		<input id="txtCanvas" type="hidden" value="" />
		<center>
			<div id="divMenu">
				<table id="tblMenu" width="800px" border="0">
					<tr>
						<td>
							<img class="zoomIt" src="iconos/casa.png" title="Inicio" id="imgHome" width="40px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/medicos.png" title="Personas" id="imgPersonas" width="40px"/>
						</td>						
						<td>
							<img class="zoomIt" src="iconos/empresa.png" title="Instituciones" id="imgInstotuciones" width="40px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/calendario.png" title="Calendario" id="imgCalendario" width="40px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/radar.png" title="Geolocalización" id="imgGeo" width="40px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/cal.png" title="Calendario del mercado" id="imgCalendarioMercado" width="40px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/ojo.png" width="40px"/>							
						</td>
						<td>
							<img class="zoomIt" src="iconos/inventario.png" title="Inventario" id="imgInventario" width="40px"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/reportes.png" width="40px" id="imgReportes" title="Reportes"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/checkbox.png" id="imgMensajes" width="40px" title="Mensajes"/>
						</td>
						<td>
							<img class="zoomIt" src="iconos/engrane.ico" id="imgConfig" width="40px" title="Opciones"/>
						</td>
					</tr>
				</table>
			</div>
			<br>
			
			<div id="divInicio" style="display:block;">
				<?php include "inicio.php"; ?>
			</div>
			
			<div id="divInstituciones" style="display:none;">
				<?php include "instituciones.php" ?>
			</div>
			
			<div id="divPersonas" style="display:none">
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
				<br><br>
				<div id="mapa" style="width:950px;height:450px;"></div>
			</div>
			
			<div id="divConfig" style="display:none;">
				<?php include "cambioPassword.php" ?>
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
				</div>
			</div>
			<div id="fade" class="fadebox">&nbsp;</div>
			<!-- fin de lightbox -->
			
		</center>
		
		<script src="external/jquery/jquery.js"></script>
		<script src="jquery-ui.js"></script>
		<script>
			$( "#divGridInstituciones" ).tabs();
			$( "#tabFiltros" ).tabs();
			$( "#tabFiltros2" ).tabs();
			$( "#tabFiltrosInstituciones" ).tabs();
			$( "#tabsInstituciones" ).tabs();
			$( "#tabsDepartamentos").tabs();
			$( "#tabsInventario" ).tabs();
			
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
		</script>
	</body>
</html>