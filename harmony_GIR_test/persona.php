<?php
	$qEtiquetas = "select cf.table_nr, cft.LONG_TEXT, 
		case cf.table_nr when 14 then 'i_' + cf.NAME else 
		case cf.table_nr when 20 then 'plw_' + cf.name else 
		case cf.table_nr when 13 then 'c_' + cf.name else 
		case cf.table_nr when 8 then 'd_' + cf.name else 
		case cf.table_nr when 7 then 'e_' + cf.name else 
		case cf.table_nr when 178 then 'b_' + cf.name else
		cf.NAME end end end end end end as NAME, 
		cf.EDITABLE, cf.MANDATORY, cf.CONF_FIELD_SNR
		from CONFIG_TABLE ct
		inner join CONFIG_FIELD cf on cf.TABLE_NR = ct.TABLE_NR
		inner join CONFIG_FIELD_TRANSLATION cft on cft.CONF_FIELD_SNR = cf.CONF_FIELD_SNR
		where ct.NAME in ('person', 'inst', 'perslocwork', 'city','district','state','brick')
		and cft.LANG_NR = 8 ";
		
	$rsEtiquetas = sqlsrv_query($conn, $qEtiquetas);
	
	$arrEtiquetas = array();
	while($etiqueta = sqlsrv_fetch_array($rsEtiquetas)){
		$asterisco = $etiqueta['MANDATORY'] == 1 ? ' *' : '';
		$arrEtiquetas[$etiqueta['NAME']] = array('etiqueta' => utf8_encode($etiqueta['LONG_TEXT']), 'obligatorio' => $asterisco, 'habilitado' => $etiqueta['EDITABLE']);
	}
	
	//print_r($arrEtiquetas);
	
