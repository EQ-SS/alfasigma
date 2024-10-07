
		<link href="jquery-ui.css" rel="stylesheet">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
		<script>
			
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
				changeMonth: true,
				changeYear: true,
				yearSuffix: ''
			};
			
			$.datepicker.setDefaults($.datepicker.regional['es']);
			
			$(function () {
				$("#txtFechaInicioFiltro").datepicker();
			});
			
			$(function () {
				$("#txtFechaTerminoFiltro").datepicker();
			});
			
		</script>
		<style type="text/css">
			
			table {
				font-family: Arial;
				font-size: 12px
			}
			
			#tabsFiltros{
				font-family: Arial;
				font-size: 12px;
			}
			
			#tblHorarioFiltro{
				border: black 1px solid;
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

		<table width="100%" border="0">
			<tr>
				<td class="tituloModulo">
					Filtrar - Personas
				</td>
				<td align="right">
					<b>Selección</b>
					<select id="lstSeleccionaFiltro" >
						<option value="0">Seleccione</option>
					</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="iconos/ok.png" title="Ejecutar Filtro" class="imgBoton"/>
					<img src="iconos/guardar.png" title="Guardar" class="imgBoton"/>
					<img src="iconos/cerrar.png" title="Cerar" id="imgCancelar" class="imgBoton"/>
					
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="tabFiltros">
						<ul>
							<li><a href="#tabs-1">Datos generales</a></li>
							<li><a href="#tabs-2">Pasatiempos</a></li>
							<li><a href="#tabs-3">Dirección del trabajo</a></li>
							<li><a href="#tabs-4">Dirección Privada</a></li>
							<li><a href="#tabs-5">Clasificación</a></li>
							<li><a href="#tabs-6">Visita</a></li>
						</ul>
						
						<div id="tabs-1">
							<table width="100%" border="0">
								<tr>
									<td class='negrita'>
										Nombre:
									</td>
									<td>
										<input type="text" id="txtNombreFiltro" />
									</td>
									<td class='negrita'>
										Apellido Materno:
									</td>
									<td>
										<input type="text" id="txtPaternoFiltro" />
 									</td>
									<td class='negrita'>
										Apellido Materno:
									</td>
									<td>
										<input type="text" id="txtMaternoFiltro" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Número:
									</td>
									<td>
										<input type="text" id="txtNumeroFiltro" />
									</td>
									<td class='negrita'>
										Código de personas:
									</td>
									<td>
										<input type="text" id="txtCodigoPersonasFiltro" />
									</td>
									<td class='negrita'>
										Número de licencia:
									</td>
									<td>
										<input type="text" id="txtNumeroLicenciaFiltro" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Tipo de persona:
									</td>
									<td>
										<input type="text" id="txtTipoPersonaFiltro" />
									</td>
									<td class='negrita'>
										Sexo:
									</td>
									<td>
										<input type="text" id="txtSexoFiltro" />
									</td>
									<td class='negrita'>
										Fecha de Nacimiento:
									</td>
									<td>
										<table>
											<tr>
												<td>
													<select id="lstDiaFechaNacimientoFiltro" >
<?php				
														for($i=1;$i<32;$i++){
															echo '<option value="'.$i.'">'.$i.'</option>';
														}
