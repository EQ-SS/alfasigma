<?php
	$idsEnviar = str_replace("'","",$ids);
?>
<input type="hidden" id="hdnFiltrosExportarInst" value="" />
<input type="hidden" id="hdnSelecciandoCambiarRutaInst" value="" />
<input type="hidden" id="hdnPaginaInst" value="1" />

<script>
	var actualTabInst = "tabInst1";
	var idInstTabs = "inst1";
	var idTrInst = "trinst1";

	function cambiaTabInst(id) {
		actualTabInst = id;

		$('#rightSideBarInst').find('button').removeClass('btn-indigo-slt');
		$('#' + id).addClass('btn-indigo-slt');
	}
</script>

<section class="content" style="margin-bottom:20px;">
	<div class="container-fluid">
		<div class="block-header headerInst">
			<!--#MAIN HEADER-->
			<div class="row clearfix add-padding-persona">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-7 p-r-0">
					<h2 id="activeTabInst0" style="text-transform: uppercase;">
						<i class="fas fa-building"></i>
						<span>INSTITUCIONES</span>
					</h2>
					<h2 id="activeTabInst1" style="text-transform: uppercase; display:none;">
						<i class="fas fa-hospital"></i>
						<span>HOSPITALES</span>
					</h2>
					<h2 id="activeTabInst2" style="text-transform: uppercase; display:none;">
						<i class="fas fa-pills"></i>
						<span>FARMACIAS</span>
					</h2>
					<h2 id="activeTabInst3" style="text-transform: uppercase; display:none;">
						<i class="fas fa-clinic-medical"></i>
						<span>CONSULTORIOS</span>
					</h2>
				</div>
				<!--MENU OPCIONES INSTITUCIONE TOP-->
				<?php 
			if($tipoUsuario != 4){
?>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 align-right padding-0">
					<input type="checkbox" id="chkSeleccionarTodosCambiarRutaInst" class="filled-in chk-col-red p-l-15"
						style="display:none;" />
					<label id="lblSeleccionarTodosCambiarRutaInst" for="chkSeleccionarTodosCambiarRutaInst"
						style="display:none;">
						Seleccionar todos
					</label>

					<button class="btn bg-red waves-effect btn-red" id="btnAceptarCambiarRutaInst"
						title="Aceptar cambiar ruta" style="display:none;" onClick="esMedicoInst(this.id);">
						Aceptar
					</button>
					<button class="btn bg-red waves-effect btn-red" id="btnCancelarCambiarRutaInst"
						title="Cancelar cambiar ruta" style="display:none;">
						Cerrar
					</button>

					<button class="btn bg-red waves-effect btn-red" id="btnCambiarRutaInst" title="Cambiar ruta">
						Cambios de ruta
					</button>
				</div>
				<?php
			}else{
?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-5 align-right padding-0">
					<button id="btnAprobacionesInst" title="Aprobaciones"
						class="btn bg-red waves-effect btn-red">Aprobaciones</button>
				</div>
				<?php
			}
?>
				<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 align-right padding-0" id="menuFiltrosGnr">
					<div class="display-inline display-screen-xs">
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );"
							id="btnReVisitadosInst" class="btn waves-effect btn-account-head btn-account-l">
							Re-Visitados
						</button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );"
							id="btnVisitadosInst" class="btn waves-effect btn-account-head btn-account-c">
							Visitados
						</button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );"
							id="btnNoVisitadosInst" class="btn waves-effect btn-account-head btn-account-c">
							No Visitados
						</button>
						<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );" id="btnTodosInst"
							class="btn waves-effect btn-account-head btn-account-r btn-account-sel" disabled>
							Todos
						</button>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 align-right padding-0">
					<button type="button" class="btn bg-red waves-effect btn-red" id="imgFiltrar2Inst"
						style="height:30px;" data-toggle="tooltip" data-placement="top" title="Filtrar">
						<i class="fas fa-filter font-15 "></i>
					</button>
				</div>
			</div>
			<!--#MENU OPCIONES INSTITUCIONE TOP-->
		</div>

		<!--FILTRAR INSTITUCIONES-->
		<div class="row clearfix" id="trFiltrosInst" style="display:none;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-padding-persona">
				<div class="card">
					<div class="header">
						<h2>
							Filtrar
						</h2>
						<div class="align-right" style="margin: -29px 0px -11px 0px;">
							<p title="Cerrar Filtros" id="imgFiltrarInst" class="pointer">
								<i class="material-icons">close</i>
							</p>
						</div>
					</div>
					<div class="body">
						<!--<div id="tabFiltrosInstituciones">						
										<div id="tabs-1">-->
						<div id="tblFiltros">
							<?php
							if($tipoUsuario != 4){
								echo "<div class='row'>
									<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 no-margin2'>
										<div class='form-group' style='display:inline-flex;' onclick=\"filtrosUsuarios('inst');\">
											<label class='m-r-5 p-t-5'>Representante: </label>
											<select class='form-control'>
												<option id=\"sltMultiSelectInst\" hidden>Seleccione</option>
											</select>
										</div>
									</div>
								</div>";
							}
