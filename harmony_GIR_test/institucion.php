<input type="hidden" id="hdnCityInstNueva" value="" />
<input type="hidden" id="hdnPersonaNueva" value="" />

<?php
	$linea = sqlsrv_fetch_array(sqlsrv_query($conn, "select name as linea from COMPLINE l left outer join users u on l.CLINE_SNR = u.CLINE_SNR where u.USER_SNR = '".$_GET['idUser']."'"))['linea'];
?>


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
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Tipo *</label>
												<!--<select id="sltTipoInstNueva" class="selectpicker" data-live-search="true">-->
												<select id="sltTipoInstNueva" class="form-control" name="tipoI" required>
<?php
													$rsTipoInst = llenaCombo($conn, "14", "3");
													//$rsTipoInst = sqlsrv_query($conn, "select * from INST_TYPE where REC_STAT = 0");
													while($arrTipoInst = sqlsrv_fetch_array($rsTipoInst)){
														echo '<option value="'.$arrTipoInst['id'].'">'.$arrTipoInst['nombre'].'</option>';
													}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Subtipo *</label>
												<select id="sltSubtipoInstNueva" class="form-control" name="subTipoI">
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Formato *</label>
												<select id="sltFormatoInstNueva" class="form-control" name="formatoI">
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Estatus *</label>
												<select name="estatusI" id="sltEstatusInstNueva" class="form-control no-style-disabled" disabled>
<?php
												$rsEstatus = llenaCombo($conn, "14", "18");
												while($arrEstatus = sqlsrv_fetch_array($rsEstatus)){
													echo '<option value="'.$arrEstatus['id'].'">'.utf8_encode($arrEstatus['nombre']).'</option>';
												}
?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Categoría</label>
												<select id="sltCategoriaInstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
<?php
													$rsCategoria = llenaCombo($conn, "14", "12");
													while($arrCategoria = sqlsrv_fetch_array($rsCategoria)){
														echo '<option value="'.$arrCategoria['id'].'">'.$arrCategoria['nombre'].'</option>';
													}
