<section class="content">
	<div class="container-fluid">
		<div class="block-header m-t-15">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 m-t-5">
					<h2 class="display-flex">
						<i class="material-icons font-23">assignment_turned_in</i>
						<span class="p-t-4 m-l-5">APROBACIONES</span>
					</h2>
				</div>

				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 align-right" id="menuFiltrosGnr">
					<div class="display-inline">
						<button id="btnPendienteAprobacionesGerentePers"
							class="btn waves-effect btn-account-head btn-account-l seleccionado btn-account-sel"
							disabled>
							Pendiente
						</button>
						<button id="btnAceptadoAprobacionesGerentePers"
							class="btn waves-effect btn-account-head btn-account-c">
							Aceptado
						</button>
						<button id="btnRechazadoAprobacionesGerentePers"
							class="btn waves-effect btn-account-head btn-account-r">
							Rechazado
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="divAprobacionesGerente">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card" style="border-top:2px #F44336 solid;">
						<div class="header padding-0">
							<div class="row margin-0 p-t-10 p-b-5">
								<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
									<ul class="display-inline no-style-list m-t-5">
										<li onClick="showTabsAprob();">
											<a href="#tbPersAprobacionesGerente">
												<button id="btnPersSel"
													class="btn waves-effect btn-calendar-head btn-ch-l btn-blue-sel"
													disabled>
													Personas
												</button>
											</a>
										</li>
										<li onClick="showTabsAprob();">
											<a href="#tbInstAprobacionesGerente">
												<button id="btnInstSel" style="width:119px;"
													class="btn waves-effect btn-calendar-head btn-ch-r">
													Instituciones
												</button>
											</a>
										</li>
									</ul>
								</div>
								<div class="col-lg-5 col-md-4 col-sm-6 col-xs-12 m-l-0">
									<div class="m-t-8">
										<div class="form-group display-flex margin-0">
											<label class="m-r-10 p-t-5">Ruta:</label>
											<select id="sltRutasAprobacionesGerentePers" class="form-control">
												<option value=""></option>
<?php							
												$queryRutas = "select USER_SNR,USER_NR,LNAME,MOTHERS_LNAME,FNAME from users where user_snr in ('".$ids."') order by user_nr, lname";
												$rsRutas = sqlsrv_query($conn, $queryRutas);
												while($regRutas = sqlsrv_fetch_array($rsRutas)){
													echo '<option value="'.$regRutas["USER_SNR"].'">'.$regRutas["USER_NR"].' - '.$regRutas["LNAME"].' '.$regRutas["MOTHERS_LNAME"].' '.$regRutas["FNAME"].'</option>';				
												}
?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="m-t-8">
										<div class="form-group display-flex margin-0">
											<label class="m-r-10 p-t-5" style="width:123px;">Tipo Mov:</label>
											<select id="sltTipoMovimientoAprobacionesGte" class="form-control">
												<option value="">Todos</option>
												<option value="D">Borrar</option>
												<option value="C">Cambio</option>
												<option value="N">Nuevo</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="body">
							<div id="tbPersAprobacionesGerente">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="div-tbl-aprob-pers">
											<table id="tblAprobacionesPersGerente" class="table-striped table-hover">
												<thead class="bg-cyan">
													<tr class="align-center">
														<td style="width:5%;">
															Tipo Mov.
														</td>
														<td style="width:10%;">Paterno</td>
														<td style="width:10%;">Materno</td>
														<td style="width:11%;">Nombre(s)</td>
														<td style="width:11%;">Especialidad</td>
														<td style="width:13%;">Institución</td>
														<td style="width:15%;">Dirección</td>
														<td style="width:12%;">Colonia</td>
														<td style="width:8%;">Fecha</td>
														<td style="width:5%;">Ruta</td>
													</tr>
												</thead>
												<tbody class="align-center">
												</tbody>
												<tfoot class="bg-cyan">
												</tfoot>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div id="tbInstAprobacionesGerente">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="div-tbl-aprob-pers">
											<table id="tblAprobacionesInstGerente" class="table-striped table-hover">
												<thead class="bg-cyan">
													<tr class="align-center">
														<td style="width:5%;">Tipo Mov.</td>
														<td style="width:18%;">Colonia</td>
														<td style="width:15%;">Nombre</td>
														<td style="width:18%;">Dirección</td>
														<td style="width:12%;">Teléfono</td>
														<td style="width:12%;">Clasificación</td>
														<td style="width:12%;">Fecha</td>
														<td style="width:8%;">Ruta</td>
													</tr>
												</thead>
												<tbody class="align-center">
												</tbody>
												<tfoot class="bg-cyan">
												</tfoot>
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
	</div>
</section>