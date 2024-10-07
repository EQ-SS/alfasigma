<input type="hidden" id="hdnCityInstNueva" value="" />
<input type="hidden" id="hdnPersonaNueva" value="" />

<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header">
				<h2>
					Institución
				</h2>
				<div class="align-right" style="margin: -25px 0px -20px 0px;">
					<p title="Cerrar" id="btnCancelarInstNueva" class="pointer p-t-5">
						<i class="material-icons">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div id="tabsInst">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-left">
						<ul class="nav nav-tabs" role="tablist">
							<!--<li role="presentation" class="active">
								<a href="#home_with_icon_title" data-toggle="tab">
									<i class="material-icons">home</i> HOME
								</a>
							</li>-->
							<li role="presentation" class="active">
								<a href="#tabsBasico" data-toggle="tab">
									<i class="material-icons">business</i> Básico
								</a>
							</li>
							<li id="liFarmaciasNueva" style="display:none;"><a href="#tabsFarmacia">Perfil de Farmacias</a></li>
							<li id="liHospitalesNueva" style="display:none;"><a href="#tabsHospital">Perfil de Hospitales</a></li>
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
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Tipo *</label>
											<!--<select id="sltTipoInstNueva" class="selectpicker" data-live-search="true">-->
											<select id="sltTipoInstNueva" class="form-control">
												<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
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
											<label>Subtipo</label>
											<select id="sltSubtipoInstNueva" class="form-control">
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Formato</label>
											<select id="sltFormatoInstNueva" class="form-control">
											</select>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Estatus *</label>
											<select id="sltEstatusInstNueva" class="form-control no-style-disabled" disabled>
												<?php
											$rsEstatus = llenaCombo($conn, "14", "6");
											while($arrEstatus = sqlsrv_fetch_array($rsEstatus)){
												if($arrEstatus['nombre'] == 'ACTIVO'){
													echo '<option value="'.$arrEstatus['id'].'" selected>'.utf8_encode($arrEstatus['nombre']).'</option>';
												}else{
													echo '<option value="'.$arrEstatus['id'].'">'.utf8_encode($arrEstatus['nombre']).'</option>';
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
											<select id="sltCategoriaInstNueva" class="form-control">
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
											<input id="txNombreInstNueva" type="text" value="" class="form-control" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Calle *</label>
											<input id="txtCalleInstNueva" type="text" value="" class="form-control" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Num. Ext</label>
											<input id="txtNumExtInstNueva" type="text" value="" class="form-control" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Código Postal *</label>
											<input id="txtCPInstNueva" type="text" value="" class="form-control" />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label class="col-red">Colonia *</label>
											<select id="sltColoniaInstNueva" class="form-control">
											</select>
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
											<label>Brick</label>
											<input id="txtBrickInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Teléfono</label>
											<input id="txtTel1InstNueva" type="text" value="" class="form-control">
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Teléfono 2</label>
											<input id="txtTel2InstNueva" type="text" value="" class="form-control">
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
											<input id="txtEmailInstNueva" type="text" value="" class="form-control">
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>HTTP</label>
											<input id="txtWebInstNueva" type="text" value="" class="form-control">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Comentarios</label>
											<!--<textarea id="txtComentariosInstNueva" rows="5" cols="30"></textarea>-->
											<input type="text" id="txtComentariosInstNueva" size="50" class="form-control" />
										</div>
									</div>
									<!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
										<div class="form-group">
											<label>Representantes unidos</label>
											<textarea id="txtRepresentantesUnidos" rows="5" cols="30" ></textarea>
										</div>
									</div>-->
								</div>
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
										
											echo '<option value="'.$arrTurnos['id'].'">'.utf8_encode($arrTurnos['nombre']).'</option>';
										
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
			</div>
		</div>
	</div>
	<div class="align-center">
		<button id="btnGuardarInstNueva" type="button" class="m-t-5 btn bg-indigo waves-effect font-15 btn-indigo-hover">
			Guardar
		</button>
	</div>
</div>