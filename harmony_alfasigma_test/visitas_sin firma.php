<html>
	<head>
		<title>Persona a visitar</title>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
		<script>
			$(document).ready(function(){
				$('#btnEncuesta').click(function(){
					$('#divEncuesta').show();
					$('#tabs').hide();
				})
				
				$('#btnCancelarEncuesta').click(function(){
					$('#divEncuesta').hide();
					$('#tabs').show();
				});
				
				$('#btnFirma').click(function(){
					$('#divContFirma').show();
				});
				
				$('#btnCancelarFirma').click(function(){
					$('#divContFirma').hide();
				});
				
				$('#btnSiguienteVisita').click(function(){
					var ancho = 600;
					var alto = 450;
					var x = (screen.width/2)-(ancho/2);
					var y = (screen.height/2)-(alto/2);
					var ventana = window.open("planes.php", "vtnPlan", "width="+ancho+",height="+alto+",top="+y+",left="+x+",resizable=no,location=no,menubar=no,status=no,toolbar=no");
				});
			});
		
			$.datepicker.regional['es'] = {
				closeText: 'Cerrar',
				prevText: '<Ant',
				nextText: 'Sig>',
				currentText: 'Hoy',
				monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
				dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
				dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
				dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
				weekHeader: 'Sm',
				dateFormat: 'dd/mm/yy',
				firstDay: 0,
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''
			};
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function () {
				$("#fecha").datepicker();
			});
 
			
		</script>
		<style type="text/css">
			body{
				font-family: Arial;
				font-size: 12px;
				background: #68B0AB;
			}
			
			input, select{
				-moz-border-radius: 5px;
				-webkit-border-radius: 5px;
			}
			
			button{
				border-radius: 5px;
			}
			
			#datos{
				border: black thin solid;
				background: #FFFFFF;
				box-shadow: 10px 5px 5px black;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				border-radius: 10px;
			}
			
			#tblVisita, #tblProductos, #tblMuestras, #tblFirma{
				font-family: Arial;
				font-size: 12px;
				background: #FFFFFF;
			}
			
			#spnPuntosHora{
				font-size: 20px;
				font-weight:bold;
			}
			
			#tabs{
				box-shadow: 10px 5px 5px black;
				
			}
			
			#tblMuestras thead td, #tblMuestras{
				border: black thin solid;
				border-collapse: collapse;
			}
			
			#divFirma{
				border: black thin solid;
				font-family: Arial;
				font-size: 12px;
				width: 300px;
				height: 150px;
				
			}
			
			.rojo{
				color:red;
				font-weight:bold;
			}
			
			.negrita{
				font-weight: bold;
				
			}
			
			.titulo{
				font-family: Arial;
				font-size: 14px;
				color: #FFFFFF;
				background: gray;
				font-weight: bold;
			}
		</style>
	</head>
	<body>
		<table width="100%">
			<tr>
				<td>
					<h3>Persona a visitar</h3>
				</td>
				<td align="right">
					<button id="btnSiguienteVisita" type="button">
						Planear Suiguiente Visita
					</button>
					<button id="btnEncuesta" type="button">
						<img src="iconos/encuesta.png" width="15px"> Encuesta
					</button>
					<button id="btnGuardar" type="button">
						<img src="iconos/ok.png" width="15px"> Guardar
					</button>
					<button name="btnCancelar" type="button">
						<img src="iconos/tache.png" width="15px"> Cancelar					 
					</button>
				</td>
			</tr>
		</table>
		<hr>
		<div id="divEncuesta" style="display:none;">
			<table width="100%" bgcolor="#FFFFFF" border="0">
				<tr>
					<td class="titulo" colspan="2">
						Encuesta de visita médica
					</td>
				</tr>
				<tr>
					<td colspan="2" class="negrita">
						1.	Dr(a) por favor mencione 3 médicos que para usted, desde el punto de vista científico, sean los más respetados en el área de Diabetes en su país.
					</td>
				</tr>
				<tr>
					<td width="20%">
						Médico 1:
					</td>
					<td>
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td width="20%">
						Médico 2:
					</td>
					<td>
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td width="20%">
						Médico 3:
					</td>
					<td>
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td colspan="2"><br>
						<hr>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="negrita"><br>
						2.	Dr(a) ¿Cuál es su pasatiempo favorito?
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td colspan="2"><br>
						<hr>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="negrita"><br>
						3.	Dr(a) ¿Cuáles son las 3 fechas más importantes que usted celebra?
					</td>
				</tr>
				<tr>
					<td width="10%">
						Fecha 1:
					</td>
					<td>
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td width="10%">
						Fecha 2:
					</td>
					<td>
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td width="10%">
						Fecha 3:
					</td>
					<td>
						<input type="text" size="50" />
					</td>
				</tr>
				<tr>
					<td colspan="2"><br>
						<hr>
					</td>
				</tr>
				<tr>
					<td colspan="2"><br>
						<form enctype="multipart/form-data" action="subir-archivos.php" method="POST" class="negrita">
							<input type="hidden" name="MAX_FILE_SIZE" value="250000" />
							Subir fotografía:
							<input name="archivo-a-subir" type="file" />
							<button id="btnGuardarEncuesta" type="button">
								<img src="iconos/upload.png" width="15px"> Subir Imagen
							</button>&nbsp;&nbsp;&nbsp;
						</form>
					</td>
				</tr>
				<tr>
					<td width="10%" colspan="2"><br>
						<button id="btnGuardarEncuesta" type="button">
							<img src="iconos/ok.png" width="15px"> Guardar Encuesta
						</button>
						<button id="btnCancelarEncuesta" type="button">
							<img src="iconos/tache.png" width="15px"> Cancelar Encuesta				 
						</button>
					</td>
				</tr>
			</table>
		</div>
		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Visita</a></li>
				<li><a href="#tabs-2">Productos</a></li>
				<li><a href="#tabs-3">Muestras</a></li>
			</ul>
			
			<div id="tabs-1">		
				<p style="margin-left:1em;">
					LIBETH AYERIN / CABAÑAS ZORRILLA<br>
					MEDICINA GENERAL<BR>
					CONS. PRIVADO PRIVADA A ORIENTE DE LA 16 DE SEPTIEMBRE NO.4317<br>
					A ORIENTE DE LA 16 DE SEPTIEMBRE NO.4317<BR>
					72534&nbsp;&nbsp;&nbsp;HUEXOTITLA<br>
					Brick: 00000001
				</p>
				<table width="100%" border="0" id="tblVisita" cellspacing="5">
					<tr>
						<td class="rojo">
							Representante:<br>
							<select>
								<option value="0">Seleccione</option>
							</select>
						</td>
						<td class="negrita">
							Fecha de la visita:<br>
							<input type="text" id="fecha" value="" />
						</td>
						<td class="negrita">
							Hora de la visita:<br>
							<select id="lstHora">
		<?php
								for($i=0;$i<24;$i++){
									echo '<option value="'.str_pad($i,2,'0').'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								}
		?>
							</select>
							<span id="spnPuntosHora">:</span>
							<select id="lstMinutos">
		<?php
								for($i=0;$i<60;$i++){
									echo '<option value="'.str_pad ($i,2,'0').'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								}
		?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="rojo">
							Código de la visita:<br>
							<select>
								<option value="0">Seleccione</option>
								<option value="1">Contcato personal</option>
								<option value="2">Sólo muestra médica</option>
							</select>
						</td>
						<td class="negrita">
							Visita acompañada:<br>
							<select>
								<option value="0">Seleccione</option>
								<option value="1">Gerente de Distrito</option>
								<option value="2">Gerente de Ventas</option>
								<option value="3">Gerente Nacional</option>
								<option value="4">Gerente de Capacitación</option>
							</select>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" class="rojo">
							Comentario de la visita:<br>
							<textarea rows="4" cols="100" value="">
							</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="3" class="negrita">
							Información de la siguiente visita:<br>
							<textarea rows="4" cols="100" value="">
							</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="3" class="negrita">
							Comentarios del médico:<br>
							<textarea rows="4" cols="100" value="">
							</textarea>
						</td>
					</tr>
				</table>
			</div>
			
			<div id="tabs-2" style="overflow:auto; height:410px;">
				<table border="0" id="tblProductos" width="100%" >
					<tr>
						<td class="negrita">Producto e indicación</td>
						<td class="negrita">% tiempo</td>
						<td class="negrita" colspan="2">Obsequios</td>
						<td class="negrita" colspan="2">Publicaciones y materiales</td>
						<td class="negrita" colspan="2">Comentarios</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2" valign="top">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px"/>
							</button>
						</td>
						<td rowspan="2" valign="top">
							<textarea rows="4" cols="15" value="">
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2" valign="top">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">	
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
					<tr>
						<td>
							<select>
								<option>Seleccione</option>
							</select>
						</td>
						<td>
							<input type="text" value="" size="3" />
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
						<td rowspan="2">
							<textarea rows="4" cols="15" value="">
								
							</textarea>
						</td>
						<td rowspan="2" valign="top">
							<button title="Agregar">
								<img src="iconos/agregar1.png" width="15px" title="Agregar"/>
							</button>
						</td>
					</tr>									
					<tr>
						<td colspan="2">
							<select><option>Seleccione</option></select>
							<select><option>Seleccione</option></select>
						</td>
					</tr>
				</table>
			</div>
			
			<div id="tabs-3">
				<table border="0" id="tblMuestras" width="100%" >
					<thead>
						<tr>
							<td class="negrita">&nbsp;Producto</td>
							<td class="negrita">&nbsp;Presentación del producto</td>
							<td class="negrita">&nbsp;Muestra</td>
							<td class="negrita">&nbsp;Lote del producto</td>
							<td class="negrita">&nbsp;Cantidad de<br>&nbsp;existencias del<br>&nbsp;usuarios</td>
							<td class="negrita">&nbsp;Cantidad</td>
						</tr>
					</thead>
					<tbody>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
						<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<br>
				<table width="100%" id="tblFirma" border="0">
					<tr>
						<td valign="top">
							<button id="btnFirma" type="button">
								<img src="iconos/firma.png" width="15px"> Firma
							</button>
						</td>
						<td align="center">
							<div id="divContFirma" style="display:none;">
								<div id="divFirma"></div><br>
								<button id="btnGuardarFirma" type="button">
									<img src="iconos/ok.png" width="10px"> Guardar
								</button>
								<button id="btnCancelarFirma" type="button">
									<img src="iconos/tache.png" width="10px"> Cancelar
								</button>
							</div>
						</td>
					</tr>
				</table>
			</div>
		
		</div>
	
	</body>
	<script src="external/jquery/jquery.js"></script>
	<script src="jquery-ui.js"></script>
	<script>
		$( "#tabs" ).tabs();
	</script>
	
</html>