?>
							<div class="row">
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5">Tipo: </label>
										<select id="sltTipoInstFiltro" class="form-control">
											<?php
											$rsTipoInst = sqlsrv_query($conn, "select * from INST_TYPE where REC_STAT = 0 order by name");
											while($regInst = sqlsrv_fetch_array($rsTipoInst)){
												echo '<option value="'.$regInst['INST_TYPE'].'">'.$regInst['NAME'].'</option>';
											}
		?>
										</select>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i
													class="glyphicon glyphicon-briefcase"></i></span>
											<input id="txtNombreInstFiltro" type="text" class="form-control"
												name="nombre" placeholder="Nombre Institución">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i
													class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtCalleInstFiltro" type="text" class="form-control" name="calle"
												placeholder="Calle">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i
													class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtColoniaInstFiltro" type="text" class="form-control"
												name="colonia" placeholder="Colonia">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i
													class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtCiudadInstFiltro" type="text" class="form-control"
												name="colonia" placeholder="Deleg/Mnpio">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i
													class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtEstadoInstFiltro" type="text" class="form-control"
												name="estado" placeholder="Estado">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
									<div style="display:inline-flex;">
										<input name="rbGeo" type="radio" id="rbtTodos"
											class="with-gap radio-col-indigo" />
										<label for="rbtTodos">Todos</label>
										<input name="rbGeo" type="radio" id="rbtGeoSi"
											class="with-gap radio-col-indigo" />
										<label for="rbtGeoSi">Geolocalizados</label>
										<input name="rbGeo" type="radio" id="rbtGeoNo"
											class="with-gap radio-col-indigo" />
										<label for="rbtGeoNo">No Geolocalizados</label>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i
													class="glyphicon glyphicon-map-marker"></i></span>
											<input id="txtCPInstFiltro" type="text" class="form-control" name="CP"
												placeholder="Código Postal">
										</div>
									</div>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5">Estatus: </label>
										<select id="sltEstatusFiltrosInst" class="form-control">
											<option value="">Seleccione</option>
											<?php
											$rsEstatus = llenaCombo($conn, 14, 6);
											while($estatus = sqlsrv_fetch_array($rsEstatus)){
												echo '<option value="'.$estatus['id'].'">'.$estatus['nombre'].'</option>';
											}
?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5" style="width: 108px;">Motivo Baja: </label>
										<select id="sltMotivoBajaFiltrosInst" class="form-control">
											<option value="">Seleccione</option>
											<?php
												$rsBajas = llenaCombo($conn, 14, 6);
												while($baja = sqlsrv_fetch_array($rsBajas)){
													echo '<option value="'.$baja['id'].'">'.$baja['nombre'].'</option>';
												}
?>
										</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-margin2">
									<div class="form-group" style="display:inline-flex;">
										<label class="m-r-5 p-t-5" style="width: 108px;">Frecuencia: </label>
										<select id="sltFrecuenciaFiltrosInst" class="form-control">
											<option value="">Seleccione</option>
											<?php
												$rsBajas = llenaCombo($conn, 14, 11);
												while($baja = sqlsrv_fetch_array($rsBajas)){
													echo '<option value="'.$baja['id'].'">'.$baja['nombre'].'</option>';
												}