?>
													</select>
												</td>
												<td>
													<select id="lstMesFechaNacimientoFiltro">
														<option value="1">Ene</option>
														<option value="2">Feb</option>
														<option value="3">Mar</option>
														<option value="4">Abr</option>
														<option value="5">May</option>
														<option value="6">Jun</option>
														<option value="7">Jul</option>
														<option value="8">Ago</option>
														<option value="9">Sep</option>
														<option value="10">Oct</option>
														<option value="11">Nov</option>
														<option value="12">Dic</option>
													</select>
												</td>
												<td>
													<input type="text" id="txtAnoFechaNacimientoFiltro" size="4" placeholder="YYYY" />
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Esp Primaria:
									</td>
									<td>
										<input type="text" id="txtEspPrimariaFiltro" />
									</td>
									<td class='negrita'>
										Título de Ciencia:
									</td>
									<td>
										<input type="text" id="txtTituloCienciaFiltro" />
									</td>
									<td class='negrita'>
										Segundo Titulo:
									</td>
									<td>
										<input type="text" id="txtSegfundoTituloFiltro" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Ciudad:
									</td>
									<td>
										<input type="text" id="txtCiudadFiltro" />
									</td>
									<td class='negrita'>
										Plan de marketing:
									</td>
									<td>
										<input type="text" id="txtPlanMarketingFiltro" />
									</td>
									<td class='negrita'>
										Categoría:
									</td>
									<td>
										<input type="text" id="txtCategoriaFiltro" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										...:
									</td>
									<td>
										<input type="text" id="txtTresPuntosFiltro" />
									</td>
									<td class='negrita'>
										ID del anfitrión:
									</td>
									<td>
										<input type="text" id="txtIDAnfitrionFiltro" />
									</td>
									<td class='negrita'>
										Line Usuario:
									</td>
									<td>
										<input type="text" id="txtLineUsuarioFiltro" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Med. Glino:
									</td>
									<td>
										<input type="text" id="txtMedGlinoFiltro" />
									</td>
									<td class='negrita'>
										Botiquin:
									</td>
									<td>
										<input type="text" id="txtBotiquinFiltro" />
									</td>
									<td>
										<input type="checkbox" id="chkDatosAprobadosFiltro" />Datos aprobados
									</td>
									<td>
										<input type="checkbox" id="chkDatosVerificadosFiltro" />Datos verificados
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										<input type="checkbox" id="chkNoEnviarCorreoFiltro" />No enviar correo
									</td>
									<td>
										<input type="checkbox" id="chkNoEnviarMailFiltro" />No eviar e-mail
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
								</tr>
							</table>
						</div>
						
						
						<div id="tabs-2">
							<b>Imagen de la persona </b>
							<select id="lstImagenPersona" >
								<option value="0">Seleccione</option>
							</select>
						</div>
						
						<div id="tabs-3">
							<table width="100%" border="0">
								<tr>
									<td class='negrita'>
										Institución
									</td>
									<td>
										<input type="txtInstitucionFiltro" value="" />
									</td>
									<td class='negrita'>
										Colonia
									</td>
									<td>
										<input type="txtColoniaFiltro" value="" />
									</td>
									<td class='negrita'>
										Tipo
									</td>
									<td>
										<select id="lstTipoFiltro">
											<option value="0">Seleccione</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Tipo de Institución
									</td>
									<td>
										<input type="txtTipoInstitucionFiltro" value="" />
									</td>
									<td class='negrita'>
										Territorio
									</td>
									<td>
										<input type="txtTerritorio" value="" />
									</td>
									<td class='negrita'>
										Estatus =
									</td>
									<td>
										<input type="txtEstatusIgual" value="" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Estatus &lt;&gt;
									</td>
									<td>
										<input type="txtEstatusDiferente" value="" />
									</td>
									<td class='negrita'>
										Estado
									</td>
									<td>
										<input type="txtTipoInstitucionEstado" value="" />
									</td>
									<td class='negrita'>
										Asociación-Iguala =
									</td>
									<td>
										<input type="txtAsociacionIgualaIgual" value="" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Asociación-Iguala &lt;&gt;
									</td>
									<td>
										<input type="txtAsociacionIgualaDiferente" value="" />
									</td>
									<td class='negrita'>
										IMS o ATV Brick
									</td>
									<td>
										<input type="txtIMSFiltro" value="" />
									</td>
									<td class='negrita'>
										Función del director en la institución
									</td>
									<td>
										<input type="txtFuncionDirectorFiltro" value="" />
									</td>
								</tr>
								<tr>
									<td class='negrita'>
										Número previsto de visitas por año
									</td>
									<td>
										<input type="txtNumeroPrevistoVisitasFiltro" value="" />
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="6">
										<br>
										<table border="0" width="40%" id="tblHorarioFiltro">
											<tr>
												<td colspan="6" align="center" class='titulo'>
													Horario de trabajo
												</td>
											</tr>
											<tr align="center">
												<td>&nbsp;</td>
												<td class='negrita'>Lunes</td>
												<td class='negrita'>Martes</td>
												<td class='negrita'>Miercoles</td>
												<td class='negrita'>Jueves</td>
												<td class='negrita'>Viernes</td>
											</tr>
											<tr align="center">
												<td class='negrita'>AM</td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
											</tr>
											<tr align="center">
												<td class='negrita'>PM</td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
												<td><input type="checkbox" /></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
						
						<div id="tabs-4">
							<table>
								<tr>
									<td class="negrita">
										Colonia
									</td>
									<td>
										<input type="text" id="txtColoniaDireccionPrivadaColoniaFiltro" />
									</td>
								</tr>								
							</table>
						</div>
						
						<div id="tabs-5">
							<table border="0" width="100%">
								<tr>
									<td class="negrita">
										Representante
									</td>
									<td>
										<input type="text" id="txtRepresentanteFiltro" />
									</td>
									<td class="negrita">
										Clasificación
									</td>
									<td>
										<input type="text" id="txtClasificacionFiltro" />
									</td>
									<td class="negrita">
										Línea
									</td>
									<td>
										<select id="lstLineaFiltro">
											<option value="0">Seleccione</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="negrita">
										Producto
									</td>
									<td>
										<select id="lstProductoFiltro">
											<option value="0">Seleccione</option>
										</select>
									</td>
									<td class="negrita">
										Estatus
									</td>
									<td>
										<select id="lstEstatusFiltro">
											<option value="0">Seleccione</option>
										</select>
									</td>
									<td class="negrita">
										Personas - Importante / No Interesante
									</td>
									<td>
										<select id="lstPersonasFiltro">
											<option value="0">Seleccione</option>
										</select>
									</td>
								</tr>
								<tr>
									<td class="negrita">
										Comentarios
									</td>
									<td>
										<input type="text" id="txtComentariosFiltro">
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
									<td>
										&nbsp;
									</td>
								</tr>
							</table>
						</div>
						
						<div id="tabs-6">
							<table border="0" width="50%" id="tblMultimedia">
								<tr>
									<td colspan="6" class="negrita">
										<input type="checkbox" />Visitado
									</td>
								</tr>
								<tr>
									<td class="negrita">
										Mayor o igual que
									</td>
									<td>
										<input type="text" id="txtMayorFiltro"/>
									</td>
									<td class="negrita">
										Menor o igual que
									</td>
									<td>
										<input type="text" id="txtMenorFiltro" />
									</td>
								</tr>
								<tr>
									<td class="negrita">
										Fecha de Inicio
									</td>
									<td>
										<input type="text" id="txtFechaInicioFiltro" />
									</td>
									<td class="negrita">
										Fecha de Término
									</td>
									<td>
										<input type="text" id="txtFechaTerminoFiltro" />
									</td>
								</tr>
								<tr>
									<td class="negrita">
										Ciclos:
									</td>
									<td>
										<select>
											<option>Seleccione</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						
					</div>
					<br>
				</td>
			</tr>
		</table>