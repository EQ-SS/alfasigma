<input type="hidden" id="hdnIdsEnviarPersonas" value="<?= $idsEnviar ?>" />
<input type="hidden" id="hdnPaginaPersonas" value="1" />
<input type="hidden" id="hdnFiltrosExportar" value="" />
<input type="hidden" id="hdnSelecciandoCambiarRuta" value="" />

<script>
	var actualTab = "tabMed1";
	var idmedico = "med1";
	var idTrMedico = "trmed1";
	var idMed1 = "";
	var nombreMed1 = "";
	var especialidadMed1 = "";

	function cambiaTab(id) {
		actualTab = id;
		$('#rightSideBarPerson').find('button').removeClass('btn-indigo-slt');
		$('#' + id).addClass('btn-indigo-slt');

		//alert(actualTab);
	}

	function esMedicoInst(id) {
		if (id == 'btnAceptarCambiarRutaPersonas') {
			isMedorInst = 0;
		}
		if (id == 'btnAceptarCambiarRutaInst') {
			isMedorInst = 1;
		}
	}
</script>

<section class="content" style="margin-bottom:20px;">
	<div class="container-fluid">
		<div class="block-header delete-margin headerMedicos">
			<div class="row clearfix add-padding-persona">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<h2>
						<i class="fas fa-user-md"></i>
						<span>MÉDICOS</span>
					</h2>
				</div>

				<?php 
					if($tipoUsuario != 4){
?>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 align-right padding-0">
					<input type="checkbox" id="chkSeleccionarTodosCambiarRuta" class="filled-in chk-col-red p-l-15"
						style="display:none;" />
					<label id="lblSeleccionarTodosCambiarRuta" for="chkSeleccionarTodosCambiarRuta"
						style="display:none;">Seleccionar
						todos</label>

					<button class="btn bg-red waves-effect btn-red" id="btnAceptarCambiarRutaPersonas"
						title="Aceptar cambiar ruta" style="display:none;"
						onClick="esMedicoInst(this.id);">Aceptar</button>
					<button class="btn bg-red waves-effect btn-red" id="btnCancelarCambiarRutaPersonas"
						title="Cancelar cambiar ruta" style="display:none;">Cerrar</button>

					<button class="btn bg-red waves-effect btn-red" id="btnCambiarRutaPersonas" title="Cambiar ruta">
						Cambios de ruta
					</button>
				</div>
				<?php
					}else{
?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 align-right padding-0">
					<button id="btnAprobacionesPers" title="Aprobaciones"
						class="btn bg-red waves-effect btn-red">Aprobaciones</button>
				</div>
				<?php
					}