?>
										</select>
									</div>
								</div>
								<div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 align-right no-margin2 filtro-p-btn">
									<button class="btn bg-indigo waves-effect btn-wid-col btn-indigo"
										id="btnLimpiarFiltrosInst" type="button">
										Limpiar
									</button>
								</div>
								<div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 align-right no-margin2 filtro-p-btn">
									<button class="btn bg-indigo waves-effect btn-wid-col btn-indigo"
										onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );"
										id="btnEjecutarFiltroInst" type="button">
										Filtrar
									</button>
								</div>
							</div>
						</div>
						<!--</div>
							</div>-->
					</div>
				</div>
			</div>
		</div>
		<!--#END FILTRAR INST-->

		<div id="tabsInstituciones">
			<!--RIGHT BAR INSTITUCIONES-->
			<div class="right-sidebar-person">
				<div style="display: inline-grid;">
					<ul id="rightSideBarInst" class="no-style-list">
						<li>
							<a href="#infoInstitucion" data-toggle="tooltip" data-placement="left" title="Perfil">
								<button type="button" id="tabInst1" onClick="cambiaTabInst(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2 btn-indigo-slt">
									<i class="material-icons pointer">contact_mail</i>
								</button>
							</a>
						</li>
						<li>
							<a href="#mapaInstitucion" id="lkMapaInstituciones" data-toggle="tooltip"
								data-placement="left" title="Mapa">
								<button type="button" id="tabInst2" onClick="cambiaTabInst(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">map</i>
								</button>
							</a>
						</li>
						<li>
							<a href="#planInstitucion" data-toggle="tooltip" data-placement="left" title="Plan">
								<button type="button" id="tabInst3" onClick="cambiaTabInst(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">today</i>
								</button>
							</a>
						</li>
						<li>
							<a href="#visitasInstitucion" data-toggle="tooltip" data-placement="left" title="Visitas">
								<button id="tabInst4" type="button" onClick="cambiaTabInst(this.id);"
									class="btn btn-default waves-effect add-margin-bottom p-l-7 p-r-7 btn-indigo2">
									<i class="fas fa-handshake pointer"></i>
								</button>
							</a>
						</li>
						<li>
							<a href="#representantesInstitucion" data-toggle="tooltip" data-placement="left"
								title="Representantes">
								<button type="button" id="tabInst5" onClick="cambiaTabInst(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">supervisor_account</i>
								</button>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!--#RIGHT BAR INSTITUCIONES-->


			<!-- SHOW INSTITUCIONES -->

			<div class="row clearfix">
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 add-padding-listm">
					<div class="tab-sidebar-inst-r" data-toggle="tooltip" data-placement="bottom" title="Desplegar">
					</div>
					<div id="leftsidebarInst" class="card margin-0">
						<div class="tab-sidebar-inst-l" data-toggle="tooltip" data-placement="bottom" title="Ocultar">
						</div>
						<div class="header bg-indigo" style="padding-bottom:40px;">
							<ul class="header-dropdown">
								<li class="pointer">
									<a id="btnActualizarInst" title="Actualizar" data-toggle="tooltip"
										data-placement="top">
										<i class="material-icons">loop</i>
									</a>
								</li>
								<li class="m-l-10 p-r-5 pointer">
									<a onClick="exportarExcelInst('<?= $hoy ?>','<?= $idsEnviar ?>');"
										id="btnExportarInst" data-toggle="tooltip" data-placement="top"
										title="Descargar">
										<i class="material-icons">cloud_download</i>
									</a>
								</li>
							</ul>
						</div>
						<div class="body ajuste-card-inst cardListInst margin-0" style="min-height:505px;">
							<div class="col-md-12 col-sm-12 col-xs-12 filtros-cuentas-sm padding-0" id="menuFiltrosGnr"
								style="display:none;">
								<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );"
									id="btnReVisitadoInst"
									class="btnReVisitadosInst2 btn waves-effect btn-account-head">
									Re-Visitados
								</button>
								<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );"
									id="btnVisitadosInst" class="btnVisitadosInst2 btn waves-effect btn-account-head">
									Visitados
								</button>
								<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );"
									id="btnNoVisitadosInst"
									class="btnNoVisitadosInst2 btn waves-effect btn-account-head">
									No Visitados
								</button>
								<button onClick="nuevaPaginaInst(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );"
									id="btnTodosInst"
									class="btnTodosInst2 btn waves-effect btn-account-head btn-account-sel" disabled>
									Todos
								</button>
								<button type="button" class="btn waves-effect btn-account-head" id="filtrarInst2"
									data-toggle="tooltip" data-placement="top" title="Filtrar">
									<i class="fas fa-filter padding-0" style="font-size: 16px; margin: -3px;"></i>
								</button>
							</div>

							<!--TODAS-->
							<div id="divGridInstituciones">
								<div class="row" style="display:none;">
									<!--SHOW LIST INST-->
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 add-padding-persona">
										<ul class="no-style-list display-inline margin-0">
											<li style="display:none;"><a href="#tbTodas">
													<button type="button"
														class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
														<i class="fas fa-th-list"></i>
														<span>Todas</span>
													</button>
												</a></li>

											<li class="m-l-0" style="display:none;"><a href="#tbHospitales">
													<button type="button"
														class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
														<i class="fas fa-hospital"></i>
														<span>Hospitales</span>
													</button>
												</a></li>
											<li class="m-l-0" style="display:none;"><a href="#tbFarmacias">
													<button type="button"
														class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
														<i class="fas fa-pills"></i>
														<span>Farmacias</span>
													</button>
												</a></li>
											<li class="m-l-0" style="display:none;"><a href="#tbConsultorios">
													<button type="button"
														class="btn bg-light-blue waves-effect btn-li-menu2 btn-light-blue">
														<i class="fas fa-hospital"></i>
														<span>Consultorios</span>
													</button>
												</a></li>
											<li class="m-l-0" style="display:none;"><a href="#tbClientes">
													<button type="button"
														class="btn bg-teal waves-effect btn-li-menu2 btn-light-blue">
														<i class="fas fa-user"></i>
														<span>Clientes</span>
													</button>
												</a></li>
										</ul>
									</div>
								</div>
								
								<div id="tbTodas">

									<table class="table table-striped table-hover margin-0" id="tblTodas">
										<thead>
											<tr><td></td></tr>
										</thead>

										<tbody>
