<html>
	<head>
		<link href="jquery-ui.css" rel="stylesheet">
		<style type="text/css">
			
			#tabs{
				font-family: Arial;
				font-size: 12px;
			}
			
			#tblRepresentantes, #tblPlan, #tblVisitas, #tblMultimedia, #tblHorario {
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
			
			#dialog{
				 margin: 30px;
    background-color: #ffffff;
    border: 1px solid black;
    opacity: 0.6;
    filter: alpha(opacity=60)
			}
			
			
		</style>
	</head>
	<body>
		<table width="100%">
			<tr>
				<td>
					<h1>Personas</h1><br>
				</td>
			</tr>
			<tr>
				<td>
					<div id="dialog" >
					<div id="tabs">
						<ul>
							<li><a href="#tabs-1">Información de la persona</a></li>
							<li><a href="#tabs-2">Reprentantes</a></li>
							<li><a href="#tabs-3">Plan</a></li>
							<li><a href="#tabs-4">Visitas</a></li>
							<li><a href="#tabs-5">Multimedia</a></li>
							<li><a href="#tabs-6">Muestras/Material</a></li>
							<li><a href="#tabs-7">Horario de trabajo</a></li>
						</ul>
						
						<div id="tabs-1">
							<table width="100%" border="0" class="tablas">
								<tr>
									<td colspan="2" class="titulo">
										Informacióin de la persona
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Apellido Paterno:
									</td>
									<td>
										López
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Apellido Materno
									</td>
									<td>
										Martínez
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Nombre:
									</td>
									<td>
										Maria Concepcion
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Sexo:
									</td>
									<td>
										Femenino
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Esp Primaria:
									</td>
									<td>
										MEDICINA GENERAL
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Sub Especialidad:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Pacientes por semana
									</td>
									<td>
										<select id="lstPacientesSemana">
											<option>1-25</option>
											<option>26-50</option>
											<option>51-75</option>
											<option>más de 75</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Honorarios:
									</td>
									<td>
										<select id="lstHonorarios">
											<option>Súper altos</option>
											<option>Altos</option>
											<option>Medios</option>
											<option>Bajos</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Edad:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Categoría:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Cédula:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Frecuencia:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Dificultad de la visita:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Lider de opinión:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Botiquin:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Iguala:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Campo abierto 1:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Campo abierto 2:
									</td>
									<td>
										
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Campo abierto 3:
									</td>
									<td>
										
									</td>
								</tr>
							</table>
						</div>
						
						<div id="tabs-2">
							<table border="0" width="100%" id="tblRepresentantes">
								<thead>
									<tr>
										<td>
											Representante
										</td>
										<td>
											última visita
										</td>
										<td>
											Siguiente visita
										</td>
										<td>
											No interesante
										</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											15009 - GONZALEZ FERNANDEZ FRANCISCO ISAAC
										</td>
										<td>
											09.03.2016
										</td>
										<td>
											&nbsp;
										</td>
										<td>
											0
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<div id="tabs-3">
							<table width="100%">
								<tr>
									<td align="right">
										<img src="iconos/agregar.png" title="Agregar" width="40px" />
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%" id="tblPlan">
											<thead>
												<tr>
													<td>
														Fecha
													</td>
													<td>
														Realización
													</td>
													<td>
														Apellido Paterno
													</td>
													<td>
														Nombre
													</td>
													<td>
														Perfil
													</td>
												</tr>
											</thead>
										</table>
									</td>
								</tr>
							</table>
						</div>
						
						<div id="tabs-4">
							<table width="100%">
								<tr>
									<td style="font-family:Arial;font-size:12px;">
										<input type="checkbox" id="chkListaUsuariosActivos"/>Lista de usuarios activos
									</td>
									<td align="right">
										<img src="iconos/agregar.png" title="Agregar" width="40px" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<table width="100%" id="tblVisitas">
											<thead>
												<tr>
													<td>
														Fecha
													</td>
													<td>
														V/G
													</td>
													<td>
														Reprentante
													</td>
													<td>
														Código de visitas
													</td>
												</tr>
											</thead>
										</table>
									</td>
								</tr>
							</table>
						</div>
						
						<div id="tabs-5">
							<table border="0" width="100%" id="tblMultimedia">
								<thead>
									<tr>
										<td>
											Captura fecha
										</td>
										<td>
											Nombre del archivo
										</td>
										<td>
											Info
										</td>
									</tr>
								</thead>
							</table>
						</div>
						
						<div id="tabs-6">
							<center>
								<select id="lstYear">
<?php
									for($i=2007;$i<date("Y") + 2;$i++){
										if($i == date("Y")){
											echo '<option value="'.$i.'" selected>'.$i.'</option>';
										}else{
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									}
?>
								</select>
							</center>
							<table border="0" width="100%" id="tblMultimedia">
								<thead>
									<tr>
										<td>
											Captura fecha
										</td>
										<td>
											Nombre del archivo
										</td>
										<td>
											Info
										</td>
									</tr>
								</thead>
							</table>
						</div>
						
						<div id="tabs-7"><center>
							<b>Cilcos</b> 
							<select id='lstMesCiclo' >
								<option value="1">Semana 1</option>
								<option value="2">Semana 2</option>
								<option value="3">Semana 3</option>
								<option value="4">Semana 4</option>
							</select>
							<b>dia</b>
							<select id='lstDiaCiclo' >
<?php
								for($i=1;$i<21;$i++){
									echo '<option value="'.$i.'">Dia '.$i.'</option>';
								}
?>
							</select><br><br>
							<table border="0" width="50%" id="tblHorario" style="border: black 1px solid;">
								<tr>
									<td>&nbsp;</td>
									<td>AM</td>
									<td>PM</td>
									<td>Todo el día</td>
									<td>Previa cita</td>
								</tr>
								<tr>
									<td>Lunes</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Martes</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Miercoles</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Jueves</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Viernes</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Sábado</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Domingo</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td colspan="5" align="center"><hr style="border: black 1px dashed;"></td>
								</tr>
								<tr>
									<td>Anque</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Raro</td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
									<td><input type="checkbox" /></td>
								</tr>
								<tr>
									<td>Perfil</td>
									<td colspan="4">&nbsp;</td>
								</tr>
							</table></center>
						</div>						
					</div>
					</div><!-- emergente -->
					
					<br>
					<img src="iconos/flechaAtras.png" title="Atras" id="imgFlechaAtras" width="40px" />
				</td>
			</tr>
		</table>
		<script src="external/jquery/jquery.js"></script>
		<script src="jquery-ui.js"></script>
		<script>
			$( "#tabs" ).tabs();
			
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 800,
				height: 500,
			});
			
		</script>
	</body>
</html>