?>

				<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 align-right padding-0" id="menuFiltrosGnr">
					<div class="display-inline display-screen-xs">
						<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );" id="btnReVisitadosPersonas"
							class="btn waves-effect btn-account-head btn-account-l"> 
							Re-Visitados
						</button>
						<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );"
							id="btnVisitadosPersonas" class="btn waves-effect btn-account-head btn-account-c">
							Visitados
						</button>
						<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );"
							id="btnNoVisitadosPersonas" class="btn waves-effect btn-account-head btn-account-c">
							No Visitados
						</button>
						<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );"
							id="btnTodosPersonas" class="btn waves-effect btn-account-head btn-account-r btn-account-sel" disabled>
							Todos
						</button>
					</div>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-6 col-xs-12 align-right padding-0">
					<button type="button" class="btn bg-red waves-effect btn-red" id="imgFiltrar2" style="height:30px;"
						data-toggle="tooltip" data-placement="top" title="Filtrar">
						<i class="fas fa-filter font-15"></i>
					</button>
				</div>
			</div>
		</div>


		<!--FILTRAR-->
		<div class="row clearfix" id="trFiltros2" style="display:none;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add-padding-persona">
				<div class="card">
					<div class="header">
						<h2>
							Filtrar
						</h2>
						<div class="align-right" style="margin: -29px 0px -11px 0px;">
							<p title="Cerrar Filtros" id="imgFiltrar" class="pointer">
								<i class="material-icons">close</i>
							</p>
						</div>
					</div>
					<div class="body">
						<div id="tabFiltros2">
							<div id="tabs-1">
								<div id="tblFiltros" width="100%" border="0">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group" style="display:inline-flex;">
												<label class="m-r-5 p-t-5">Estatus: </label>
												<select id="sltEstatusFiltro" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">
														Seleccione
													</option>
													<?php
													$rsTipoPersona = llenaCombo($conn, 19, 11);
														while($tipo = sqlsrv_fetch_array($rsTipoPersona)){
															echo '<option value="'.$tipo['id'].'">'.utf8_encode($tipo['nombre']).'</option>';
														}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-user"></i></span>
													<input id="txtNombreFiltro" type="text" class="form-control"
														name="nombres" placeholder="Nombre(s)">
												</div>
												<!--<input type="text" id="txtNombreFiltro" />-->
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-user"></i></span>
													<input id="txtApellidosFiltro" type="text" class="form-control"
														name="apellidos" placeholder="Apellidos(s)">
												</div>
												<!--<input type="text" id="txtApellidosFiltro" />-->
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-map-marker"></i></span>
													<input id="txtEstadoFiltro" type="text" class="form-control"
														name="estado" placeholder="Estado">
												</div>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-map-marker"></i></span>
													<input id="txtBrickFiltro" type="text" class="form-control"
														name="estado" placeholder="Brick">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group" style="display:inline-flex;">
												<label class="m-r-5 p-t-5">Especialidad: </label>
												<select id="sltEspecialidadFiltro" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">
														Seleccione
													</option>
													<?php
													$rsEsp = llenaCombo($conn, 19, 1);
													while($esp = sqlsrv_fetch_array($rsEsp)){
														echo '<option value="'.$esp['id'].'">'.$esp['nombre'].'</option>';
													}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-map-marker"></i></span>
													<input id="txtDireccionFiltro" type="text" class="form-control"
														name="estado" placeholder="Calle">
												</div>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-map-marker"></i></span>
													<input id="txtColoniaFiltro" type="text" class="form-control"
														name="estado" placeholder="Colonia">
												</div>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-map-marker"></i></span>
													<input id="txtCPFiltro" type="text" class="form-control"
														name="estado" placeholder="C.P.">
												</div>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i
															class="glyphicon glyphicon-map-marker"></i></span>
													<input id="txtDelegacionFiltro" type="text" class="form-control"
														name="estado" placeholder="Deleg/Mnpio">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group" style="display:inline-flex;">
												<label class="m-r-5 p-t-5" style="width:203px;">Tipo de Médico: </label>
												<select id="sltTipoMedicoFiltro" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">
														Seleccione
													</option>
													<?php
												$rsTipoMedico = llenaCombo($conn, 19, 73);
												while($regTipoMedico = sqlsrv_fetch_array($rsTipoMedico)){
													echo '<option value="'.$regTipoMedico['id'].'">'.$regTipoMedico['nombre'].'</option>';
												}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group" style="display:inline-flex;">
												<label class="m-r-5 p-t-5">Frecuencia: </label>
												<select id="sltFrecuenciaFiltro" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">
														Seleccione
													</option>
													<?php
												$rsFrecuencia = llenaCombo($conn, 19, 2);
												while($regFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
													echo '<option value="'.$regFrecuencia['id'].'">'.$regFrecuencia['nombre'].'</option>';
												}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group" style="display:inline-flex;">
												<label class="m-r-5 p-t-5">Categoría: </label>
												<select id="sltCategoriaFiltro" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">
														Seleccione
													</option>
													<?php
												$rsCategoria = llenaCombo($conn, 19, 25);
												while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
													echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
												}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i class="material-icons"
															style="font-size: 17px;">business</i></span>
													<input id="txtInstitucionFiltro" type="text" class="form-control"
														name="estado" placeholder="Institución">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-margin2">
											<div class="form-group" style="display:inline-flex;">
												<label class="m-r-5 p-t-5" style="width:103px;">Motivo baja: </label>
												<select id="sltBajasFiltro" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000">
														Seleccione
													</option>
													<?php
													$rsTipoPersona = llenaCombo($conn, 19, 11);
														while($tipo = sqlsrv_fetch_array($rsTipoPersona)){
															echo '<option value="'.$tipo['id'].'">'.utf8_encode($tipo['nombre']).'</option>';
														}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 no-margin2">
											<?php
											if($tipoUsuario != 4){
												echo "<div class='form-group' style='display:inline-flex;' onclick=\"filtrosUsuarios('personas');\">
														<label class='m-r-5 p-t-5'>Representante: </label>
														<select class='form-control'>
															<option id=\"sltMultiSelectPersonas\">Seleccione</option>
														</select>
													</div>";
											}else{
												echo "<div class='form-group'></div>";
											}
										?>
										</div>
										<div
											class="col-lg-1 col-md-1 col-sm-6 col-xs-6 align-right no-margin2 filtro-p-btn">
											<button class="btn bg-indigo waves-effect btn-wid-col btn-indigo"
												id="btnLimpiarFiltros" type="button">
												Limpiar
											</button>
										</div>
										<div
											class="col-lg-1 col-md-1 col-sm-6 col-xs-6 align-right no-margin2 filtro-p-btn">
											<button class="btn bg-indigo waves-effect btn-wid-col btn-indigo"
												onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );"
												id="btnEjecutarFiltro" type="button">
												Filtrar
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--#END FILTRAR-->

		<!-- Right Sidebar -->
		<div id="tabsDatosPersonales" style="font-size:10px">
			<div class="right-sidebar-person">
				<div style="display: inline-grid;">
					<ul id="rightSideBarPerson" class="no-style-list">
						<li data-toggle="tooltip" data-placement="left" title="Perfil">
							<a href="#tabs-1" id="lkInformacionPersona">
								<button id="tabMed1" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2 btn-indigo-slt">
									<i class="material-icons pointer">contact_mail</i>
								</button>
							</a>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Prescripciones">
							<a href="#tabs-9">
								<button id="tabMed2" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="fas fa-file-prescription pointer"></i>
								</button>
							</a>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Mapa">
							<a href="#tabs-5" id="lkMapa">
								<button id="tabMed3" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">map</i>
								</button>
							</a>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Plan">
							<a href="#tabs-3">
								<button id="tabMed4" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">today</i>
								</button>
							</a>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Visitas">
							<a href="#tabs-4" id="lkVisitas">
								<button id="tabMed5" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom p-l-7 p-r-7 btn-indigo2">
									<i class="fas fa-handshake pointer"></i>
								</button>
							</a>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Representantes">
							<a href="#tabs-2">
								<button id="tabMed6" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">supervisor_account</i>
								</button>
							</a>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Muestras/Material">
							<a href="#tabs-7">
								<button id="tabMed7" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom p-l-10 p-r-10 btn-indigo2">
									<i class="fas fa-vial"></i>
								</button>
							</a>
						</li>
						<!--<li data-toggle="tooltip" data-placement="left" title="Bancos/Aseguradoras">
							<a href="#tabs-10">
								<button id="tabMed8" type="button" onClick="cambiaTab(this.id);"
									class="btn btn-default waves-effect add-margin-bottom btn-indigo2">
									<i class="material-icons pointer">account_balance</i>
								</button>
							</a>
						</li>-->
						<!--<li data-toggle="tooltip" data-placement="left" title="Encuesta">
							<button type="button" class="btn btn-default waves-effect add-margin-bottom">
								<i class="material-icons pointer">question_answer</i>
							</button>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Eventos">
							<button type="button" class="btn btn-default waves-effect add-margin-bottom">
								<i class="material-icons pointer">event_note</i>
							</button>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="Multi Canal">
							<button type="button" class="btn btn-default waves-effect add-margin-bottom">
								<i class="material-icons pointer">layers</i>
							</button>
						</li>
						<li data-toggle="tooltip" data-placement="left" title="CLM">
							<button type="button" class="btn btn-default waves-effect add-margin-bottom">
								<i class="material-icons pointer">ondemand_video</i>
							</button>
						</li>-->
					</ul>
				</div>
			</div><!-- #END# Right Sidebar -->


			<!--DATOS MÉDICOS -->
			<div class="row clearfix">
				<!--LISTA-->
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 add-padding-listm">
					<div class="tab-sidebar-medicos-r" data-toggle="tooltip" data-placement="bottom" title="Desplegar">
					</div>
					<div id="leftsidebarMedicos" class="card margin-0">
						<div class="tab-sidebar-medicos-l" data-toggle="tooltip" data-placement="bottom"
							title="Ocultar"></div>
						<div class="header bg-indigo" style="padding-bottom:40px;">
							<ul class="header-dropdown">
								<li class="pointer">
									<a id="btnActualizarPers" data-toggle="tooltip" data-placement="top"
										title="Actualizar">
										<i class="material-icons">loop</i>
									</a>
								</li>
								<li class="m-l-10 p-r-5 pointer">
									<a onClick="exportarExcelPersonas('<?= $hoy ?>','<?= $idsEnviar ?>');"
										id="btnExportarPersonas" data-toggle="tooltip" data-placement="top"
										title="Descargar">
										<i class="material-icons">cloud_download</i>
									</a>
								</li>
							</ul>
						</div>

						<div class="body ajuste-card cardListMedicos" style="min-height:505px;">
							<div class="col-md-12 col-sm-12 col-xs-12 filtros-cuentas-sm padding-0" id="menuFiltrosGnr"
								style="display:none;">
								<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','re' );"
									id="btnReVisitadosPersonas"
									class="btnReVisitadosPersonas2 btn waves-effect btn-account-head">
									Re-Visitados
								</button>
								<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','visitados' );"
									id="btnVisitadosPersonas"
									class="btnVisitadosPersonas2 btn waves-effect btn-account-head">
									Visitados
								</button>
								<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','no' );"
									id="btnNoVisitadosPersonas"
									class="btnNoVisitadosPersonas2 btn waves-effect btn-account-head">
									No Visitados
								</button>
								<button onClick="nuevaPagina(1,'<?= $hoy ?>','<?= $idsEnviar ?>','' );"
									id="btnTodosPersonas"
									class="btnTodosPersonas2 btn waves-effect btn-account-head btn-account-sel"
									disabled>
									Todos
								</button>
								<button type="button" class="btn waves-effect btn-account-head m-b-3" id="filtrar2"
									data-toggle="tooltip" data-placement="top" title="Filtrar">
									<i class="fas fa-filter padding-0" style="font-size: 15px; margin: -3px;"></i>
								</button>
							</div>

							<div id="divGridPersonas">
								<div id="tbMedicos">
									<div class="m-b-15 m-t--5">
										<div class="input-group margin-0">
											<input class="form-control buscaMedInst" type="text" id="txtBuscarMedico" placeholder="Buscar médico por nombre">
											<span class="input-group-addon padding-0 buscaMedInstSpan">
												<button id="btnBuscarMedList" class="btn waves-effect btn-indigo2" style="padding: 4px 12px;">
													<i class="glyphicon glyphicon-search" style="color:#777; font-size:14px;"></i>
												</button>
											</span>
										</div>
									</div>

									<div id="tbPersonas">
									</div>
									<div id="tblCambiarRutaPersonas" style="display:none;">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--#END LISTA-->
				<!--#END LISTA-->
				<!--SHOW DATOS-->
				<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 add-padding-persona">
					<div class="card cardListMedicos" id="sinResultadosMed" style="min-height:735px; display:none;">
						<div class="body align-center">
							<i class="fas fa-search font-150 m-t-20" style="color:#ebecee;"></i>
							<h2>No hay resultados que coincidan con tu búsqueda</h2>
							<div>
								<i class="fas fa-info-circle font-15"></i>
								<span>Intenta con otros filtros</span>
							</div>
						</div>
					</div>
					<div class="card" id="divMedicosInactivos" style="min-height:700px; display:none;">
						<div class="header">
							<h2>
								<span class="font-18" id="lblNombreMedicoInactivo">
								</span>
								<small id="lblEspecialidadMedInactivo"></small>
							</h2>
						</div>
						<div class="body" id="datosPersonalesPersona2">
							<div id="divDatosPersonales2">
								<?php include "datosPersonales2.php"; ?>
							</div>
						</div>
					</div>
					<div class="card cardListMedicos margin-0" style="min-height:565px;" id="cardInfMedicos">
						<div class="header">
							<h2>
								<span class="font-18" id="lblNombreMedico1">
								</span>
								<small id="lblEspecialidad2"></small>
							</h2>
						</div>
						<div class="body" id="datosPersonalesPersona" style="padding-bottom:0;">
							<div id="divDatosPersonales" style="display:none;">
								<?php include "datosPersonales.php"; ?>
							</div>
						</div>
					</div>
				</div>
				<!--#END SHOW DATOS-->
			</div>
		</div>
		<!-- #END# Datos Médicos -->

		<!--<div class="button-float pull-right list-btn-float">
			<button class="btn bg-red btn-circle waves-effect waves-circle waves-float btn-red-hf" id="imgAgregarPersona"
			 data-toggle="tooltip" data-placement="left" title="Agregar">
				<i class="material-icons">add</i>
			</button>
		</div>-->
		<div class="fixed-action-btn">
			<a class="btn-floating btn-large btn-txt bg-red btn-red" id="imgAgregarPersona">
				<i class="material-icons">add</i><span id="spanP" class="col-white m-l-10"
					style="display:none;">AGREGAR</span>
			</a>
		</div>
	</div>
</section>

<script>
	//$("#imgPersonas").ready(function () {
	//$("#med1").click();
	//alert("hola");
	//});
</script>