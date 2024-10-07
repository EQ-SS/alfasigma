<link rel="stylesheet" href="dist/virtual-select.min.css" />
<script src="dist/virtual-select.min.js"></script>

<input type="hidden" id="hdnReporte" />

<section class="content">
	<div class="container-fluid">
		<div class="block-header m-t--5">
			<h2>
				<i class="fas fa-chart-line"></i>
				<span>REPORTES</span>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="header align-right p-t-15 p-b-15">
						<button type="button" class="btn bg-indigo waves-effect" id="btnReporte" style="display:none;">
							Desplegar Análisis
						</button>
					</div>
					<div class="body">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="card" style="box-shadow: none; border: 1px #f1f1f1 solid;">
									<div class="header bg-light-blue">
										<h2>
											Reportes
										</h2>
									</div>
									<div class="body table-responsive" style="height: 550px;">
										<table class="table table-hover table-striped pointer" id="tablaReportes">
											<!--<tr class="esteReporte">
												<td id="reporteCobMed"
													onclick="criteriosReporte(this.id, 'fecIni,fecFin,repre','coberturaMedicos.php');">
													Reporte de cobertura de médicos
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteCobFarm"
													onclick="criteriosReporte(this.id, 'fecIni,fecFin,repre','coberturaFarmacias.php');">
													Reporte de cobertura de farmacias
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteMedVisDia"
													onclick="criteriosReporte(this.id, 'fecIni,fecFin,repre','reporteMedicosVisitadosDia.php');">
													Reporte de médicos visitados por día
												</td>
											</tr>-->
											<tr class="esteReporte">
												<td id="reporteAG"
													onclick="criteriosReporte(this.id, 'periodo,repre','reporteAnalisisGerencial.php');">
													Analisis Gerencial
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteCobMedCat"
													onclick="criteriosReporte(this.id, 'periodo,repre','coberturaMedicosCat.php');">
													Cobertura por Categoria
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteCobMedEsp"
													onclick="criteriosReporte(this.id, 'periodo,repre','coberturaMedicosEsp.php');">
													Cobertura por Especialidad
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteDiaFueraTerr"
													onclick="criteriosReporte(this.id, 'periodo,repre','diasFueraTerritorio.php');">
													Dias Fuera de Territorio
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteTipoVis"
													onclick="criteriosReporte(this.id, 'periodo,repre','reporteTipoVis.php');">
													Resumen Tipo de Visita
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteResDiaVisit"
													onclick="criteriosReporte(this.id, 'periodo,repre','resumenDiarioVisitas.php');">
													Resumen Diario de Visita
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteResEjRta"
													onclick="criteriosReporte(this.id, 'periodo,repre','resumenEjecutivoRuta.php');">
													Resumen Ejecutivo por Ruta
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteBotInicDia"
													onclick="criteriosReporte(this.id, 'periodo,repre','botonInicioDia.php');">
													Boton Inicio de Dia
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteContactPorProd"
													onclick="criteriosReporte(this.id, 'periodo,linea','contactosPorProducto.php');">
													Contactos por Producto
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>


							<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
								<div class="row">
									<div id="fecIni" class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="display:none;">
										<div class="form-group">
											<label>Fecha de inicio</label>
											<div class="" id="bs_datepicker_container">
												<input type="text" id="txtFechaInicioReportes" class="form-control"
													placeholder="aaaa/mm/dd">
											</div>
										</div>
									</div>
									<div id="fecFin" class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="display:none;">
										<div class="form-group">
											<label>Fecha de término</label>
											<div class="" id="bs_datepicker_container">
												<input type="text" id="txtFechaFinReportes" class="form-control"
													placeholder="aaaa/mm/dd">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="repre" style="display:none;">
											<label>Representante</label>
											<?php
											if($tipoUsuario != 4){
												echo "<div class=\"selectBox\" onclick=\"filtrosUsuarios('reportes');\">
													<div class=\"select\">
														<select class=\"form-control\">
															<option id=\"sltMultiSelectReportes\" >Seleccione</option>
														</select>
														<div class='select_arrow'></div>
													</div>
												</div>";								
											}else{//es repre
												echo '<div class="select"><select id="sltRepreReportes" class="form-control">';
												$queryRepres = "select user_snr, lname + ' ' + MOTHERS_LNAME + ' ' + fname as nombre from users where USER_SNR in ('".$ids."') ";
												$repre = sqlsrv_fetch_array(sqlsrv_query($conn, $queryRepres));
												echo '<option id="'.$repre['user_snr'].'">'.$repre['nombre'].'</option>';
												echo '</select><div class="select_arrow"></div></div>';
											}
					?>
										</div>
									</div>
									<div id="status" class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">
										<label>Estatus de la persona</label>
										<select id="sltEsatusReportes" class="form-control">
											<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
											<?php
											$rsPacientes = llenaCombo($conn, 19, 11);
											while($paciente = sqlsrv_fetch_array($rsPacientes)){
												echo '<option value="'.$paciente['id'].'">'.$paciente['nombre'].'</option>';
											}
			?>
										</select>
									</div>
									<div id="status_i" class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
										style="display:none;">
										<label>Estatus de la Institución</label>
										<select id="sltEsatusReportesI" class="form-control">
											<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
											<?php
								$rsPacientes = llenaCombo($conn, 14, 18);
											while($paciente = sqlsrv_fetch_array($rsPacientes)){
												echo '<option value="'.$paciente['id'].'">'.$paciente['nombre'].'</option>';
											}
			?>
										</select>
									</div>
									<div id="periodo" class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
										style="display:none;">
										<label>Período</label>
										<select id="sltCycleReportes" class="form-control">
											<?php
								$rsCycles = sqlsrv_query($conn, "select * from cycles where rec_stat=0 order by NAME desc");
											while($ciclo = sqlsrv_fetch_array($rsCycles)){
									echo '<option value="'.$ciclo['CYCLE_SNR'].'">'.$ciclo['NAME'].'</option>';
											}
			?>
										</select>
										<!--<input type="text" id="txtPeriodoReportes"/>
										<div class="select">
											<select id="sltPeriodoReportes" style="width:150px">
												<option value="">Seleccione</option>
											</select>
											<div class="select_arrow"></div>
										</div>-->
									</div>
									<div id="producto" class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
										style="display:none;">
										<div class="form-group">
											<label>Producto</label>
											<!--<input type="text" class="form-control" id="txtProductoReportes"/>-->
											<select id="sltProductoReportes" class="form-control">
												<option value=""></option>
											</select>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="linea" style="display:none;">
											<label>Lineas:</label><br>
											<select id="sltLineaReportes" multiple placeholder="Seleccione" data-search="false" data-silent-initial-value-set="true">
<?php
												$qLinea = "select cline_snr, name 
													from COMPLINE 
													where rec_stat = 0 
													and cline_snr <> '00000000-0000-0000-0000-000000000000' 
													order by NAME";
												$rsLinea = sqlsrv_query($conn, $qLinea);
												while($linea = sqlsrv_fetch_array($rsLinea)){
													echo '<option value="'.$linea['cline_snr'].'">'.$linea['name'].'</option>';
												}
?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section><script>
	VirtualSelect.init({

		ele: '#sltLineaReportes',
		selectAllText: 'Seleccionar todos',
		optionsSelectedText: 'Opciones seleccionadas',
		allOptionsSelectedText: 'Todas'
	  
	});
</script>