<?php
											echo "<div class='col-white m-t--20 m-b-20'>
												<p style='position:absolute; top:20px;'>
													<span class='font-bold'>Total de registros: </span>
													<span id='tblTodasnumReg'>".$totalRegistrosInst."</span>
												</p>
											</div>";
?>
										</tbody>
										<tfoot>
											<tr>
												<td class="align-center">
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!--HOSPITALES-->
								<div id="tbHospitales">
									<div class="m-b-15 m-t--5">
										<div class="input-group margin-0">
											<input class="form-control buscaMedInst" type="text" id="txtBuscarHosp"
												placeholder="Buscar hospital por nombre">
											<span class="input-group-addon padding-0 buscaMedInstSpan">
												<button id="btnBuscarHospList" class="btn waves-effect btn-indigo2"
													style="padding: 4px 12px;">
													<i class="glyphicon glyphicon-search"
														style="color:#777; font-size:14px;"></i>
												</button>
											</span>
										</div>
									</div>
									<table class="table table-striped table-hover margin-0" id="tblHospitales">
										<thead>

										</thead>

										<tbody class="listaInstituciones">
<?php 

                                 
										
										echo "<div class='col-white'>
												<p style='position:absolute; top:20px;'>
													<span class='font-bold'>Total de registros: </span>
													<span id='tblHospitalesnumReg'></span>
												</p>
											</div>";
										
											
?>
										</tbody>
										<tfoot class="listaInstTfoot">
											<tr>
												<td class="align-center">
<?php								
													

?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>

								<!--FARMACIAS-->
								<div id="tbFarmacias">
									<div class="m-b-15 m-t--5">
										<div class="input-group margin-0">
											<input class="form-control buscaMedInst" type="text" id="txtBuscarFar"
												placeholder="Buscar farmacia por nombre">
											<span class="input-group-addon padding-0 buscaMedInstSpan">
												<button id="btnBuscarFarList" class="btn waves-effect btn-indigo2"
													style="padding: 4px 12px;">
													<i class="glyphicon glyphicon-search"
														style="color:#777; font-size:14px;"></i>
												</button>
											</span>
										</div>
									</div>
									<table class="table table-striped table-hover margin-0" id="tblFarmacias">
										<thead>

										</thead>

										<tbody class="listaInstituciones2">
