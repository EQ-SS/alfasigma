<input type="hidden" id="hdnIdInst" value="" />
<input type="hidden" id="hdnIdTipoInst" value="" />
<input type="hidden" id="hdnIdRutaDatosInst" value="" />

<div id="infoInstitucion">
	<div id="tabsPerfilInst">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--40" style="margin-bottom:0;">
				<ul class="nav nav-tabs m-l--5 m-r--5 tab-col-blue p-l-5 tabDatosInst" role="tablist"
					style="background-color:#efefef;">
					<li role="presentation" class="active">
						<a id="tabPerfilInst1" href="#tabInfoI" data-toggle="tab" class="show-tooltip p-t-14">
							<i class="fas fa-building top-0 p-b-3"></i>
							<div class="divTooltip">Información</div>
							<span class="hidden-txt-tabs">Información</span>
						</a>
					</li>
					<li role="presentation">
						<a id="tabPerfilInst2" href="#tabServiciosI" data-toggle="tab" class="show-tooltip p-t-14">
							<i class="fas fa-medkit top-0 p-b-3"></i>
							<div class="divTooltip">Servicios</div>
							<span class="hidden-txt-tabs">Servicios</span>
						</a>
					</li>
					<li role="presentation">
						<a id="tabPerfilInst3" href="#tabPersonasI" data-toggle="tab" class="show-tooltip">
							<i class="material-icons">person</i>
							<div class="divTooltip">Personas</div>
							<span class="hidden-txt-tabs">Personas</span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div style="overflow-y: auto;overflow-x: hidden;" class="m-r--20 p-t-20 p-r-20 cardDatosInst">
			<div class="row clearfix">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div id="tabInfoI">
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-md-12"><span id="lblTipoInst"
											class="label bg-cyan label-esp"></span></div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Dirección: </p><label
											id="lblDireccionInst"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">IMS o ATV Brick: </p><label
											id="lblBrickInst"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Teléfono: </p><label
											id="lblTelefonoInst"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Fax: </p><label
											id="lblFaxInst"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">E-mail: </p><label
											id="lblMailInst"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">HTTP: </p><label
											id="lblPaginaInst"></label>
									</div>
								</div>
								
								<hr class="style-hr m-t-0" />
								
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">No. de tienda: </p><label
											id="lblField_01"></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p class="font-bold col-indigo text-inline">Consultorio: </p><label
											id="lblField_01_snr"></label>
									</div>
								</div>
								
								<div class="row" id="divPerfilFarmacias" hidden>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Encargado de Farmacia: </p><label
													id="lblEncargadoFarmaciaInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Nivel de Ventas: </p><label
													id="lblNivelVentasInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Nombre Comercial: </p><label
													id="lblNombreComercialInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Rotación Silanes: </p><label
													id="lblRotacionSilanesInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Tipo de Pacientes que Atiende: </p><label
													id="lblTipoPacientesAtiendeInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. de Empleados X Dia de Compra: </p><label
													id="lblNumeroEmpleadosXDiaCompraInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. de Clientes X Dia Compra: </p><label
													id="lblNumeroClientesXDiaCompraInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. de Médicos Cerca de Farmacia: </p><label
													id="lblNumeroMedicosCercaFarmaciaInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Accesibilidad: </p><label
													id="lblAccesibilidadInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. Most. Atden Ctes: </p><label
													id="lblNumeroMostradoresAtiendenClientesInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Recibe Vendedores: </p><label
													id="lblRecibeVendedoresInst"></label>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Venta Genéricos: </p><label
													id="lblVentaGenericosInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Mayorista 1: </p><label
													id="lblMayorista1Inst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Mayorista 2: </p><label
													id="lblMayorista2Inst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Mayorista 3: </p><label
													id="lblMayorista3Inst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. Anaquel: </p><label
													id="lblNumeroAnaquelInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. Visitas por Ciclo: </p><label
													id="lblNumeroVisitasCicloInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Turnos Pref. Visita: </p><label
													id="lblTurnosPreferenciaVisitaInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Ubicación: </p><label
													id="lblUbicacionInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Trabaja Inst. Pública: </p><label
													id="lblTrabajInstitucionPublicaInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Tamaño de Farmacia: </p><label
													id="lblTamFarmaciaInst"></label>
											</div>
										</div>
									</div>
								</div>
								
								<div class="row" id="divPerfilHospitales" hidden>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Tipo de Cliente: </p><label
													id="lblTipoClienteInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Nombre del Cliente: </p><label
													id="lblNombreClienteInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Encargado de Compras: </p><label
													id="lblEncargadoComprasInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Distribuidor o Mayorista: </p><label
													id="lblDistribuidorMayoristaInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Decisor de Compra: </p><label
													id="lblDecisorCompraInst"></label>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 margin-0">
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">No. de Servicios: </p><label
													id="lblNumeroServiciosInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Clinica del Dolor: </p><label
													id="lblClinicaDolorInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Prod. de la Competencia: </p><label
													id="lblPordCompetenciaInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Cual es el Precio: </p><label
													id="lblCualPrecioInst"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<p class="font-bold col-indigo text-inline">Compra Velian: </p><label
													id="lblCompraVelianInst"></label>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					</div>

					<div id="tabServiciosI">
						<input type="hidden" id="hdnIDepto" value="" />
						<!--Servicios-->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="card margin-0" style="box-shadow: 0 0px 1px rgba(0, 0, 0, 0.4);">
									<div class="header">
										<h2 style="color: #555;">
											<i class="fas fa-building font-18"></i>
											<span>Departamento</span>
										</h2>
										<div class="pull-right m-t--25">
											<button id="btnAgregarDepartamento" type="button"
												class="btn bg-indigo waves-effect btn-indigo">
												Agregar
											</button>
											<!--<button name="btnModificarDepartamento" type="button">
											Modificar				 
										</button>-->
										</div>
									</div>
									<div class="body">
										<div class="div-tbl-grey" style="border: 1px #9e9e9e solid;">
											<table id="tblDepartamentos" class="tblPlanesVisitas">
												<thead class="bg-grey">
													<tr class="align-center">
														<td style="width:18%;">
															Nombre del Departamento
														</td>
														<td style="width:17%;">
															Liderando Departamento
														</td>
														<td style="width:15%;">
															Calle
														</td>
														<td style="width:15%;">
															Colonia
														</td>
														<td style="width:15%;">
															Teléfono
														</td>
														<td style="width:10%;">
															Editar
														</td>
														<td style="width:10%;">
															Borrar
														</td>
													</tr>
												</thead>
												<tbody class="align-center" height="150px">
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margin-0">
								<div class="card margin-0" style="box-shadow: 0 0px 1px rgba(0, 0, 0, 0.4);">
									<div class="header">
										<h2 style="color: #555;">
											<i class="fas fa-user-md font-18"></i>
											<span>Personas</span>
										</h2>

										<div class="pull-right m-t--25">
											<button id="btnAgregarPersonaDepartamentoDatosInstituciones" type="button"
												class="btn bg-indigo waves-effect btn-indigo">Agregar</button>
										</div>
									</div>
									<div class="body">
										<div id="tabPersonas" align="left">
											<input type="hidden" id="hdnIdpersonaDeptoEdit" value="" />

											<label id="lblNombreDepto" class="rojo"></label>

											<div class="div-tbl-grey" style="border: 1px #9e9e9e solid;">
												<table class="tblPlanesVisitas" id="">
													<thead class="bg-grey">
														<tr class="align-center">
															<td style="width:10%;">
																Núm.
															</td>
															<td style="width:15%;">
																Apellido Paterno
															</td>
															<td style="width:15%;">
																Apellido Materno
															</td>
															<td style="width:20%;">
																Nombre
															</td>
															<td style="width:20%;">
																Especialidad
															</td>
															<td style="width:10%;">
																Editar
															</td>
															<td style="width:10%;">
																Borrar
															</td>
														</tr>
													</thead>
													<tbody class="align-center" height="150px">
														<tr>
															<td style="width:100%;">
																Sin datos que mostrar
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>

										<div id="tabVisitas" class="m-t-20">
											<label>Visitas</label>
											<div class="div-tbl-grey">
												<table class="tblPlanesVisitas">
													<thead class="bg-grey align-center">
														<tr>
															<td style="width:16%;">
																Fecha
															</td>
															<td style="width:42%;">
																Representante
															</td>
															<td style="width:42%;">
																Comentarios
															</td>
														</tr>
													</thead>
													<tbody class="align-center" style="max-height:150px;">
														<!--<tr>
															<td style="width:16%;">
																&nbsp;
															</td>
															<td style="width:42%;">
																&nbsp;
															</td>
															<td style="width:42%;">
																&nbsp;
															</td>
														</tr>-->
														<tr>
															<td style="width:100%;">
																Sin datos que mostrar
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>

										<div id="tabPlan" class="m-t-20">
											<label>Planes</label>
											<div class="div-tbl-grey">
												<table class="tblPlanesVisitas">
													<thead class="bg-grey align-center">
														<tr>
															<td style="width:16%;">
																Fecha
															</td>
															<td style="width:28%;">
																Representante
															</td>
															<td style="width:28%;">
																Nombre
															</td>
															<td style="width:28%;">
																Comentarios
															</td>
														</tr>
													</thead>
													<tbody class="align-center" style="max-height:150px;">
														<!--<tr>
															<td style="width:16%;">
																Fecha
															</td>
															<td style="width:28%;">
																Representante
															</td>
															<td style="width:28%;">
																Nombre
															</td>
															<td style="width:28%;">
																Comentarios
															</td>
														</tr>-->
														<tr>
															<td style="width:100%;">
																Sin datos que mostrar
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--<div id="tabsDepartamentos">
							<ul>
								<li>
									<a href="#tabPersonas">Personas</a>
								</li>
								<li>
									<a href="#tabVisitas">Visitas</a>
								</li>
								<li>
									<a href="#tabPlan">Plan</a>
								</li>
							</ul>

							<div id="tabPersonas" align="left">
								<input type="hidden" id="hdnIdpersonaDeptoEdit" value="" />

								<label id="lblNombreDepto" class="rojo"></label>
								<table class="table grid_scroll_body" id="">
									<thead class="bg-grey">
										<tr>
											<td width="50px" align="left">Núm.</td>
											<td width="250px" align="left">Apellido Paterno</td>
											<td width="250px" align="left">Apellido Materno</td>
											<td width="250px" align="left">Nombre</td>
											<td width="200px" align="left">Especialidad</td>
											<td width="50px" align="left">Editar</td>
											<td width="50px" align="left">Borrar</td>
										</tr>
									</thead>
									<tbody height="100px">
									</tbody>
								</table>
								<div>
									<button id="btnAgregarPersonaDepartamentoDatosInstituciones" type="button" class="btn bg-red waves-effect btn-red">Agregar</button>
								</div>
							</div>

							<div id="tabVisitas">
								<table width="100%">
									<tr class="titulo">
										<td>
											Fecha
										</td>
										<td>
											Representante
										</td>
										<td>
											Comentarios
										</td>
									</tr>
									<tr>
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

							<div id="tabPlan">
								<table width="100%">
									<tr class="titulo">
										<td>
											Fecha
										</td>
										<td>
											Representante
										</td>
										<td>
											Nombre
										</td>
										<td>
											Comentarios
										</td>
									</tr>
								</table>
							</div>
						</div>-->
					</div>

					<div id="tabPersonasI">
						<div class="div-tbl-grey">
							<table id="tblPersonasInstituciones" class="tblPlanesVisitas">
								<thead class="bg-grey">
									<tr class="align-center">
										<td style="width:7%;">
											Número
										</td>
										<td style="width:20%;">
											Paterno
										</td>
										<td style="width:20%;">
											Materno
										</td>
										<td style="width:25%;">
											Nombre
										</td>
										<td style="width:20%;">
											Esp. primaria
										</td>
										<td style="width:8%;">
											Categoría
										</td>
									</tr>
								</thead>
								<tbody>
								</tbody>
								<tfoot class="bg-white" style="border-top:1px #efefef solid; color:#555555;"></tfoot>
							</table>
						</div>
						<div>
							<button id="btnAgregarPersonaDatosInstituciones" type="button"
								class="btn bg-indigo waves-effect btn-indigo m-t-20">
								Agregar
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!--<div id="departamentoInstitucion">
		
	</div>-->

