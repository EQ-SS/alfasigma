<input type="hidden" id="hdnCityInstNueva" value="" />
<input type="hidden" id="hdnPersonaNueva" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Registro institución 
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnGuardarInstNueva" type="button" class="m-t-5 btn bg-indigo waves-effect font-15 btn-indigo-hover">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarInstNueva" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div id="tabsInst">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--20">
						<ul class="nav nav-tabs m-l--35 m-r--35 tab-col-blue p-l-5" role="tablist" style="background-color:#efefef;">
							<li role="presentation" class="active">
								<a href="#tabsBasico" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">business</i>
									<div class="divTooltip">Básico</div>
									<span class="hidden-txt-tabs">Básico</span>
								</a>
							</li>
							<!--<li id="tabPer2" role="presentation">
								<a href="#tabs-3" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">account_balance</i>
									<div class="divTooltip">Bancos/Aseguradoras</div>
									<span class="hidden-txt-tabs">Bancos/Aseguradoras</span>
								</a>
							</li>-->
							<li id="liFarmaciasNueva" style="display:none;"><a href="#tabsFarmacia">Perfil de Farmacias</a></li>
							<li id="liHospitalesNueva" style="display:none;"><a href="#tabsHospital">Perfil de Hospitales</a></li>
							
							<li id="perfilFarmacia" style="display:none;" role="presentation">
								<a href="#tabsPerfilFarmacia" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">local_hospital</i>
									<div class="divTooltip">Perfil</div>
									<span class="hidden-txt-tabs">Perfil</span>
								</a>
							</li>
						</ul>

						<!--<ul class="no-style-list">
							<li><a href="#tabsBasico" id="liBasicoInst">
									<button type="button" class="btn bg-teal waves-effect btn-li-menu" style="cursor:default;">
										<i class="material-icons">business</i>
										<span>Básico</span>
									</button>
								</a></li>
							<li id="liFarmaciasNueva" style="display:none;"><a href="#tabsFarmacia">Perfil de Farmacias</a></li>
							<li id="liHospitalesNueva" style="display:none;"><a href="#tabsHospital">Perfil de Hospitales</a></li>
						</ul>-->
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="new-div add-scroll-y">
							<div id="tabsBasico">
								<form id="formAgregarInst">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red" id="cuentaFarmacia">Cuenta *</label>
												<label class="col-red" id="tipoHospitales">Tipo *</label>
												<!--<select id="sltTipoInstNueva" class="selectpicker" data-live-search="true">-->
												<select id="sltTipoInstNueva" class="form-control" name="tipoI">
													<?php
												$rsTipoInst = llenaCombo($conn, "14", "1");/*sqlsrv_query($conn, "select * from INST_TYPE where REC_STAT = 0");*/
												while($arrTipoInst = sqlsrv_fetch_array($rsTipoInst)){
													echo '<option value="'.$arrTipoInst['id'].'">'.$arrTipoInst['nombre'].'</option>';
												}
						?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red" id="tipoFarmacia">Tipo *</label>
												<label class="col-red" id="subtipoHospital">Subtipo *</label>
												<select id="sltSubtipoInstNueva" class="form-control" name="subTipoI">
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red" id="subtipoFarmacia">Subtipo *</label>
												<label class="col-red" id="formatoHospital">Formato *</label>
												<select id="sltFormatoInstNueva" class="form-control" name="formatoI">
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Estatus *</label>
												<select name="estatusI" id="sltEstatusInstNueva" class="form-control no-style-disabled" required disabled>
													<?php
											$rsEstatus = llenaCombo($conn, "14", "6");
											while($arrEstatus = sqlsrv_fetch_array($rsEstatus)){
												if($arrEstatus['nombre'] == 'ACTIVO'){
													echo '<option value="'.$arrEstatus['id'].'" selected>'.$arrEstatus['nombre'].'</option>';
												}else{
													echo '<option value="'.$arrEstatus['id'].'">'.$arrEstatus['nombre'].'</option>';
												}

											}
						?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
									  
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Categoría</label>
												<select id="sltCategoriaInstNueva" class="form-control" >
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php
											$rsCategoria = llenaCombo($conn, "14", "313");
											while($arrCategoria = sqlsrv_fetch_array($rsCategoria)){
												echo '<option value="'.$arrCategoria['id'].'">'.$arrCategoria['nombre'].'</option>';
											}
						?>
												</select>
											</div> 
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Frecuencia</label>
												<select id="sltFrecuenciaInstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "14", "5");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrCategoria['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
									  
										<!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>País</label>
											<select style="width:150px;"  id="sltPais">
												<option>Seleccione</option>
												<option id="MEXICO" selected>México</option>
											</select>
										</div>
									</div>-->
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Institución *</label>
												<input id="txNombreInstNueva" type="text" value="" name="nombreI" class="form-control" required />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Calle *</label>
												<input id="txtCalleInstNueva" type="text" value="" name="calleI" class="form-control" required />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Num. Ext *</label>
												<input id="txtNumExtInstNueva" type="text" value="" class="form-control" name="numExtI"/>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Código Postal *</label>
												<input id="txtCPInstNueva" type="text" value="" maxlength="5" name="codigoPostalI" class="form-control"
												 required />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Colonia *</label>
												<select id="sltColoniaInstNueva" class="form-control">
												</select>
												<label id="sltColoniaInstNuevaError" class="error2" style="display:none;">Seleccione la colonia</label>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Deleg/Mnpio</label>
												<input id="txtCiudadInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Estado</label>
												<input id="txtEstadoInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-red">Brick *</label>
												<input id="txtBrickInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Teléfono</label>
												<input name="tel1I" id="txtTel1InstNueva" type="text" value="" class="form-control">
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Teléfono 2</label>
												<input name="tel2I" id="txtTel2InstNueva" type="text" value="" class="form-control">
											</div>
										</div>
									</div>
									<div class="row">
										<!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Celular</label>
											<input id="txtCelularInstNueva" type="text" value="">
										</div>
									</div>-->
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Email</label>
												<input name="emailI" id="txtEmailInstNueva" type="text" value="" class="form-control">
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>HTTP</label>
												<input id="txtWebInstNueva" type="text" value="" class="form-control">
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Comentarios</label>
												<!--<textarea id="txtComentariosInstNueva" rows="5" cols="30"></textarea>-->
												<input type="text" id="txtComentariosInstNueva" size="50" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
											<div class="form-group" id="divRutaInst" hidden>
												<label class="col-red">Ruta *</label>
												<select class="form-control" id="sltRutaInstNueva" >
													<option value="00000000-0000-0000-0000-000000000000" hidden>Seleccione</option>
