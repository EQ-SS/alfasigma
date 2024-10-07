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
			Filtrar - Instituciones
		</td>
		<td align="right">
			<b>Selección</b>
			<select id="lstSeleccionaFiltro" >
				<option value="0">Seleccione</option>
			</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="iconos/ok.png" title="Ejecutar Filtro" width="40px"/>
			<img src="iconos/guardar.png" title="Guardar" width="40px"/>
			<img src="iconos/cerrar.png" title="Cerar" id="imgCancelarInstituciones" width="40px"/>					
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="tabFiltrosInstituciones">
				<ul>
					<li><a href="#tabs-1">Los campos estandar</a></li>
					<li><a href="#tabs-2">Campos definidos por el usuario</a></li>
				</ul>
				
				<div id="tabs-1">
					<table width="100%" border="0">
						<tr>
							<td class='negrita'>
								Tipo:
							</td>
							<td>
								<select>
									<option value="0">Seleccione</option>
								</select>
							</td>
							<td class='negrita'>
								Institución:
							</td>
							<td>
								<input type="text" id="txtPaternoFiltro" />
							</td>
							<td class='negrita'>
								Tipo de institución:
							</td>
							<td>
								<input type="text" id="txtMaternoFiltro" />
							</td>
						</tr>
						<tr>
							<td class='negrita'>
								Departamento:
							</td>
							<td>
								<input type="text" id="txtNumeroFiltro" />
							</td>
							<td class='negrita'>
								País:
							</td>
							<td>
								<input type="text" id="txtCodigoPersonasFiltro" />
							</td>
							<td class='negrita'>
								Estado:
							</td>
							<td>
								<input type="text" id="txtNumeroLicenciaFiltro" />
							</td>
						</tr>
						<tr>
							<td class='negrita'>
								Ciudad:
							</td>
							<td>
								<input type="text" id="txtTipoPersonaFiltro" />
							</td>
							<td class='negrita'>
								Representante:
							</td>
							<td>
								<input type="text" id="txtSexoFiltro" />
							</td>
							<td class='negrita'>
								Colonia:
							</td>
							<td>
								<input type="text" />
							</td>
						</tr>
						<tr>
							<td class='negrita'>
								ZIP:
							</td>
							<td>
								<input type="text" id="txtEspPrimariaFiltro" />
							</td>
							<td class='negrita'>
								Calle:
							</td>
							<td>
								<input type="text" id="txtTituloCienciaFiltro" />
							</td>
							<td class='negrita'>
								Categoría:
							</td>
							<td>
								<input type="text" id="txtSegfundoTituloFiltro" />
							</td>
						</tr>
						<tr>
							<td class='negrita'>
								IMS o ATV Brick:
							</td>
							<td>
								<input type="text" id="txtCiudadFiltro" />
							</td>
							<td class='negrita'>
								Institución image:
							</td>
							<td>
								<input type="text" id="txtPlanMarketingFiltro" />
							</td>
							<td class='negrita'>
								Territorio:
							</td>
							<td>
								<input type="text" id="txtCategoriaFiltro" />
							</td>
						</tr>
						<tr>
							<td class='negrita'>
								Estatus =:
							</td>
							<td>
								<input type="text" id="txtTresPuntosFiltro" />
							</td>
							<td class='negrita'>
								Estatus &lt;&gt;:
							</td>
							<td>
								<input type="text" id="txtIDAnfitrionFiltro" />
							</td>
							<td class='negrita'>
								Plan de marketing:
							</td>
							<td>
								<input type="text" id="txtLineUsuarioFiltro" />
							</td>
						</tr>
						<tr>
							<td class='negrita'>
								Mantener:
							</td>
							<td>
								<input type="text" id="txtMedGlinoFiltro" />
							</td>
							<td class='negrita'>
								ID del anfitrion:
							</td>
							<td>
								<input type="text" id="txtBotiquinFiltro" />
							</td>
							<td colspan="2" class="negrita">
								<input type="checkbox" id="chkDatosAprobadosFiltro" />Sólo las instituciones no unidas
							</td>
						</tr>
						<tr>
							<td colspan="2" class="negrita">
								<input type="checkbox" id="chkDatosVerificadosFiltro" />Instituciones con la venta en el año
								<select>
									<option value="0"></option>
<?php
									for($i=2015;$i<=date("Y");$i++){
										echo '<option value="'.$i.'">'.$i.'</option>';
									}
?>
								</select>
							</td>
							<td class='negrita' class="negrita" colspan="2">
								<input type="checkbox" id="chkNoEnviarCorreoFiltro" />Instituciones añadidas a más usuarios de la misma lines
							</td>
							<td class="negrita" colspan="2">
								<input type="checkbox" id="chkNoEnviarMailFiltro" />Mostrar sólo retenciones
							</td>
						</tr>
						<tr>
							<td class="negrita">
								Fecha de inicio
							</td>
							<td>
								<input type="text" id="txtFechaInicioInstituciones" />
							</td>
							<td class="negrita">
								Fecha de termino:
							</td>
							<td>
								<input type="text" id="txtFechaTerminoInstituciones" />
							</td>
							<td></td>
							<td></td>
						</tr>
					</table>
				</div>
				
				
				<div id="tabs-2">
					<b>Imagen de la persona </b>
					<select id="lstImagenPersona" >
						<option value="0">Seleccione</option>
					</select>
				</div>

			</div>
			<br>
		</td>
	</tr>
</table>