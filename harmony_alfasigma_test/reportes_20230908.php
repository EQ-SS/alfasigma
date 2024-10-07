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
											<tr class="esteReporte">
												<td id="reporteCobMed"
													onclick="criteriosReporte(this.id, 'fecIni,fecFin,repre','coberturaMedicos.php');">
													Reporte de cobertura de médicos
												</td>
											</tr>
											<tr class="esteReporte">
												<td id="reporteInv"
													onclick="criteriosReporte(this.id,'periodo,repre','reporteInventario.php');">
													Reporte de inventario
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
								$rsPacientes = llenaCombo($conn, 14, 6);
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>