<?php 
                                   
									
									echo "<div class='col-white'>
												<p style='position:absolute; top:20px;'>
													<span class='font-bold'>Total de registros: </span>
													<span id='tblFarmaciasnumReg'></span>
												</p>
											</div>";
									
									
?>
										</tbody>
										<tfoot class="listaInstTfoot2">
											<tr>
												<td class="align-center">
													<?php								
																		
				?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
								
								<!--CONSULTORIOS-->
								<div id="tbConsultorios">
									<div class="m-b-15 m-t--5">
										<div class="input-group margin-0">
											<input class="form-control buscaMedInst" type="text" id="txtBuscarCon"
												placeholder="Buscar consultorio por nombre">
											<span class="input-group-addon padding-0 buscaMedInstSpan">
												<button id="btnBuscarConList" class="btn waves-effect btn-indigo2"
													style="padding: 4px 12px;">
													<i class="glyphicon glyphicon-search"
														style="color:#777; font-size:14px;"></i>
												</button>
											</span>
										</div>
									</div>
									<table class="table table-striped table-hover margin-0" id="tblConsultorios">
										<thead>

										</thead>

										<tbody class="listaInstituciones2">
<?php 
                                    
									echo "<div class='col-white'>
												<p style='position:absolute; top:20px;'>
													<span class='font-bold'>Total de registros: </span>
													<span id='tblConsultoriosnumReg'></span>
												</p>
											</div>";
									
									
?>
										</tbody>
										<tfoot class="listaInstTfoot2">
											<tr>
												<td class="align-center">
													<?php								
													
																					
				?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
								
							</div>

							<!--<div id="divCambiarRutaInst" style="display:none;">-->
							<div id="divCambiarRutaInst">
								<div class='col-white m-t--20 m-b-20'>
									<p style='position:absolute; top:20px;'>
										<span class='font-bold'>Total de registros: </span>
										<span id='tblCambiarRutaInstnumReg'>
											<?= $totalRegistrosInst ?>
										</span>
									</p>
								</div>
								<table id="tblCambiarRutaInst" class="table table-striped table-hover margin-0">
									<thead>
									</thead>
									<tbody id="listaInstituciones3">
										<?= $tabla ?>
									</tbody>
									<tfoot id="listaInstTfoot3">
										<tr>
											<td class="align-center">
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!--SHOW LIST INST-->

				<!--SHOW DATOS INST-->
				<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 add-padding-persona">
					<div class="card cardListInst" id="sinResultadosInst" style="min-height:735px; display:none;">
						<div class="body align-center">
							<i class="fas fa-search font-150 m-t-20" style="color:#ebecee;"></i>
							<h2>No hay resultados que coincidan con tu búsqueda</h2>
							<div>
								<i class="fas fa-info-circle font-15"></i>
								<span>Intenta con otros filtros</span>
							</div>
						</div>
					</div>
					<div class="card cardListInst margin-0" id="cardInfInst" style="min-height:565px;">
						<div class="header">
							<h2>
								<span class="font-18" id="lblNombreInst">
								</span>
								<small id="lblTipoInst2"></small>
							</h2>
						</div>
						<div class="body" style="padding-bottom:0;">
							<div id="divDatosInstituciones" style="display:none;">
								<?php include "datosInstituciones.php"; ?>
							</div>
						</div>
					</div>
				</div>
				<!--#SHOW DATOS INST-->
			</div> <!-- #SHOW INSTITUCIONES -->
		</div>
		<!--</div>-->

		<div class="fixed-action-btn">
			<a class="btn-floating btn-txt btn-large bg-red btn-red" id="imgAgregarInstitucion">
				<i class="material-icons">add</i><span id="spanP" class="col-white m-l-10"
					style="display:none;">AGREGAR</span>
			</a>
		</div>
	</div>
</section>