<!--<div id="personasInstitucion">
		<table width="100%">
			<tr>
				<td align="left">
					<button id="btnAgregarPersonaDatosInstituciones" type="button">
						Agregar
					</button>
				</td>
			</tr>
			<tr>
				<td>
					<table width="1000px" id="tblPersonasInstituciones" class="grid2">	
						<thead>
							<tr>
								<td width="100px">
									Número
								</td>
								<td width="200px">
									Paterno
								</td>
								<td width="200px">
									Materno
								</td>
								<td width="200px">
									Nombre
								</td>
								<td width="200px">
									Esp. primaria
								</td>
								<td width="100px">
									Categoría
								</td>
							</tr>
						</thead>
						<tbody style="height:400px;">
						</tbody>
						<tfoot></tfoot>
					</table>
				</td>
			</tr>
		</table>
	</div>-->

<div id="planInstitucion">
	<!--HISTORICO DE PLANES-->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-9 col-xs-8">
			<label class="negrita" style="font-size: 12px">Histórico de planes</label>
			<div class="select">
				<select id="lstYearPlanesInst" class="form-control">
					<?php
					for($i=date("Y");$i>=date("Y") - 5;$i--){
						if($i == date("Y")){
							echo '<option value="'.$i.'" selected>'.$i.'</option>';
						}else{
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
					}
?>
				</select>
				<div class="select_arrow"></div>
			</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-3 col-xs-4">
			<button id="imgAgregarPlanInstituciones" type="button"
				class="pull-right m-t-20 btn bg-indigo waves-effect btn-indigo" style="padding: 8px 12px;">
				Agregar
			</button>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="img-plan align-center">
				<div class="borde">1</div>
				<div class="td-cld-plan"><label id="cicloInst1">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">2</div>
				<div class="td-cld-plan"><label id="cicloInst2">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">3</div>
				<div class="td-cld-plan"><label id="cicloInst3">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">4</div>
				<div class="td-cld-plan"><label id="cicloInst4">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">5</div>
				<div class="td-cld-plan"><label id="cicloInst5">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">6</div>
				<div class="td-cld-plan"><label id="cicloInst6">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">7</div>
				<div class="td-cld-plan"><label id="cicloInst7">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">8</div>
				<div class="td-cld-plan"><label id="cicloInst8">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">9</div>
				<div class="td-cld-plan"><label id="cicloInst9">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">10</div>
				<div class="td-cld-plan"><label id="cicloInst10">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">11</div>
				<div class="td-cld-plan"><label id="cicloInst11">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">12</div>
				<div class="td-cld-plan"><label id="cicloInst12">0</label></div>
			</div>
			<div class="img-plan align-center" style="margin-right:15px;">
				<div class="borde">13</div>
				<div class="td-cld-plan"><label id="cicloInst13">0</label></div>
			</div>
			<div class="align-center m-r-15" style="display: inline-block;">
				<div class="font-bold">Total</div>
				<div class="m-t-15"><label id="acumuladoInst">0</label></div>
			</div>
			<div class="align-center" style="display: none;">
				<div class="font-bold">Frecuencia</div>
				<div class="m-t-15"><label id="lblFrecPlanInst">0</label></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="div-tbl-grey">
				<table id="tblPlanesInstituciones" class="tblPlanesVisitas">
					<thead class="bg-grey">
						<tr class="align-center">
							<td style="width:15%;">Ciclo</td>
							<td style="width:10%;">Ruta</td>
							<td style="width:15%;">Fecha</td>
							<td style="width:15%;">Hora</td>
							<td style="width:45%;">Obj. de la visita</td>
						</tr>
					</thead>
					<tbody class="pointer align-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="visitasInstitucion">
	<!--VISITAS-->
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-9 col-xs-8">
			<label class="negrita" style="font-size: 12px">Histórico de visitas</label>
			<select id="lstYearVisitasInst" class="form-control">
				<option value="0" hidden>Seleccione</option>
				<?php
					for($i=date("Y");$i>=date("Y") - 12;$i--){
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				?>
			</select>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-3 col-xs-4">
			<button id="btnAgregarVisitaInstituciones" type="button"
				class="pull-right m-t-20 btn bg-indigo waves-effect btn-indigo" style="padding: 8px 12px;">
				Agregar
			</button>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="img-plan align-center">
				<div class="borde">1</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst1">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">2</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst2">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">3</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst3">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">4</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst4">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">5</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst5">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">6</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst6">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">7</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst7">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">8</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst8">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">9</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst9">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">10</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst10">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">11</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst11">0</label></div>
			</div>
			<div class="img-plan align-center">
				<div class="borde">12</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst12">0</label></div>
			</div>
			<div class="img-plan align-center" style="margin-right:15px;">
				<div class="borde">13</div>
				<div class="td-cld-plan"><label id="cicloVisitaInst13">0</label></div>
			</div>
			<div class="align-center m-r-15" style="display: inline-block;">
				<div class="font-bold">Total</div>
				<div class="m-t-15"><label id="cicloVisitasAcumuladoInst">0</label></div>
			</div>
			<div class="align-center" style="display: none;">
				<div class="font-bold">Frecuencia</div>
				<div class="m-t-15"><label id="lblFrecVisitaInst">0</label></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="div-tbl-grey">
				<table id="tblVisitasInst" class="tblPlanesVisitas table-hover">
					<thead class="bg-grey">
						<tr class="align-center">
							<td style="width:11%;">Ciclo</td>
							<td style="width:8%;">Ruta</td>
							<td style="width:13%;">Fecha</td>
							<td style="width:9%;">Hora</td>
							<td style="width:19%;">Código visita</td>
							<td style="width:20%;">Resultado visita</td>
							<td style="width:20%;">Objetivo visita</td>
						</tr>
					</thead>
					<tbody class="pointer align-center">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="representantesInstitucion">
	<div class="div-tbl-grey" style="max-height: 645px;">
		<table class="tblPlanesVisitas">
			<thead class="bg-grey">
				<tr class="align-center">
					<td style="width:40%;text-transform:capitalize;">
						Representante
					</td>
					<td style="width:20%;">
						Teléfono
					</td>
					<td style="width:20%;">
						Celular
					</td>
					<td style="width:20%;">
						Fecha de Visita
					</td>
				</tr>
			</thead>
			<tbody>
				<tr class="align-center">
					<td style="width:100%;">
						Sin datos que mostrar
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div id="mapaInstitucion">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 margin-0">
			<div class="col-lg-6 col-md-6 col-sm-5 col-xs-5 padding-0">Latitude: <label id="lblLatitudInst"></label>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-5 col-xs-5 padding-0">Longitude: <label id="lblLongitudInst"></label>
			</div>

		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 align-right  m-t--10">
			<?php 
			if($tipoUsuario == 4){ 
			?>
			<input type="button" value="Obtener coordenadas" class="btn bg-indigo waves-effect btn-indigo" />
			<?php
				}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div id='canvasInstitucion' class="align-center map-style"></div>
		</div>
	</div>

	<input type="hidden" id="txtLatitudInstituciones" />
	<input type="hidden" id="txtLongitudInstituciones" />
	<input type="hidden" maxlength="100" id="addressInstitucionesF" placeholder="Dirección" />
</div>