?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Frecuencia</label>
												<select id="sltFrecuenciaInstNueva" class="form-control" disabled>
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "14", "11");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Nombre de la institución *</label>
												<input id="txNombreInstNueva" type="text" value="" name="nombreI" class="form-control" required />
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Calle *</label>
												<input id="txtCalleInstNueva" type="text" value="" name="calleI" class="form-control" required />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Num. Ext *</label>
												<input id="txtNumExtInstNueva" name="numExtI" type="text" value="" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Código Postal *</label>
												<input id="txtCPInstNueva" type="text" value="" maxlength="5" name="codigoPostalI" class="form-control"
												 required />
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label class="col-red">Colonia *</label>
												<select id="sltColoniaInstNueva" class="form-control">
												</select>
												<label id="sltColoniaInstNuevaError" class="error2" style="display:none;">Seleccione la colonia</label>
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Deleg/Mnpio</label>
												<input id="txtCiudadInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" hidden>
											<div class="form-group">
												<label>Brick</label>
												<input id="txtBrickInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Estado</label>
												<input id="txtEstadoInstNueva" type="text" value="" class="form-control no-style-disabled" disabled />
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Teléfono</label>
												<input name="tel1I" id="txtTel1InstNueva" type="text" value="" class="form-control" maxlength="10">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Teléfono 2</label>
												<input name="tel2I" id="txtTel2InstNueva" type="text" value="" class="form-control" maxlength="10">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Celular</label>
												<input id="txtCelularInstNueva" type="text" value="" class="form-control" disabled>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Email</label>
												<input name="emailI" id="txtEmailInstNueva" type="text" value="" class="form-control">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>HTTP</label>
												<input id="txtWebInstNueva" type="text" value="" class="form-control">
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Sucursal</label>
												<input type="text" id="txtSucursalInstNueva" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="form-group">
												<label>Comentarios</label>
												<input type="text" id="txtComentariosInstNueva" size="50" class="form-control" />
											</div>
										</div>
									</div>
								<!--</div>

								<div class ="row">-->
									<div class="row" id="farm1" hidden>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Encargado de Farmacias</label>
												<input type="text" id="txtField01InstNueva" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Nivel de ventas</label>
												<select id="sltField01InstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "483", "9");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Nombre Comercial</label>
												<input type="text" id="txtField02InstNueva" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Tipo Pac que Atiende</label>
												<form>
													<div class="multiselect">
														<div class="selectBox" onclick="showCheckboxesTipoPaciente()">
															<select class="form-control">
																<option id="sltMultiSelectTipoPaciente">Selecciona</option>
															</select>
															<div class="overSelect"></div>
														</div>
														<div id="checkboxesTipoPaciente" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
													<?php
															$rsTipoPaciente = llenaCombo($conn, 483, 10);
															$contadorChecksTipoPaciente = 0;
															$idChecks = '';
															$descripcionesCheckTipoPaciente = '';
															while($tipoPaciente = sqlsrv_fetch_array($rsTipoPaciente)){
																$contadorChecksTipoPaciente++;
																$idChk = "tipoPaciente".$contadorChecksTipoPaciente;
																echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesTipoPaciente(\''.$tipoPaciente['nombre'].'\',\''.$idChk.'\');" value="'.$tipoPaciente['id'].'"/>';
																echo '<label for="'.$idChk.'">'.utf8_encode($tipoPaciente['nombre']).'</label><br>';
															}
													?>
														</div>
													</div>
													<input type="hidden" id="hdnTotalChecksTipoPaciente"
														value="<?= $contadorChecksTipoPaciente ?>">
													<input type="hidden" id="hdnDescripcionChkTipoPaciente"
														value="<?= ($descripcionesCheckTipoPaciente == '') ? "Seleccione" : $descripcionesCheckTipoPaciente ?>" />
												</form>
											</div>
										</div>
									</div>
									<div class="row" id="farm2" hidden>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Número de empleados</label>
												<input type="text" id="txtField03InstNueva" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Numero de turnos</label>
												<input type="text" id="txtField04InstNueva" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Número de clientes X día de compra</label>
												<select id="sltField03InstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "483", "11");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Número de médicos cerca</label>
												<input type="text" id="txtField05InstNueva" class="form-control" />
											</div>
										</div>
									</div>
									<div class="row" id="farm3" hidden>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Accesibilidad</label>
												<select id="sltField04InstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "483", "12");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Recibe vendedores</label>
												<select id="sltField05InstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "483", "13");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Días sugeridos de visita</label>
												<form>
													<div class="multiselect">
														<div class="selectBox" onclick="showCheckboxesField06();">
															<select class="form-control">
																<option id="sltMultiSelectField06">Selecciona</option>
															</select>
															<div class="overSelect"></div>
														</div>
														<div id="checkboxesField06" style="display:none;border: 1px #dadada solid;height: 70px;overflow: auto;padding: 5px;background-color: #ffffff;">
													<?php
															$rsField06 = llenaCombo($conn, 483, 14);
															$contadorChecksField06 = 0;
															$idChecks = '';
															$descripcionesCheckField06 = '';
															while($field06 = sqlsrv_fetch_array($rsField06)){
																$contadorChecksField06++;
																$idChk = "field06".$contadorChecksField06;
																echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesField06(\''.$field06['nombre'].'\',\''.$idChk.'\');" value="'.$field06['id'].'"/>';
																echo '<label for="'.$idChk.'">'.utf8_encode($field06['nombre']).'</label><br>';
															}
													?>
														</div>
													</div>
													<input type="hidden" id="hdnTotalChecksField06"
														value="<?= $contadorChecksField06 ?>">
													<input type="hidden" id="hdnDescripcionChkField06"
														value="<?= ($descripcionesCheckField06 == '') ? "Seleccione" : $descripcionesCheckField06 ?>" />
												</form>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Turno de preferencia de visita</label>
												<form>
													<div class="multiselect">
														<div class="selectBox" onclick="showCheckboxesField07();">
															<select class="form-control">
																<option id="sltMultiSelectField07">Selecciona</option>
															</select>
															<div class="overSelect"></div>
														</div>
														<div id="checkboxesField07" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
													<?php
															$rsField07 = llenaCombo($conn, 483, 15);
															$contadorChecksField07 = 0;
															$idChecks = '';
															$descripcionesCheckField07 = '';
															while($field07 = sqlsrv_fetch_array($rsField07)){
																$contadorChecksField07++;
																$idChk = "field07".$contadorChecksField07;
																echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesField07(\''.$field07['nombre'].'\',\''.$idChk.'\');" value="'.$field07['id'].'"/>';
																echo '<label for="'.$idChk.'">'.utf8_encode($field07['nombre']).'</label><br>';
															}
													?>
														</div>
													</div>
													<input type="hidden" id="hdnTotalChecksField07"
														value="<?= $contadorChecksField07 ?>">
													<input type="hidden" id="hdnDescripcionChkField07"
														value="<?= ($descripcionesCheckField07 == '') ? "Seleccione" : $descripcionesCheckField07 ?>" />
												</form>
											</div>
										</div>
									</div>
									<div class="row" id="farm4" hidden>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Número de mostradores</label>
												<input type="text" id="txtField06InstNueva" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Venta de genéricos</label>
												<select id="sltField08InstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "483", "16");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Mayorista</label>
												<form>
													<div class="multiselect">
														<div class="selectBox" onclick="showCheckboxesField09();">
															<select class="form-control">
																<option id="sltMultiSelectField09">Selecciona</option>
															</select>
															<div class="overSelect"></div>
														</div>
														<div id="checkboxesField09" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
													<?php
															$rsField09 = llenaCombo($conn, 483, 17);
															$contadorChecksField09 = 0;
															$idChecks = '';
															$descripcionesCheckField09 = '';
															while($field09 = sqlsrv_fetch_array($rsField09)){
																$contadorChecksField09++;
																$idChk = "field09".$contadorChecksField09;
																echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesField09(\''.$field09['nombre'].'\',\''.$idChk.'\');" value="'.$field09['id'].'"/>';
																echo '<label for="'.$idChk.'">'.utf8_encode($field09['nombre']).'</label><br>';
															}
													?>
														</div>
													</div>
													<input type="hidden" id="hdnTotalChecksField09"
														value="<?= $contadorChecksField09 ?>">
													<input type="hidden" id="hdnDescripcionChkField09"
														value="<?= ($descripcionesCheckField09 == '') ? "Seleccione" : $descripcionesCheckField09 ?>" />
												</form>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Número de anaqueles</label>
												<input type="text" id="txtField07InstNueva" class="form-control" />
											</div>
										</div>
									</div>
									<div class="row" id="farm5" hidden>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Ubicación</label>
												<select id="sltField10InstNueva" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "483", "18");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
									</div>
									<div class="row" id="farm6" hidden>
									</div>
									
									<div class="row" id="hospital1" hidden>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
											<div class="form-group">
												<label>Director de Hospital</label>
												<input type="text" id="txtField01InstNuevaHosp" name="field01InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
											<div class="form-group">
												<label>Director medico</label>
												<input type="text" id="txtField02InstNuevaHosp" name="field02InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Encargado de compras</label>
												<input type="text" id="txtField03InstNuevaHosp" name="field03InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
											<div class="form-group">
												<label>Jefe de enfermeras</label>
												<input type="text" id="txtField04InstNuevaHosp" name="field04InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
											<label>Servicios</label>
											<form>
												<div class="multiselect">
													<div class="selectBox" onclick="showCheckboxesTipoCliente()">
														<select class="form-control">
															<option id="sltMultiSelectTipoCliente">Selecciona</option>
														</select>
														<div class="overSelect"></div>
													</div>
													<div id="checkboxesTipoCliente" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
													<?php
														$rsTipoCliente = llenaCombo($conn, 485, 15);
														$contadorChecksTipoCliente = 0;
														$idChecks = '';
														$descripcionesCheckTipoCliente = '';
														while($tipoCliente = sqlsrv_fetch_array($rsTipoCliente)){
															$contadorChecksTipoCliente++;
															$idChk = "tipoCliente".$contadorChecksTipoCliente;
															echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesTipoCliente(\''.$tipoCliente['nombre'].'\',\''.$idChk.'\');" value="'.$tipoCliente['id'].'"/>';
															echo '<label for="'.$idChk.'">'.$tipoCliente['nombre'].'</label><br>';
														}
													?>
													</div>
												</div>
												<input type="hidden" id="hdnTotalChecksTipoCliente"
													value="<?= $contadorChecksTipoCliente ?>">
												<input type="hidden" id="hdnDescripcionChkTipoCliente"
													value="<?= ($descripcionesCheckTipoCliente == '') ? "Seleccione" : $descripcionesCheckTipoCliente ?>" />
											</form>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
											<label>Distribuidor /Mayorista</label>
											<form>
												<div class="multiselect">
													<div class="selectBox" onclick="showCheckboxesProdCompetencia()">
														<select class="form-control">
															<option id="sltMultiSelectProdCompetencia">Selecciona</option>
														</select>
														<div class="overSelect"></div>
													</div>
													<div id="checkboxesProdCompetencia" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
													<?php
														$rsProdCompetencia = llenaCombo($conn, 485, 16);
														$contadorChecksProdCompetencia = 0;
														$idChecks = '';
														$descripcionesCheckProdCompetencia = '';
														while($prodCompetencia = sqlsrv_fetch_array($rsProdCompetencia)){
															$contadorChecksProdCompetencia++;
															$idChk = "prodCompetencia".$contadorChecksProdCompetencia;
															echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesProdCompetencia(\''.$prodCompetencia['nombre'].'\',\''.$idChk.'\');" value="'.$prodCompetencia['id'].'"/>';
															echo '<label for="'.$idChk.'">'.$prodCompetencia['nombre'].'</label><br>';
														}
													?>
													</div>
												</div>
												<input type="hidden" id="hdnTotalChecksProdCompetencia"
													value="<?= $contadorChecksProdCompetencia ?>">
												<input type="hidden" id="hdnDescripcionChkProdCompetencia"
													value="<?= ($descripcionesCheckProdCompetencia == '') ? "Seleccione" : $descripcionesCheckProdCompetencia ?>" />
											</form>
										</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
												<div class="form-group">
													<label>No de camas</label>
													<input type="text" id="txtField05InstNuevaHosp" name="field05InstNuevaHosp" class="form-control" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
												<div class="form-group">
													<label>Promedio de compra mensual</label>
													<input type="text" id="txtField06InstNuevaHosp" name="field06InstNuevaHosp" class="form-control" />
												</div>
											</div>
											<!--
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'NUTRI') ? '' : 'hidden' ?>>
												<div class="form-group">
													<label>No de cunas</label>
													<input type="text" id="txtField07InstNuevaHosp" class="form-control" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'NUTRI') ? '' : 'hidden' ?>>
												<div class="form-group">
													<label>No de nacimientos X mes nutribay</label>
													<input type="text" id="txtField08InstNuevaHosp" class="form-control" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'NUTRI') ? '' : 'hidden' ?>>
											<div class="form-group">
												<label>No de enfermeras en la maternidad</label>
												<input type="text" id="txtField09InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'NUTRI') ? '' : 'hidden' ?>>
											<div class="form-group">
												<label>Categoría de la maternidad</label>
												<select id="sltField03InstNuevaHosp" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "485", "3");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'NUTRI') ? '' : 'hidden' ?>>
											<div class="form-group">
												<label>Sub categoría</label>
												<select id="sltField04InstNuevaHosp" class="form-control">
													<option value="00000000-0000-0000-0000-000000000000" selected>Seleccione</option>
													<?php					
													$rsFrecuencia = llenaCombo($conn, "485", "4");
													while($arrFrecuencia = sqlsrv_fetch_array($rsFrecuencia)){
														echo '<option value="'.$arrFrecuencia['id'].'">'.$arrFrecuencia['nombre'].'</option>';
													}
												?>
												</select>
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'ODO') ? '' : 'hidden' ?>>
											<div class="form-group">
												<label>No de odontólogos</label>
												<input type="text" id="txtField10InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'ODO') ? '' : 'hidden' ?>>
											<div class="form-group">
												<label>No de pacientes a la semana</label>
												<input type="text" id="txtField11InstNuevaHosp" class="form-control" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" <?= ($linea == 'ODO') ? '' : 'hidden' ?>>
											<div class="form-group">
												<label>Encargado o administrador de la clínica</label>
												<input type="text" id="txtField12InstNuevaHosp" class="form-control" />
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
</div>
</div>