?>
<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div cardPersonaCenter">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0 headerCardCapa3">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Registro Médico
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnReactivarPersonaNuevo" style="display:none;"
						class="btn bg-indigo waves-effect btn-indigo" type="button">
						Reactivar
					</button>
					<button id="btnGuardarPersonaNuevo" type="button"
						class="btn bg-indigo waves-effect btn-indigo m-l-10" <?=($tipoUsuario==5)?
					 "disabled" : "" ?>>
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarPersonaNuevo" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div id="tabsPersona">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--20 navbarTabs">
						<ul class="nav nav-tabs m-l--35 m-r--35 tab-col-blue p-l-5" role="tablist"
							style="background-color:#efefef;">
							<li id="tabPer1" role="presentation" class="active">
								<a href="#tabs-1" id="aPerfilPersona" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">person</i>
									<div class="divTooltip">Perfil</div>
									<span class="hidden-txt-tabs">Perfil</span>
								</a>
							</li>
							<li id="tabPer2" role="presentation">
								<a id="aTabPer2" href="#tabs-3" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">account_balance</i>
									<div class="divTooltip">Bancos/Aseguradoras</div>
									<span class="hidden-txt-tabs">Bancos/Aseguradoras</span>
								</a>
							</li>
							<li id="tabPer5" role="presentation" style="display:none;">
								<a href="#tabs-2" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">place</i>
									<div class="divTooltip">Dir. trabajo</div>
									<span class="hidden-txt-tabs">Dirección de trabajo</span>
								</a>
							</li>
							<li id="tabPer7" role="presentation">
								<a id="aTabPer7" href="#tabs-5" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">note</i>
									<div class="divTooltip">Notas</div>
									<span class="hidden-txt-tabs">Notas</span>
								</a>
							</li>
						</ul>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="new-div add-scroll-y">
							<form id="formAgregarPersona">
								<div id="tabs-1">
									<div id="tblDatosGnr">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['perstype_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['perstype_snr']['etiqueta'].$arrEtiquetas['perstype_snr']['obligatorio'] ?></label>
													<select id="sltTipoPersonaNuevo" class="form-control"
														name="tipoPersona" >
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsTipoPersona = llenaCombo($conn, "19", "3");
														while($tipo = sqlsrv_fetch_array($rsTipoPersona)){
															echo '<option value="'.$tipo['id'].'">'.utf8_encode($tipo['nombre']).'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['lname']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['lname']['etiqueta'].$arrEtiquetas['lname']['obligatorio'] ?></label>
													<input type="text" value="" id="txtPaternoPersonaNuevo"
														class="form-control" name="apellidoP"
														placeholder="Apellido Paterno" required />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['mothers_lname']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['mothers_lname']['etiqueta'].$arrEtiquetas['mothers_lname']['obligatorio'] ?></label>
													<input name="apellidoM" type="text" value=""
														id="txtMaternoPersonaNuevo" class="form-control"
														placeholder="Apellido Materno" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['fname']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['fname']['etiqueta'].$arrEtiquetas['fname']['obligatorio'] ?></label>
													<input type="text" value="" id="txtNombrePersonaNuevo"
														class="form-control" name="nombreP" placeholder="Nombre"
														required />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['spec_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['spec_snr']['etiqueta'].$arrEtiquetas['spec_snr']['obligatorio'] ?></label>
													<select id="sltEspecialidadPersonaNuevo" class="form-control"
														name="especialidadP">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsEsp = llenaCombo($conn, 19, 9);
															while($esp = sqlsrv_fetch_array($rsEsp)){
																echo '<option value="'.$esp['id'].'">'.utf8_encode($esp['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['subspec_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['subspec_snr']['etiqueta'].$arrEtiquetas['subspec_snr']['obligatorio'] ?></label>
													<select id="sltSubEspecialidadPersonaNuevo" class="form-control" name="subespecialidadP">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsSubEsp = llenaCombo($conn, 19, 14);
															while($subEsp = sqlsrv_fetch_array($rsSubEsp)){
																echo '<option value="'.$subEsp['id'].'">'.utf8_encode($subEsp['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['patperweek_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['patperweek_snr']['etiqueta'].$arrEtiquetas['patperweek_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltPacientesXSemanaPersonaNuevo" name="pacientesXSemanaPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000">
															Seleccione
														</option>
<?php
															$rsSiNo = llenaCombo($conn, 19, 15);
															while($siNo = sqlsrv_fetch_array($rsSiNo)){
																echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['fee_type_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['fee_type_snr']['etiqueta'].$arrEtiquetas['fee_type_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltHonorariosPersonaNuevo" name="honorariosPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000">
															Seleccione
														</option>
<?php
															$rsSiNo = llenaCombo($conn, 19, 16);
															while($siNo = sqlsrv_fetch_array($rsSiNo)){
																echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>

											
											
											
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['frecvis_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['frecvis_snr']['etiqueta'].$arrEtiquetas['frecvis_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltFrecuenciaPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
																Seleccione</option>
<?php
														$rsFrec = llenaCombo($conn, 19, 13);
														while($frec = sqlsrv_fetch_array($rsFrec)){
															echo '<option value="'.$frec['id'].'">'.$frec['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['prof_id']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['prof_id']['etiqueta'].$arrEtiquetas['prof_id']['obligatorio'] ?></label>
													<input type="text" id="txtCedulaPersonaNuevo" value=""
														class="form-control" name="cedulaP" placeholder="Cédula"
														maxlength="8" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['sex_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['sex_snr']['etiqueta'].$arrEtiquetas['sex_snr']['obligatorio'] ?></label>
													<select id="sltSexoPersonaNuevo" class="form-control" name="sexoP">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSexo = llenaCombo($conn, 19, 10);
														while($regSexo = sqlsrv_fetch_array($rsSexo)){
															echo '<option value="'.$regSexo['id'].'">'.$regSexo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['category_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['category_snr']['etiqueta'].$arrEtiquetas['category_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltCategoriaPersonaNuevo" disabled>
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsCategoria = llenaCombo($conn, 19, 12);
														while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
															echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['status_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['status_snr']['etiqueta'].$arrEtiquetas['status_snr']['obligatorio'] ?></label>
													<select name="estatusP" class="form-control" id="sltEstatusPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSiNo = llenaCombo($conn, 19, 11);
														while($siNo = sqlsrv_fetch_array($rsSiNo)){
															echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['diffvis_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['diffvis_snr']['etiqueta'].$arrEtiquetas['diffvis_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltDificultadVisita">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsDif = llenaCombo($conn, 19, 17);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['birthdate']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['birthdate']['etiqueta'].$arrEtiquetas['birthdate']['obligatorio'] ?></label>
													<div class="fecha-slt-group" style="display:flex;">
														<input type="text" id="txtFechaNacimientoPersonaNuevo" value=""
															style="display:none;" />
														<select name="fechaNacDiaP" style="width:25%;"
															class="form-control" id="sltDiaFechaNacimientoPersonaNuevo"
															onChange="actualizaFechaCumple();">
															<option value="" hidden>dd</option>
<?php
															for($i=1;$i<32;$i++){
																echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.$i.'</option>';
															}
?>
														</select>
														<select style="width:45%;" class="form-control"
															id="sltMesFechaNacimientoPersonaNuevo"
															onChange="actualizaFechaCumple();" name="fechaNacMesP">
															<option value="" hidden>mm</option>
<?php
															for($i=1;$i<13;$i++){
																echo '<option value="'.str_pad($i, 2, "0", STR_PAD_LEFT).'">'.$meses[$i].'</option>';
															}
?>
														</select>
														<select style="width:30%;" class="form-control"
															id="sltAnioFechaNacimientoPersonaNuevo"
															onChange="actualizaFechaCumple();" name="fechaNacAñoP">
															<option value="" hidden>aaaa</option>
<?php
															for($i=1900;$i<date("Y");$i++){
																echo '<option value="'.$i.'">'.$i.'</option>';
															}
?>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['mobile']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['mobile']['etiqueta'].$arrEtiquetas['mobile']['obligatorio'] ?></label>
													<input name="celP" type="text" value="" id="txtCelularPersonaNuevo"
														class="form-control" placeholder="Celular" maxlength="10" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Rango edad</label>
													<select class="form-control" id="sltField01" >
														<option value="00000000-0000-0000-0000-000000000000">
															Seleccione
														</option>
<?php
														$rsField01 = llenaCombo($conn, 481, 3);
														while($field01 = sqlsrv_fetch_array($rsField01)){
															echo '<option value="'.$field01['id'].'">'.utf8_encode($field01['nombre']).'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Compra</label>
													<select class="form-control" id="sltField02" >
														<option value="00000000-0000-0000-0000-000000000000">
															Seleccione
														</option>
<?php
														$rsField02 = llenaCombo($conn, 481, 4);
														while($field02 = sqlsrv_fetch_array($rsField02)){
															echo '<option value="'.$field02['id'].'">'.utf8_encode($field02['nombre']).'</option>';
														}
?>
													</select>
												</div>
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label class="col-red">Médico Botiquín *</label>
													<select class="form-control" id="sltBotiquinPersonaNuevo" name="botiquinPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsCategoria = llenaCombo($conn, 19, 18);
														while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
															echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											
										</div>	
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['tel1']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['tel1']['etiqueta'].$arrEtiquetas['tel1']['obligatorio'] ?></label>
													<input name="tel1P" type="text" value=""
														id="txtTelPersonalPersonaNuevo" class="form-control"
														placeholder="Teléfono 1" maxlength="10" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['tel2']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['tel2']['etiqueta'].$arrEtiquetas['tel2']['obligatorio'] ?></label>
													<input name="tel2P" type="text" value=""
														id="txtTelPersonalPersonaNuevo2" class="form-control"
														placeholder="Teléfono 2" maxlength="10" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['email1']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['email1']['etiqueta'].$arrEtiquetas['email1']['obligatorio'] ?></label>
													<input name="email1PerfilP" type="text" value=""
														id="txtCorreoPersonalPersonaNuevo" class="form-control"
														placeholder="Correo 1" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['email2']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['email2']['etiqueta'].$arrEtiquetas['email2']['obligatorio'] ?></label>
													<input name="email2PerfilP" type="text" value=""
														id="txtCorreoPersonalPersonaNuevo2" class="form-control"
														placeholder="Correo 2" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['assistant_name']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['assistant_name']['etiqueta'].$arrEtiquetas['assistant_name']['obligatorio'] ?></label>
													<input type="text" value="" id="txtNombreAsistentePersonaNuevo"
														class="form-control" placeholder="Nombre del asistente"/>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['assistant_tel']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['assistant_tel']['etiqueta'].$arrEtiquetas['assistant_tel']['obligatorio'] ?></label>
													<input type="text" value=""
														id="txtTelAsistentePersonaNuevo" class="form-control"
														placeholder="Teléfono del asistente" maxlength="10" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['assistant_email']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['assistant_email']['etiqueta'].$arrEtiquetas['assistant_email']['obligatorio'] ?></label>
													<input name="email1PerfilP" type="text" value=""
														id="txtCorreoAsistentePersonaNuevo" class="form-control"
														placeholder="Correo del asistente" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['i_name']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['i_name']['etiqueta'].$arrEtiquetas['i_name']['obligatorio'] ?></label>
													<div class="input-group margin-0">
														<input type="text" value="" id="txtNombreInstPersonaNuevo"
															class="form-control no-style-disabled" size="30px"
															readonly="readonly" name="nombreInstPer"/>
														<span class="input-group-addon btn-indigo"
															style="padding: 0px; background-color: #3F51B5;">
															<input type="button" id="btnSleccionaInst" value="..."
																class="btn bg-indigo waves-effect no-shadow btn-indigo" />
															<input type="hidden" value="" id="hdnIdInstPersonaNuevo" />
														</span>
													</div>
													<label id="txtNombreInstPersonaNuevoError" class="error2"
														style="display:none;">Seleccione Institución</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['i_street1']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['i_street1']['etiqueta'].$arrEtiquetas['i_street1']['obligatorio'] ?></label>
													<input type="text" value="" id="txtCalleInstPersonaNuevo"
														class="form-control no-style-disabled" size="30px" readonly />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['plw_num_int']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['plw_num_int']['etiqueta'].$arrEtiquetas['plw_num_int']['obligatorio'] ?></label>
													<input id="txtNumIntInstPersonaNuevo" class="form-control"
														type="text" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['plw_department']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['plw_department']['etiqueta'].$arrEtiquetas['plw_department']['obligatorio'] ?></label>
													<input id="txtDepartamentoPersonaNuevo" class="form-control"
														type="text" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['plw_tower']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['plw_tower']['etiqueta'].$arrEtiquetas['plw_tower']['obligatorio'] ?></label>
													<input type="text" id="txtTorrePersonaNuevo" class="form-control"
														value="" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['plw_floor']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['plw_floor']['etiqueta'].$arrEtiquetas['plw_floor']['obligatorio'] ?></label>
													<input type="text" id="txtPisoPersonaNuevo" class="form-control"
														value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['plw_office']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['plw_office']['etiqueta'].$arrEtiquetas['plw_office']['obligatorio'] ?></label>
													<input type="text" id="txtConsultorioPersonaNuevo"
														class="form-control" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['c_zip']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['c_zip']['etiqueta'].$arrEtiquetas['c_zip']['obligatorio'] ?></label>
													<input type="text" size="10" id="txtCPInstPersonaNuevo"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['c_name']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['c_name']['etiqueta'].$arrEtiquetas['c_name']['obligatorio'] ?></label>
													<input type="text" id="txtColoniaInstPersonaNuevo" size="30px"
														class="form-control" value="" disabled />
												</div>
											</div>
											
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['d_name']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['d_name']['etiqueta'].$arrEtiquetas['d_name']['obligatorio'] ?></label>
													<input type="text" id="txtCiudadInstPersonaNuevo" size="30px"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['e_name']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['e_name']['etiqueta'].$arrEtiquetas['e_name']['obligatorio'] ?></label>
													<input type="text" id="txtEstadoInstPersonaNuevo" size="30px"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['b_name']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['b_name']['etiqueta'].$arrEtiquetas['b_name']['obligatorio'] ?></label>
													<input type="text" id="txtNombreBrickInstPersonaNuevo"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Brick</label>
													<input type="text" id="txtBrickInstPersonaNuevo"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['ailment_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['ailment_snr']['etiqueta'].$arrEtiquetas['ailment_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltPadecimientoMedicoPersonaNuevo" name="padecimientoMedicoPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSiNo = llenaCombo($conn, 19, 50);
														while($siNo = sqlsrv_fetch_array($rsSiNo)){
															echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['kol_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['kol_snr']['etiqueta'].$arrEtiquetas['kol_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltLiderOpinionPersonaNuevo" name="liderOpinionPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSiNo = llenaCombo($conn, 19, 32);
														while($siNo = sqlsrv_fetch_array($rsSiNo)){
															echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['marital_status_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['marital_status_snr']['etiqueta'].$arrEtiquetas['marital_status_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltEstadoCivilPersonaNuevo" name="estadoCivilPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSiNo = llenaCombo($conn, 19, 51);
														while($siNo = sqlsrv_fetch_array($rsSiNo)){
															echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['speaker_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['speaker_snr']['etiqueta'].$arrEtiquetas['speaker_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltPersonaSpeaker" name="personSpeaker">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSiNo = llenaCombo($conn, 19, 37);
														while($siNo = sqlsrv_fetch_array($rsSiNo)){
															echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label <?= $arrEtiquetas['brightly_snr']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['brightly_snr']['etiqueta'].$arrEtiquetas['brightly_snr']['obligatorio'] ?></label>
													<select class="form-control" id="sltPersonabrightly" name="personbrightly">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsSiNo = llenaCombo($conn, 19, 58);
														while($siNo = sqlsrv_fetch_array($rsSiNo)){
															echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Full nombre hospital</label>
													<input type="text" id="txtNombreHospitalPersonaNuevo"
														class="form-control" value="" placeholder="Full nombre hospital" />
												</div>
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Acepta apoyo</label>
													<select class="form-control" id="sltAceptaApoyoPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsSiNo = llenaCombo($conn, 19, 15);
															while($siNo = sqlsrv_fetch_array($rsSiNo)){
																echo '<option value="'.$siNo['id'].'">'.utf8_decode($siNo['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>¿Porqué?</label>
													<input type="text" id="txtPorqueAceptaApoyoPersonaNuevo"
														class="form-control" value="" placeholder="¿Porqué?" />
												</div>
											</div>
										</div>
										<div class="row" >
											
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label class="col-red">Compra directa *</label>
													<select class="form-control" id="sltCompraDirectaPersonaNuevo" name="compraDirectaPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsCategoria = llenaCombo($conn, 19, 17);
														while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
															echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Speaker</label>
													<select class="form-control" id="sltSpeakerPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsDif = llenaCombo($conn, 19, 20);
														while($dif = sqlsrv_fetch_array($rsDif)){
															echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label class="col-red">Tipo de Consulta *</label>
													<select class="form-control" id="sltTipoConsultaPersonaNuevo" name="tipoConsultaPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsTipoConsulta = llenaCombo($conn, 19, 21);
															while($regTipoConsulta = sqlsrv_fetch_array($rsTipoConsulta)){
																echo '<option value="'.$regTipoConsulta['id'].'">'.$regTipoConsulta['nombre'].'</option>';
															}
?>
													</select>
												</div>
											</div>
<?php
											if($tipoUsuario != 4){
?>
												<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 margin-0" hidden>
													<div class="form-group">
														<label class="col-red">Representante *</label>
														<select multiple id="sltReprePersonaNuevo"  name="native-select"  data-search="true" data-silent-initial-value-set="true" placeholder="Representante">
<?php
															$rsRepres = sqlsrv_query($conn, "select user_snr as id, user_nr + ' - ' + lname +' '+MOTHERS_LNAME+' '+FNAME as nombre from users where user_snr in ('".$ids."')
															and USER_TYPE in ('4')
															order by USER_NR,LNAME,MOTHERS_LNAME,FNAME");
															while($repre = sqlsrv_fetch_array($rsRepres)){
																echo '<option value="'.$repre['id'].'">'.utf8_encode($repre['nombre']).'</option>';
															}
?>
														</select>
													</div>
												</div>
<?php												
											}
											echo "<script>
											VirtualSelect.init({ 
												ele: '#sltReprePersonaNuevo' ,
												hideClearButton: false,
												dropboxWidth: '100%',
												maxWidth: '100%',
												searchPlaceholderText: 'Buscar...',
												allOptionsSelectedText: 'Todos',
												optionSelectedText: 'Opciones Seleccionadas',
												optionsSelectedText: 'Opciones Seleccionadas',
												
											  });
											
											</script>";
?>
										</div>
									</div>
								</div>

								<div id="tabs-2">

								</div>

								<div id="tabs-3">
									<div class="row p-t-20">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 font-15">
											<?php
											$rsPasatiempos = llenaCombo($conn, 19, 28);
											$contador = 1;
											while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
												if($contador == 1){
													echo "<div class='row'>";
												}
												echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:40px;">';

												echo '<input type="checkbox" class="filled-in chk-col-red" id="chkPasatiempoPersonaNuevo'.$contador.'" value="'.$pasatiempo['id'].'" />';

												echo '<label style="font-size:16px;" for="chkPasatiempoPersonaNuevo'.$contador.'" value="'.$pasatiempo['id'].'">'.$pasatiempo['nombre'].'</label>';
													
												echo '</div>';
												if($contador%3==0){
													echo "</div><div class='row'>";
												}
												$contador++;
											}
											if ($contador > 1) {
												echo "</div>";
											}
?>
										</div>
									</div>
									<input type="hidden" id="hdnPasatiempoPersonaNuevo" value="<?= $contador ?>" />
								</div>

								<div id="tabs-5">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<label <?= $arrEtiquetas['info_shorttime']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['info_shorttime']['etiqueta'].$arrEtiquetas['info_shorttime']['obligatorio'] ?></label>
											<textarea rows="8" id="txtCorto" class="text-notas2"></textarea>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<label <?= $arrEtiquetas['info_longtime']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['info_longtime']['etiqueta'].$arrEtiquetas['info_longtime']['obligatorio'] ?></label>
											<textarea rows="8" id="txtLargo" class="text-notas2"></textarea>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<label <?= $arrEtiquetas['info']['obligatorio'] == ' *' ? 'class="col-red"' : '' ?>><?= $arrEtiquetas['info']['etiqueta'].$arrEtiquetas['info']['obligatorio'] ?></label>
											<textarea rows="8" id="txtGenerales" class="text-notas2"></textarea>
										</div>
									</div>
								</div>
							</form>
							<div id="divBusqueda" style="display:none;">
								<div class="row ">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-left">
										<button type="button"
											class="btn btn-default waves-effect btn-r-20 m-l-3 m-t-3 btn-indigo2"
											id="btnCerrarBuscarInst">
											<i class="material-icons">chevron_left</i>
											<span>Regresar</span>
										</button>
										<!--<input type="button" id="btnCerrarBuscarInst" value="Cerrar" />-->
									</div>
								</div>
								<div class="row ">
									<div class="col-lg-9 col-md-9 col-sm-8 col-xs-8 margin-0">
										<div class="form-group">
											<div class="input-group">
												<input id="txtBusqueda" type="text" size="100"
													class="form-control"
													placeholder="Buscar por nombre o dirección...">
												<span class="input-group-addon padding-0">
													<button id="btnBuscarInstMed"
														class="btn waves-effect btn-indigo2"
														style="padding: 3px 12px;">
														<i class="glyphicon glyphicon-search"></i>
													</button>
												</span>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 align-right">
										<button type="button" class="btn bg-indigo waves-effect btn-indigo"
											id="imgAgregarInstPersona">
											<i class="material-icons">add_circle_outline</i>
											<span>Agregar</span>
										</button>

										<!--<img src="iconos/agregar.png" title="Agregar" id="imgAgregarInstPersona" class="imgBoton"/>-->
									</div>
								</div>
								<div class="div-tbl-aprob-pers">
									<table id="tblBuscarInst" class="table-striped table-hover">
										<thead class="bg-cyan">
											<tr>
<?php
												if($tipoUsuario != 4){
?>
													<td style="width:8%;">Ruta</td>
													<td style="width:11%;">Tipo</td>
													<td style="width:15%;">Nombre</td>
													<td style="width:15%;">Dirección</td>
													<td style="width:15%;">Colonia</td>
													<td style="width:8%;">CP</td>
													<td style="width:15%;">Población</td>
													<td style="width:13%;">Estado</td>
<?php
												}else{
?>
													<td style="width:12%;">Tipo</td>
													<td style="width:16%;">Nombre</td>
													<td style="width:16%;">Dirección</td>
													<td style="width:16%;">Colonia</td>
													<td style="width:9%;">CP</td>
													<td style="width:16%;">Población</td>
													<td style="width:15%;">Estado</td>
<?php
												}				
?>
											</tr>
										</thead>
										<tbody class="pointer" style="height:350px !important;">
										</tbody>
									</table>
								</div>
								<div id="totalInstMedBuscados" class="font-bold"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$( "#btnCargaDatosPersona" ).click(function() {
		$( "#divCargaDatosPersona" ).show();
		$('#ModalCargaDatosPersona').modal('show');

		$('#tblCargaDatosPersona tbody').empty();
			var inputfileCarga = document.getElementById("formFile");
			inputfileCarga.value = '';	
	});

	$("#btnGuardaCargaPersonas").click(function() {

		var inputfileCarga = document.getElementById("formFile");
		var valida= $("#hdnTextValidaCargaPersona").val();

		if(inputfileCarga.value==""){
			alertSiegfriedCargaDatos('Sin Archivo', 'debe adjuntar un archivo', 'info',5000,true,false)
		}else if($("#tblCargaDatosPersona").find('tbody tr').length==0){
			alertSiegfriedCargaDatos('Sin Registros', 'La tabla no Contiene Registros', 'info',5000,true,false)
		}else if(valida>0){
			alertSiegfriedCargaDatos('Errores', 'Asegurese de que todos los registros esten correctos', 'error',10000,true,false)
		}else{
   			var datos= $("#hdnTextDatosCargaPersona").val();
   			$("#divRespuesta").load("ajax/guardaCargaPersona.php",{datos:datos,valida:valida});
		}
	});

	$("#formFile").change(function(){
		$("#btnValidaCargaPersona").click();
    });

</script>