<?php
														$rsRutas = sqlsrv_query($conn, "select USER_SNR, LNAME + ' ' + MOTHERS_LNAME + ' ' + FNAME as nombre from users where user_snr in ('".$ids."') and USER_TYPE = 4 and REC_STAT = 0 order by LNAME");
														while($ruta = sqlsrv_fetch_array($rsRutas)){
															echo '<option value="'.$ruta['USER_SNR'].'">'.$ruta['nombre'].'</option>';
														}
?>
												</select>
											</div>
										</div>
										<!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Representantes unidos</label>
											<textarea id="txtRepresentantesUnidos" rows="5" cols="30" ></textarea>
										</div>
									</div>-->
									</div>
								</form>
							</div>
						</div>
					</div>

					<div id="tabsFarmacia">
						<table width="100%" border="0">
							<tr>
								<td class="negrita" align="left">
									Nivel de ventas:<br>
									<select style="width:150px;" id="sltNivelVentasInstNueva" class="form-control">
										<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
										<?php
									$rsNivelVentas = llenaCombo($conn, "482", "1005");
									while($arrNivelVentas = sqlsrv_fetch_array($rsNivelVentas)){
										echo '<option value="'.$arrNivelVentas['id'].'">'.$arrNivelVentas['nombre'].'</option>';
									}
				?>
									</select>
					</div>
					</td>
					<td class="negrita" align="left">
						Nombre Comer Farm:<br>
						<input type="text" id="txtNomComercialFarmInstNueva" value="">
					</td>
					<td class="negrita" align="left">
						Rotación:<br>
						<div class="select"><select style="width:150px;" id="sltRotacionInstNueva">
								<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
								<?php
									$rsRotacion = llenaCombo($conn, "482", "1002");
									while($arrRotacion = sqlsrv_fetch_array($rsRotacion)){
										echo '<option value="'.$arrRotacion['id'].'">'.$arrRotacion['nombre'].'</option>';
									}
				?>
							</select>
							<div class="select_arrow"></div>
						</div>
					</td>
					<td class="negrita" align="left">
						Tipo Pac que Atiende:<br>
						<div class="select">
							<select style="width:150px;" id="sltTipoPacienteInstNueva">
								<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
								<?php
											$rsTipoPaciente = llenaCombo($conn, "482", "1003");
											while($arrTipoPaciente = sqlsrv_fetch_array($rsTipoPaciente)){
												echo '<option value="'.$arrTipoPaciente['id'].'">'.$arrTipoPaciente['nombre'].'</option>';
											}
				?>
							</select>
							<div class="select_arrow"></div>
						</div>
					</td>
					</tr>
					<tr>
						<td class="negrita" align="left"><br>
							Número de empleados<br>
							<input type="text" id="sltNumEmpInstNueva" value="" />
						</td>
						<td class="negrita" align="left"><br>
							Núm Ctes X Día Compra:<br>
							<div class="select"><select style="width:150px;" id="sltNumCtesXdiaInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsNumCtes = llenaCombo($conn, "482", "1004");
									while($arrNumCtes = sqlsrv_fetch_array($rsNumCtes)){
										echo '<option value="'.$arrNumCtes['id'].'">'.$arrNumCtes['nombre'].'</option>';
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Núm Med Cerca Farm:<br>
							<input type="text" id="txtNumMedFarmInstNueva" value="">
						</td>
						<td class="negrita" align="left"><br>
							Accesibilidad Farmacia:<br>
							<div class="select"><select style="width:150px;" id="sltAccesibilidadInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsAccesibilidad = llenaCombo($conn, "482", "1006");
									while($arrAccesibilidad = sqlsrv_fetch_array($rsAccesibilidad)){
										echo '<option value="'.$arrAccesibilidad['id'].'">'.$arrAccesibilidad['nombre'].'</option>';
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="negrita" align="left"><br>
							Num Most Atden Ctes:<br>
							<input type="text" id="txtNumCtesInstNueva" value="">
						</td>
						<td class="negrita" align="left"><br>
							Recibe Vendedores:<br>
							<div class="select"><select style="width:150px;" id="sltRecibeVendedoresInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsRecibeVendedores = llenaCombo($conn, "482", "1007");
									while($arrRecibeVendedores = sqlsrv_fetch_array($rsRecibeVendedores)){
										echo '<option value="'.$arrRecibeVendedores['id'].'">'.$arrRecibeVendedores['nombre'].'</option>';
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Venta de Genéricos:<br>
							<div class="select"><select style="width:150px;" id="sltVentaGenericosInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsVentaGenericos = llenaCombo($conn, "482", "1008");
									while($arrVentaGenericos = sqlsrv_fetch_array($rsVentaGenericos)){
										echo '<option value="'.$arrVentaGenericos['id'].'">'.$arrVentaGenericos['nombre'].'</option>';
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Tamaño de la Farmacia:<br>
							<div class="select"><select style="width:150px;" id="sltTamFarmaciaInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsTamFarmacia = llenaCombo($conn, "482", "1012");
									while($arrTamFarmacia = sqlsrv_fetch_array($rsTamFarmacia)){
										
											echo '<option value="'.$arrTamFarmacia['id'].'">'.$arrTamFarmacia['nombre'].'</option>';
										
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="negrita" align="left"><br>
							Mayorista 1:<br>
							<div class="select"><select style="width:150px;" id="sltMayorista1InstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsMayorista = llenaCombo($conn, "14", "404");
									while($arrMayorista = sqlsrv_fetch_array($rsMayorista)){
										
											echo '<option value="'.$arrMayorista['id'].'">'.$arrMayorista['nombre'].'</option>';
										
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Mayorista 2:<br>
							<div class="select"><select style="width:150px;" id="sltMayorista2InstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsMayorista2 = llenaCombo($conn, "14", "404");
									while($arrMayorista2 = sqlsrv_fetch_array($rsMayorista2)){
									
											echo '<option value="'.$arrMayorista2['id'].'">'.$arrMayorista2['nombre'].'</option>';
										
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Núm de Anaquel:<br>
							<input type="text" id="txtNumAnaquelesInstNueva" value="">
						</td>
						<td class="negrita" align="left"><br>
							Núm de Visitas por Ciclo:<br>
							<input type="text" id="txtNumVisitasXcicloInstNueva" value="">
						</td>
					</tr>
					<tr>
						<td class="negrita" align="left"><br>
							Turnos Pref Visita:<br>
							<div class="select"><select style="width:150px;" id="sltTurnosInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsTurnos = llenaCombo($conn, "482", "1009");
									while($arrTurnos = sqlsrv_fetch_array($rsTurnos)){
										
											echo '<option value="'.$arrTurnos['id'].'">'.$arrTurnos['nombre'].'</option>';
										
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Trabaja Inst Pub:<br>
							<div class="select"><select style="width:150px;" id="sltTrabajaInstPublicaInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsTrabajaInstPublica = llenaCombo($conn, "482", "1007");
									while($arrTrabajaInstPublica = sqlsrv_fetch_array($rsTrabajaInstPublica)){
										
											echo '<option value="'.$arrTrabajaInstPublica['id'].'">'.$arrTrabajaInstPublica['nombre'].'</option>';
										
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>
							Ubicación Farm:<br>
							<div class="select"><select style="width:150px;" id="sltUbicacionInstNueva">
									<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
									<?php
									$rsUbicacion = llenaCombo($conn, "482", "1010");
									while($arrUbicacion = sqlsrv_fetch_array($rsUbicacion)){
										echo '<option value="'.$arrUbicacion['id'].'">'.$arrUbicacion['nombre'].'</option>';
									}
				?>
								</select>
								<div class="select_arrow"></div>
							</div>
						</td>
						<td class="negrita" align="left"><br>

							<input type="text" id="txtFrecuenciaVisitaInstNueva" value="" style="display:none;">
						</td>
					</tr>
					</table>
				</div>

				<div id="tabsHospital">
					<table width="100%" border="0" style="border-spacing:  5px 15px;">
						<tr>
							<td class="negrita" width="25%" align="left">
								Número de camas:<br>
								<input type="text" id="txtNumCamasInstNueva" value="" />
							</td>
							<td class="negrita" width="25%" align="left">
								Número de quirófanos<br>
								<input type="text" id="txtNumQuirofanosInstNueva" value="" />
							</td>
							<td class="negrita" width="25%" align="left">
								Número de salas de expulsión
								<input type="text" id="txtNumSalasExpulsionInstNueva" value="" />
							</td>
							<td class="negrita" width="25%" align="left">
								Número de cunas
								<input type="text" id="txtNumCunasInstNueva" value="" />
							</td>
						</tr>
						<tr>
							<td class="negrita" width="25%" align="left">
								Número de incubadoras:<br>
								<input type="text" id="txtNumIncubadorasInstNueva" value="" />
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkTerapiaIntensiva" />
								Terapia intensiva
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkUnidadCuidadosIntensivos" />
								Unidad de cuidados intensivos
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkInfectologia" />
								Infectología
							</td>
						</tr>
						<tr>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkLaboratorio" />
								Laboratorio
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkUrgencias" />
								Urgencias
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkRayosx" />
								Rayos X
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkFarmacia" />
								Farmacia
							</td>
						</tr>
						<tr>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkBotiquin" />
								Botiquin
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkEndoscopia" />
								Endoscopia
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkConsultaExterna" />
								Consulta externa
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkCirugiaAmbulatoria" />
								Cirugia ambulatoria
							</td>
						</tr>
						<tr>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkScanner" />
								Scanner
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkUltrasonido" />
								Ultrasonido
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkDialisis" />
								Diálisis
							</td>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkHemodialisis" />
								Hemodiálisis
							</td>
						</tr>
						<tr>
							<td class="negrita" width="25%" align="left">
								<input type="checkbox" id="chkResonanciaMagnetica" />
								Resonancia magnética
							</td>
							<td class="negrita" width="25%" align="left">
								&nbsp;
							</td>
							<td class="negrita" width="25%" align="left">
								&nbsp;
							</td>
							<td class="negrita" width="25%" align="left">
								&nbsp;
							</td>
						</tr>
					</table>
				</div>

				<div id="tabsPerfilFarmacia">
					<form id="formAgregarPerfilFarm">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label id="lbFlonorm">Flonorm</label>
									<div class="select">
										<select class="form-control" name="flonorm" id="sltFlonorm" onchange="calcularCampoAlfa()">
											<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
												$rsFlonorm = llenaCombo($conn, "483", "2");
												while($arrFlonorm = sqlsrv_fetch_array($rsFlonorm)){
											
													echo '<option value="'.$arrFlonorm['id'].'">'.$arrFlonorm['nombre'].'</option>';
											
												}
?>
										</select>
										<div class="select_arrow"></div>
									</div>									
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label id="lbVessel">Vessel</label>
									<div class="select">
										<select id="sltVessel" class="form-control" name="vessel" onchange="calcularCampoAlfa()">
											<option value="00000000-0000-0000-0000-000000000000"> Seleccione... </option>
<?php
												$rsVessel = llenaCombo($conn, "483", "3");
												while($arrVessel = sqlsrv_fetch_array($rsVessel)){
											
													echo '<option value="'.$arrVessel['id'].'">'.$arrVessel['nombre'].'</option>';
											
												}
?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label id="lbAteka">Ateka</label>
									<div class="select">
										<select id="sltAteka" class="form-control" name="ateka" onchange="calcularCampoAlfa()">
											<option value="00000000-0000-0000-0000-000000000000"> Seleccione... </option>
<?php
												$rsAteka = llenaCombo($conn, "483", "4");
												while($arrAteka = sqlsrv_fetch_array($rsAteka)){
											
													echo '<option value="'.$arrAteka['id'].'">'.$arrAteka['nombre'].'</option>';
											
												}
?>
										</select>
									</div>
									
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Zirfos</label>
									<div class="select">
										<select id="sltZirfos" class="form-control" onchange="calcularCampoAlfa()">
											<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
<?php
												$rsZirfos = llenaCombo($conn, "483", "5");
												while($arrZirfos = sqlsrv_fetch_array($rsZirfos)){
											
													echo '<option value="'.$arrZirfos['id'].'">'.$arrZirfos['nombre'].'</option>';
											
												}
?>
										</select>
									</div>
								</div> 
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Esoxx</label>
									<div class="select">
										<select id="sltEsoxx" class="form-control" onchange="calcularCampoAlfa()">
											<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
<?php
												$rsEsoxx = llenaCombo($conn, "483", "6");
												while($arrEsoxx = sqlsrv_fetch_array($rsEsoxx)){
											
													echo '<option value="'.$arrEsoxx['id'].'">'.$arrEsoxx['nombre'].'</option>';
											
												}
?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label>Categoría Alfasigma</label>

									<select id="sltCatAlfa" class="form-control" disabled>
											<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
<?php
												$rsCatAlfa = llenaCombo($conn, "483", "7");
												while($arrCatAlfa = sqlsrv_fetch_array($rsCatAlfa)){
											
													echo '<option value="'.$arrCatAlfa['id'].'">'.$arrCatAlfa['nombre'].'</option>';
											
												}
?>
										</select>

										<!--<input id="txtCatAlfa" type="text" value="" class="form-control no-style-disabled" readonly="readonly" />-->
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
								<div class="form-group">
									<label> Mayoristas </label>
									<div class="select">
										<select id="sltMayoristas" class="form-control">
											<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
<?php
											$rsMayoristas = llenaCombo($conn, "483", "1");
												while($arrMayoristas = sqlsrv_fetch_array($rsMayoristas)){
											
													echo '<option value="'.$arrMayoristas['id'].'">'.$arrMayoristas['nombre'].'</option>';
											
												}
?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</div>
</div>
