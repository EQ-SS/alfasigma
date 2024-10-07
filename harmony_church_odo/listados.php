<input type="text" id="hdnListado" />

<section class="content">
	<div class="container-fluid">
		<div class="block-header m-t--5">
			<h2>
				<i class="far fa-list-alt"></i>
				<span>LISTADOS</span>
			</h2>
		</div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card" style="border-top:2px #F44336 solid;">
					<div class="header align-right p-t-15 p-b-15">
						<button type="button" class="btn bg-indigo waves-effect" id="btnListado" style="display:none;">
							Desplegar Listado
						</button>
					</div>
					<div class="body">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
								<div class="card" style="box-shadow: none; border: 1px #f1f1f1 solid;">
									<div class="header bg-light-blue">
										<h2>
											<span>Listados</span>
										</h2>
									</div>
									<div class="body table-responsive" style="height: 550px;">
										<table class="table table-hover table-striped pointer" id="tablaListados">
											<tr>
												<td id="listadoMed"
													onclick="criteriosListado(this.id, 'repreListados,statusListados','listadoMedicos.php');">
													Listado de médicos
												</td>
											</tr>
											<!--<tr>
												<td id="listadoAltaMed"
													onclick="criteriosListado(this.id, 'repreListados,statusListados,fecIniListados,fecFinListados','listadoMedicosAltas.php');">
													Listado de altas de médicos
												</td>
											</tr>-->
											<tr>
												<td id="listadoBajaMed"
													onclick="criteriosListado(this.id, 'repreListados,fecIniListados,fecFinListados','listadoMedicosBajas.php');">
													Listado de bajas de médicos
												</td>
											</tr>
											<tr>
												<td id="listadoMedPlan"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoMedicosPlaneados.php');">
													Listado de médicos planeados
												</td>
											</tr>
											<tr>
												<td id="listadoMedVis"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados,statusListados','listadoMedicosVisitados.php');">
													Listado de médicos visitados
												</td>
											</tr>
											<!--<tr>
												<td id="listadoMedNoVis"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoMedicosNoVisitados.php');">
													Listado de médicos no visitados
												</td>
											</tr>-->
											<tr>
												<td id="listadoFar"
													onclick="criteriosListado(this.id, 'repreListados,statusListados_i','listadoFarmacias.php');">
													Listado de farmacias
												</td>
											</tr>
											<!--<tr>
												<td id="listadoAltaFar"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados,statusListados_i','listadoFarmaciasAltas.php');">
													Listado de altas de farmacias
												</td>
											</tr>-->
											<!--<tr>
												<td id="listadoBajaFar"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoFarmaciasBajas.php');">
													Listado de bajas de farmacias
												</td>
											</tr>-->
											<tr>
												<td id="listadoFarPlan"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados,statusListados_i','listadoFarmaciasPlaneadas.php');">
													Listado de farmacias planeadas
												</td>
											</tr>
											<tr>
												<td id="listadoFarVis"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados,statusListados_i','listadoFarmaciasVisitadas.php');">
													Listado de farmacias visitadas
												</td>
											</tr>
											<!--<tr>
												<td id="listadoFarNoVis"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados,statusListados_i','listadoFarmaciasNoVisitadas.php');">
													Listado de farmacias no visitadas
												</td>
											</tr>-->
											<tr>
												<td id="listadoMedMuestra"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoMedicosMuestraMedica.php');">
													Listado de médicos con muestra médica
												</td>
											</tr>
											<tr>
												<td id="listadoFarMuestra"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoFarmaciasMuestraMedica.php');">
													Listado de farmacias con muestra médica
												</td>
											</tr>
											<tr>
												<td id="listadoHis"
													onclick="criteriosListado(this.id, 'statusListados,repreListados,periodoListados','listadoHistoricoMedicosVisitadosCiclo.php');">
													Listado histórico de médicos visitados por ciclo
												</td>
											</tr>
											<tr>
												<td id="listadoAprobMed"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoAprobacionesMedicos.php');">
													Listado de aprobaciones de médicos
												</td>
											</tr>
											<tr>
												<td id="listadoAprobInst"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoAprobacionesInst.php');">
													Listado de aprobaciones de Instituciones
												</td>
											</tr>
											<tr>
												<td id="listadoAprobInv"
													onclick="criteriosListado(this.id, 'repreListados,periodoListados,productoListados','listadoAprobacionInventario.php');">
													Listado aprobación inventario
												</td>
											</tr>
											<tr>
												<td id="listadoOA"
													onclick="criteriosListado(this.id, 'fecIniListados,fecFinListados,repreListados','listadoOtrasActividades.php');">
													Listado otras actividades
												</td>
											</tr>
											<tr>
												<td id="listadoEncuestaW"
													onclick="criteriosListado(this.id, 'repreListados','listadoEncuestaWaterpik.php');">
													Listado de encuesta WATERPIK
												</td>
											</tr>
											<tr>
												<td id="listadoEncuestaMW"
													onclick="criteriosListado(this.id, 'repreListados','listadoEncuestaMercadoWaterpik.php');">
													Listado de encuesta Mercado WATERPIK
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>


							<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
								<div class="row">
									<div id="fecIniListados" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"
										style="display:none;">
										<div class="form-group">
											<label>Fecha de inicio</label>
											<div class="" id="bs_datepicker_container">
												<input type="text" id="txtFechaInicioListados" class="form-control"
													placeholder="aaaa-mm-dd">
											</div>
										</div>
									</div>
									<div id="fecFinListados" class="col-lg-6 col-md-6 col-sm-6 col-xs-6"
										style="display:none;">
										<div class="form-group">
											<label>Fecha de término</label>
											<div class="" id="bs_datepicker_container">
												<input type="text" id="txtFechaFinListados" class="form-control"
													placeholder="aaaa-mm-dd">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="repreListados" style="display:none;">
											<label>Representante</label>
											<?php
											if($tipoUsuario != 4){
												echo "<div class=\"selectBox\" onclick=\"filtrosUsuarios('listados');\">
														<select class=\"form-control\">
															<option id=\"sltMultiSelectListados\" >Seleccione</option>
														</select>
																	</div>";								
											}else{//es repreListados
												echo '<div class="select"><select id="sltrepreListados" class="form-control">';
												$queryrepreListadoss = "select user_snr, lname + ' ' + MOTHERS_LNAME + ' ' + fname as nombre from users where USER_SNR in ('".$ids."') ";
												$repreListados = sqlsrv_fetch_array(sqlsrv_query($conn, $queryrepreListadoss));
												echo '<option id="'.$repreListados['user_snr'].'">'.$repreListados['nombre'].'</option>';
												echo '</select><div class="select_arrow"></div></div>';
											}
					?>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="statusListados" style="display:none;">
											<label>Estatus de la persona:</label>
											<form>
												<div class="multiselect">
													<div class="selectBox" onclick="showCheckboxesListadosPersonas()">
														<select class="form-control">
															<option id="sltMultiSelectListadosPersonas">Selecciona</option>
														</select>
														<div class="overSelect"></div>
													</div>
													<div id="checkboxesListadosPersonas" style="display:none;">
													<?php
														$rsListadosPersonas = llenaCombo($conn, 19, 11);
														$contadorChecksListadosPersonas = 0;
														$idChecks = '';
														$descripcionesCheckListadosPersonas = '';
														while($listadosPersonas = sqlsrv_fetch_array($rsListadosPersonas)){
															$contadorChecksListadosPersonas++;
															$idChk = "listadosPersonas".$contadorChecksListadosPersonas;
															//echo '<label for="'.$idChk.'"><input onclick="agregaDesListadosPersonas(\''.$listadosPersonas['nombre'].'\',\''.$idChk.'\');" type="checkbox" id="'.$idChk.'" value="'.$listadosPersonas['id'].'" />'.$listadosPersonas['nombre'].'</label><br>';
															//echo $contadorChecks."<br>";

															echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesListadosPersonas(\''.$listadosPersonas['nombre'].'\',\''.$idChk.'\');" value="'.$listadosPersonas['id'].'"/>';
															echo '<label for="'.$idChk.'">'.$listadosPersonas['nombre'].'</label>';
														}
													?>
													</div>
												</div>
												<input type="hidden" id="hdnTotalChecksListadosPersonas"
													value="<?= $contadorChecksListadosPersonas ?>">
												<input type="hidden" id="hdnDescripcionChkVisitasListadosPersonas"
													value="<?= ($descripcionesCheckListadosPersonas == '') ? "Seleccione" : $descripcionesCheckListadosPersonas ?>" />
											</form>

											<!--<div class="select">
												<select id="sltEsatusListados" style="width:150px">
													<option value="00000000-0000-0000-0000-000000000000"></option>
					<?php
													/*$rsPacientes = llenaCombo($conn, 19, 11);
													while($paciente = sqlsrv_fetch_array($rsPacientes)){
														echo '<option value="'.$paciente['id'].'">'.$paciente['nombre'].'</option>';
													}*/
					?>
												</select>
												<div class="select_arrow"></div>
											</div>-->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="statusListados_i" class="negrita" valign="top" style="display:none;">
											<label>Estatus Listados de la Inst:</label>
											<form>
												<div class="multiselect">
													<div class="selectBox" onclick="showCheckboxesListadosInst()">
														<select class="form-control">
															<option id="sltMultiSelectListadosInst"
																style="width:200px;">Selecciona</option>
														</select>
														<div class="overSelect"></div>
													</div>
													<div id="checkboxesListadosInst">
														<?php
														$rsListadosInst = llenaCombo($conn, 14, 6);
														$contadorChecksListadosInst = 0;
														$idChecks = '';
														$descripcionesCheckListadosInst = '';
														while($listadosInst = sqlsrv_fetch_array($rsListadosInst)){
															$contadorChecksListadosInst++;
															$idChk = "listadosInst".$contadorChecksListadosInst;
															echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesListadosInst(\''.$listadosPersonas['nombre'].'\',\''.$idChk.'\');" value="'.$listadosInst['id'].'"/>';
															echo '<label for="'.$idChk.'">'.$listadosInst['nombre'].'</label>';
															//echo $contadorChecks."<br>";
														}
					?>
													</div>
												</div>
												<input type="hidden" id="hdnTotalChecksListadosInst"
													value="<?= $contadorChecksListadosInst ?>">
												<input type="hidden" id="hdnDescripcionChkVisitasListadosInst"
													value="<?= ($descripcionesCheckListadosInst == '') ? "Seleccione" : $descripcionesCheckListadosInst ?>" />
											</form>

											<!--<div class="select">
												<select id="sltEsatusListadosI" style="width:150px">
													<option value="00000000-0000-0000-0000-000000000000"></option>
					<?php
													/*$rsPacientes = llenaCombo($conn, 14, 6);
													while($paciente = sqlsrv_fetch_array($rsPacientes)){
														echo '<option value="'.$paciente['id'].'">'.$paciente['nombre'].'</option>';
													}*/
					?>
												</select>
												<div class="select_arrow"></div>
											</div>-->
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="periodoListados" class="negrita" valign="top" style="display:none;">
											<label>Período </label>
												<select id="sltCycleListados" class="form-control">
													<option value=""></option>
													<?php
													$rsCycles = sqlsrv_query($conn, "select * from cycles where rec_stat=0 order by NAME desc");
													while($ciclo = sqlsrv_fetch_array($rsCycles)){
														echo '<option value="'.$ciclo['CYCLE_SNR'].'">'.$ciclo['NAME'].'</option>';
													}
					?>
												</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div id="productoListados" style="display:none;">
											<div class="selectBox" onclick="filtroProductos();">
												<label>Producto</label>
												<select class="form-control">
													<option id="sltProductoListados" value=""></option>
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
	</div>
</section>