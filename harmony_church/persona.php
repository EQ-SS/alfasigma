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
								<a href="#tabs-3" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">account_balance</i>
									<div class="divTooltip">Bancos/Aseguradoras</div>
									<span class="hidden-txt-tabs">Bancos/Aseguradoras</span>
								</a>
							</li>
							<!--<li id="tabPer3" role="presentation">
								<a href="#tabs-6" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">insert_chart</i>
									<div class="divTooltip">W Analysis</div>
									<span class="hidden-txt-tabs">What Analysis</span>
								</a>
							</li>
							<li id="tabPer4" role="presentation">
								<a href="#tabs-7" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">assignment</i>
									<div class="divTooltip">Segmentación</div>
									<span class="hidden-txt-tabs">Segmentación</span>
								</a>
							</li>-->
							<li id="tabPer5" role="presentation" style="display:none;">
								<a href="#tabs-2" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">place</i>
									<div class="divTooltip">Dir. trabajo</div>
									<span class="hidden-txt-tabs">Dirección de trabajo</span>
								</a>
							</li>
							<!--<li id="tabPer6" role="presentation">
								<a href="#tabs-4" data-toggle="tab" class="show-tooltip">
									<i class="material-icons">access_time</i>
									<div class="divTooltip">Horario</div>
									<span class="hidden-txt-tabs">Horario de trabajo</span>
								</a>
							</li>-->
							<li id="tabPer7" role="presentation">
								<a href="#tabs-5" data-toggle="tab" class="show-tooltip">
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
													<label class="col-red">Tipo</label>
													<select id="sltTipoPersonaNuevo" class="form-control"
														name="tipoPersona">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
														$rsTipoPersona = llenaCombo($conn, "19", "73");
														while($tipo = sqlsrv_fetch_array($rsTipoPersona)){
															echo '<option value="'.$tipo['id'].'">'.utf8_encode($tipo['nombre']).'</option>';
														}
													?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Apellido Paterno *</label>
													<input type="text" value="" id="txtPaternoPersonaNuevo"
														class="form-control" name="apellidoP"
														placeholder="Apellido Paterno" required />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Apellido Materno</label>
													<input name="apellidoM" type="text" value=""
														id="txtMaternoPersonaNuevo" class="form-control"
														placeholder="Apellido Materno" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Nombre(s) *</label>
													<input type="text" value="" id="txtNombrePersonaNuevo"
														class="form-control" name="nombreP" placeholder="Nombre"
														required />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Especialidad *</label>
													<select id="sltEspecialidadPersonaNuevo" class="form-control"
														name="especialidadP">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsEsp = llenaCombo($conn, 19, 1);
												while($esp = sqlsrv_fetch_array($rsEsp)){
													echo '<option value="'.$esp['id'].'">'.utf8_encode($esp['nombre']).'</option>';
												}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Subespecialidad</label>
													<select id="sltSubEspecialidadPersonaNuevo" class="form-control" name="subespecialidadP">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsSubEsp = llenaCombo($conn, 19, 51);
															while($subEsp = sqlsrv_fetch_array($rsSubEsp)){
																echo '<option value="'.$subEsp['id'].'">'.utf8_encode($subEsp['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Pacientes por semana *</label>
													<select name="pacientesSemanaP" id="sltPacientesXSemanaPersonaNuevo"
														class="form-control">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsPacientes = llenaCombo($conn, 19, 389);
												while($paciente = sqlsrv_fetch_array($rsPacientes)){
													echo '<option value="'.$paciente['id'].'">'.$paciente['nombre'].'</option>';
												}
					?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Honorarios *</label>
													<select name="honorariosP" id="sltHonorariosPersonaNuevo"
														class="form-control">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsHonorarios = llenaCombo($conn, 19, 387);
												while($honorario = sqlsrv_fetch_array($rsHonorarios)){
													echo '<option value="'.$honorario['id'].'">'.$honorario['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Frecuencia de la visita</label>
													<select class="form-control" id="sltFrecuenciaPersonaNuevo" <?=($tipoUsuario==4) ? "enabled" : "" ?>>
														<option value="00000000-0000-0000-0000-000000000000" hidden>
																Seleccione</option>
								<?php
											$rsFrec = llenaCombo($conn, 19, 2);
											while($frec = sqlsrv_fetch_array($rsFrec)){
												echo '<option value="'.$frec['id'].'">'.$frec['nombre'].'</option>';
											}
										?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Cédula *</label>
													<input type="text" id="txtCedulaPersonaNuevo" value=""
														class="form-control" name="cedulaP" placeholder="Cédula"
														maxlength="11" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Sexo</label>
													<select id="sltSexoPersonaNuevo" class="form-control" name="sexoP">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsSexo = llenaCombo($conn, 19, 6);
												while($regSexo = sqlsrv_fetch_array($rsSexo)){
													echo '<option value="'.$regSexo['id'].'">'.$regSexo['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Categoría</label>
													<select class="form-control" id="sltCategoriaPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsCategoria = llenaCombo($conn, 19, 25);
												while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
													echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Folio audit</label>
													<input name="folioAuditP" type="text" value="" id="txtFolioAuditPersonaNuevo"
														class="form-control" placeholder="Folio audit" readonly />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Estatus</label>
													<select name="estatusP" class="form-control"
														id="sltEstatusPersonaNuevo" disabled>
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
													<label class="col-red">Fecha de nacimiento *</label>
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
													<label class="col-red">Edad *</label>
													<input type="text" class="form-control" id="txtEdad" name="edadPersona" readonly />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Dificultad de la visita</label>
													<select class="form-control" id="sltDificultadVisita" disabled>
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
						<?php
															$rsDif = llenaCombo($conn, 19, 391);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Celular *</label>
													<input name="celP" type="text" value="" id="txtCelularPersonaNuevo"
														class="form-control" placeholder="Celular" maxlength="10" />
												</div>
											</div>
										</div>	
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Tel1 *</label>
													<input name="tel1P" type="text" value=""
														id="txtTelPersonalPersonaNuevo" class="form-control"
														placeholder="Teléfono 1" maxlength="10" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Tel2</label>
													<input name="tel2P" type="text" value=""
														id="txtTelPersonalPersonaNuevo2" class="form-control"
														placeholder="Teléfono 2" maxlength="10" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Email1 *</label>
													<input name="email1PerfilP" type="text" value=""
														id="txtCorreoPersonalPersonaNuevo" class="form-control"
														placeholder="Correo 1" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Email2</label>
													<input name="email2PerfilP" type="text" value=""
														id="txtCorreoPersonalPersonaNuevo2" class="form-control"
														placeholder="Correo 2" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Prescriptor cat. lub. nasales</label>
													<select class="form-control" id="sltField01">
						<?php
															$rsDif = llenaCombo($conn, 481, 1);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Líder de opinión *</label>
													<select class="form-control" id="sltField02" name="field02" >
						<?php
															$rsDif = llenaCombo($conn, 481, 2);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Segmento X potencial *</label>
													<select class="form-control" id="sltField03" name="field03">
						<?php
															$rsDif = llenaCombo($conn, 481, 3);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Rango de edad</label>
													<select class="form-control" id="sltField04" >
						<?php
															$rsDif = llenaCombo($conn, 481, 4);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
						?>
													</select>
												</div>
											</div>
										</div>
										<div class="row" hidden>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<input type="text" id="txtNumExtInstPersonaNuevo" value="" disabled
														style="display:none;" />
													<label>Departamento</label>
													<input id="txtDepartamentoPersonaNuevo" class="form-control"
														type="text" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Torre </label>
													<input type="text" id="txtTorrePersonaNuevo" class="form-control"
														value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Piso</label>
													<input type="text" id="txtPisoPersonaNuevo" class="form-control"
														value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Consultorio No.</label>
													<input type="text" id="txtConsultorioPersonaNuevo"
														class="form-control" value="" />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Nombre de la Institución *</label>
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
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Calle</label>
													<input type="text" value="" id="txtCalleInstPersonaNuevo"
														class="form-control no-style-disabled" size="30px" readonly />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Num. Interior </label>
													<input id="txtNumIntInstPersonaNuevo" class="form-control"
														type="text" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>C.P.</label>
													<input type="text" size="10" id="txtCPInstPersonaNuevo"
														class="form-control" value="" disabled />
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Colonia</label>
													<input type="text" id="txtColoniaInstPersonaNuevo" size="30px"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Ciudad</label>
													<input type="text" id="txtCiudadInstPersonaNuevo" size="30px"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Estado</label>
													<input type="text" id="txtEstadoInstPersonaNuevo" size="30px"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Brick</label>
													<input type="text" id="txtBrickInstPersonaNuevo"
														class="form-control" value="" disabled />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Médico ketos</label>
													<select class="form-control" id="sltField01" name="field01">
														<option value="00000000-0000-0000-0000-000000000000">
															Seleccione
														</option>
<?php
															$rsSiNo = llenaCombo($conn, 481, 1);
															while($siNo = sqlsrv_fetch_array($rsSiNo)){
																echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Prueba PCR</label>
													<select class="form-control" id="sltField02" name="field02">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsSiNo = llenaCombo($conn, 481, 2);
															while($siNo = sqlsrv_fetch_array($rsSiNo)){
																echo '<option value="'.$siNo['id'].'">'.utf8_decode($siNo['nombre']).'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label class="col-red">Segmento x Potencial *</label>
													<select class="form-control" id="sltField03" name="field03">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsDif = llenaCombo($conn, 481, 3);
												while($dif = sqlsrv_fetch_array($rsDif)){
													echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label class="col-red">Líder de Opinión *</label>
													<select class="form-control" id="sltField04" name="field04">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsSiNo = llenaCombo($conn, 481, 4);
												while($siNo = sqlsrv_fetch_array($rsSiNo)){
													echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
											
										</div>
										<div class="row" hidden>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Recetario Libre *</label>
													<select class="form-control" id="sltField05" name="field05">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsDif = llenaCombo($conn, 481, 5);
														while($dif = sqlsrv_fetch_array($rsDif)){
															echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label class="col-red">Establecimiento Personal *</label>
													<input type="text" id="txtField01" name="txtField01"
														class="form-control" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Decil Audit</label>
													<select class="form-control" id="sltField07" name="field07">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															$rsDif = llenaCombo($conn, 481, 7);
															while($dif = sqlsrv_fetch_array($rsDif)){
																echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
															}
?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Médicos Columbia</label>
													<input type="text" id="txtField03" name="txtField03"
														class="form-control" value="" />
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Iguala</label>
													<select class="form-control" id="sltField13">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsDif = llenaCombo($conn, 19, 14);
												while($dif = sqlsrv_fetch_array($rsDif)){
													echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Tipo Iguala</label>
													<select class="form-control" id="sltField15">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsSiNo = llenaCombo($conn, 19, 17);
												while($siNo = sqlsrv_fetch_array($rsSiNo)){
													echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
												}
					?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Contacto Virtual</label>
													<select class="form-control" id="sltField12" disabled>
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsSiNo = llenaCombo($conn, 19, 621);
												while($siNo = sqlsrv_fetch_array($rsSiNo)){
													echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
												}
					?>
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0" hidden>
												<div class="form-group">
													<label>Botiquín</label>
													<select class="form-control" id="sltField14">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
														$rsCategoria = llenaCombo($conn, 19, 15);
														while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
															echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
														}
?>
													</select>
												</div>
											</div>
										</div>
										<div class="row" hidden>
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Vitamedica</label>
													<select class="form-control" id="sltField09">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
														<?php
												$rsDif = llenaCombo($conn, 19, 9);
												while($dif = sqlsrv_fetch_array($rsDif)){
													echo '<option value="'.$dif['id'].'">'.$dif['nombre'].'</option>';
												}
						?>
													</select>
												</div>
											</div>
											
										</div>
										<div class="row" hidden>
											
											<!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Conexión</label>
													<select class="form-control" id="sltField10">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															/*$rsCategoria = llenaCombo($conn, 19, 7);
															while($regCategoria = sqlsrv_fetch_array($rsCategoria)){
																echo '<option value="'.$regCategoria['id'].'">'.$regCategoria['nombre'].'</option>';
															}*/
?>
													</select>
												</div>
											</div>-->
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
												<label>Conexión</label>
												<form>
													<div class="multiselect">
														<div class="selectBox" onclick="showCheckboxesConexion()">
															<select class="form-control">
																<option id="sltMultiSelectConexion">Selecciona</option>
															</select>
															<div class="overSelect"></div>
														</div>
														<div id="checkboxesConexion" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
														<?php
															$rsConexion = llenaCombo($conn, 19, 7);
															$contadorChecksConexion = 0;
															$idChecks = '';
															$descripcionesCheckConexion = '';
															while($conexion = sqlsrv_fetch_array($rsConexion)){
																$contadorChecksConexion++;
																$idChk = "conexion".$contadorChecksConexion;
																echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesConexion(\''.utf8_encode($conexion['nombre']).'\',\''.$idChk.'\');" value="'.$conexion['id'].'"/>';
																echo '<label for="'.$idChk.'">'.utf8_encode($conexion['nombre']).'</label><br>';
															}
														?>
														</div>
													</div>
													<input type="hidden" id="hdnTotalChecksConexion"
														value="<?= $contadorChecksConexion ?>">
													<input type="hidden" id="hdnDescripcionChkConexion"
														value="<?= ($descripcionesCheckConexion == '') ? "Seleccione" : $descripcionesCheckConexion ?>" />
												</form>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
												<label>Tipo de Consulta</label>
													<div class="multiselect">
														<div class="selectBox" onclick="showCheckboxesTipoConsulta();">
															<select class="form-control">
																<option id="sltMultiSelectTipoConsulta">Selecciona</option>
															</select>
															<div class="overSelect"></div>
														</div>
														<div id="checkboxesTipoConsulta" style="display:none;border: 1px #dadada solid;height: 87px;overflow: auto;padding: 5px;background-color: #ffffff;">
														<?php
															$rsTipoConsulta = llenaCombo($conn, 19, 622);
															$contadorChecksTipoConsulta = 0;
															$idChecksTipoConsulta = '';
															$descripcionesCheckTipoConsulta = '';
															while($tipoConsulta = sqlsrv_fetch_array($rsTipoConsulta)){
																$contadorChecksTipoConsulta++;
																$idChk = "tipoConsulta".$contadorChecksTipoConsulta;
																echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesTipoConsulta(\''.utf8_encode($tipoConsulta['nombre']).'\',\''.$idChk.'\');" value="'.$tipoConsulta['id'].'"/>';
																echo '<label for="'.$idChk.'">'.utf8_encode($tipoConsulta['nombre']).'</label><br>';
															}
														?>
														</div>
													</div>
													<input type="hidden" id="hdnTotalChecksTipoConsulta"
														value="<?= $contadorChecksTipoConsulta ?>">
													<input type="hidden" id="hdnDescripcionChkTipoConsulta"
														value="<?= ($descripcionesCheckTipoConsulta == '') ? "Seleccione" : $descripcionesCheckTipoConsulta ?>" />
											
											</div>
											<!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margin-0">
												<div class="form-group">
													<label>Tipo de Consulta</label>
													<select class="form-control" id="sltTipoConsultaPersonaNuevo">
														<option value="00000000-0000-0000-0000-000000000000" hidden>
															Seleccione</option>
<?php
															/*$rsTipoConsulta = llenaCombo($conn, 19, 622);
															while($regTipoConsulta = sqlsrv_fetch_array($rsTipoConsulta)){
																echo '<option value="'.$regTipoConsulta['id'].'">'.$regTipoConsulta['nombre'].'</option>';
															}*/
?>
													</select>
												</div>
											</div>-->
										</div>
									</div>
								</div>

								<div id="tabs-2">

								</div>

								<div id="tabs-3">
									<div class="row p-t-20">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 font-15">
											<?php
											$rsPasatiempos = llenaCombo($conn, 25, 1);
											$contador = 1;
											while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
												if($contador == 1){
													echo "<div class='row'>";
												}
												echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:40px;">';

												echo '<input type="checkbox" class="filled-in chk-col-red" id="chkPasatiempoPersonaNuevo'.$contador.'" value="'.$pasatiempo['id'].'" />';

												echo '<label style="font-size:16px;" for="chkPasatiempoPersonaNuevo'.$contador.'" value="'.$pasatiempo['id'].'">'.$pasatiempo['nombre'].'</label>';
												
												/*if($contador ==2 || $contador ==5 || $contador ==8 || $contador ==9){
													echo '<label for="chkPasatiempoPersonaNuevo'.$contador.'" value="'.$pasatiempo['id'].'">'.$pasatiempo['nombre'].'</label>';
												}
												else{
													echo "<label for='chkPasatiempoPersonaNuevo".$contador."' value='".$pasatiempo['id']."'>
													<div style='width:80px; height:50px;'><img style='width:100%; height:100%; margin-top:-14px;' src='images/logos_bancos_seguros/chkBancosPersonaDatosPersonales".$contador.".png'></div></label>";
												}*/
													
												echo '</div>';
												if($contador%3==0){
													echo "</div><div class='row'>";
												}
												$contador++;
											}
											echo "</div>";
										?>
										</div>
									</div>
									<input type="hidden" id="hdnPasatiempoPersonaNuevo" value="<?= $contador ?>" />
								</div>

								<!--<div id="tabs-4">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive align-center">
												<table id="tblHorario" class="table">
													<tr>
														<td>&nbsp;</td>
														<td class="font-bold align-center">AM</td>
														<td class="font-bold align-center">PM</td>
														<td class="font-bold align-center">Previa cita</td>
														<td class="font-bold align-center">Horario Fijo</td>
													</tr>
													<tr>
														<td class="font-bold">Lunes</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkLunesAm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkLunesPm"></label>

														</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkLunesPrevia"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkLunesFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkLunesFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtLunesComentarios" value="" /></td>
													</tr>
													<tr>
														<td class="font-bold">Martes</td>
														<td class="align-center">
															<input type="checkbox" id="chkMartesAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMartesAm"></label>
														</td>
														<td class="align-center">
															<input type="checkbox" id="chkMartesPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMartesPm"></label>
														</td>
														<td align="center">
															<input type="checkbox" id="chkMartesPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMartesPrevia"></label>
														</td>
														<td align="center">
															<input type="checkbox" id="chkMartesFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMartesFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtMartesComentarios" value="" /></td>
													</tr>
													<tr>
														<td class="font-bold">Miercoles</td>
														<td class="align-center"><input type="checkbox"
																id="chkMiercolesAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMiercolesAm"></label>
														</td>
														<td class="align-center"><input type="checkbox"
																id="chkMiercolesPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMiercolesPm"></label>
														</td>
														<td align="center"><input type="checkbox"
																id="chkMiercolesPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMiercolesPrevia"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkMiercolesFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkMiercolesFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtMiercolesComentarios" value="" /></td>
													</tr>
													<tr>
														<td class="font-bold">Jueves</td>
														<td class="align-center"><input type="checkbox" id="chkJuevesAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkJuevesAm"></label>
														</td>
														<td class="align-center"><input type="checkbox" id="chkJuevesPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkJuevesPm"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkJuevesPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkJuevesPrevia"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkJuevesFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkJuevesFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtJuevesComentarios" value="" /></td>
													</tr>
													<tr>
														<td class="font-bold">Viernes</td>
														<td class="align-center"><input type="checkbox"
																id="chkViernesAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkViernesAm"></label>
														</td>
														<td class="align-center"><input type="checkbox"
																id="chkViernesPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkViernesPm"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkViernesPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkViernesPrevia"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkViernesFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkViernesFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtViernesComentarios" value="" /></td>
													</tr>
													<tr>
														<td class="font-bold">Sábado</td>
														<td class="align-center"><input type="checkbox" id="chkSabadoAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkSabadoAm"></label>
														</td>
														<td class="align-center"><input type="checkbox" id="chkSabadoPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkSabadoPm"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkSabadoPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkSabadoPrevia"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkSabadoFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkSabadoFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtSabadoComentarios" value="" /></td>
													</tr>
													<tr>
														<td class="font-bold">Domingo</td>
														<td class="align-center"><input type="checkbox"
																id="chkDomingoAm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkDomingoAm"></label>
														</td>
														<td class="align-center"><input type="checkbox"
																id="chkDomingoPm"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkDomingoPm"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkDomingoPrevia"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkDomingoPrevia"></label>
														</td>
														<td align="center"><input type="checkbox" id="chkDomingoFijo"
																class="filled-in chk-col-red p-l-15" />
															<label for="chkDomingoFijo"></label>
														</td>
														<td class="align-center"><input class="form-control" type="text"
																id="txtDomingoComentarios" value="" /></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>-->

								<div id="tabs-5">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="font-bold">Objetivos a corto plazo:</p>
											<textarea rows="8" id="txtCorto" class="text-notas2"></textarea>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="font-bold">Objetivos a largo plazo:</p>
											<textarea rows="8" id="txtLargo" class="text-notas2"></textarea>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<p class="font-bold">Comentarios Generales:</p>
											<textarea rows="8" id="txtGenerales" class="text-notas2"></textarea>
										</div>
									</div>
								</div>

								<!--<div id="tabs-6">
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Producto 1</label>
												<select class="form-control" id="sltProducto1PersonaNuevo">
													<option value="00000000-0000-0000-0000-000000000000">Seleccione
													</option>
													<?php
													/*$rsSiNo = llenaCombo($conn, 481, 1001);
													while($siNo = sqlsrv_fetch_array($rsSiNo)){
														echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
													}*/
						?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>W (qué producto)</label>
												<input type="text" id="txtWProducto1" value="" class="form-control"
													placeholder="¿Qué producto?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>H (cuánto)</label>
												<input type="text" id="txtHProducto1" value="" class="form-control"
													placeholder="¿Cuánto?" />
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>A (por qué)</label>
												<input type="text" id="txtAProducto1" value="" class="form-control"
													placeholder="¿Por qué?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>T (qué haré)</label>
												<input type="text" id="txtTProducto1" value="" class="form-control"
													placeholder="¿Qué voy a hacer?" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Producto 2</label>
												<select class="form-control" id="sltProducto2PersonaNuevo">
													<option value="00000000-0000-0000-0000-000000000000">Seleccione
													</option>
													<?php
													/*$rsSiNo = llenaCombo($conn, 481, 1002);
													while($siNo = sqlsrv_fetch_array($rsSiNo)){
														echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
													}*/
						?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>W (qué producto)</label>
												<input type="text" id="txtWProducto2" value="" class="form-control"
													placeholder="¿Qué producto?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>H (cuánto)</label>
												<input type="text" id="txtHProducto2" value="" class="form-control"
													placeholder="¿Cuánto?" />
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>A (por qué)</label>
												<input type="text" id="txtAProducto2" value="" class="form-control"
													placeholder="¿Por qué?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>T (qué haré)</label>
												<input type="text" id="txtTProducto2" value="" class="form-control"
													placeholder="¿Qué voy a hacer?" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Producto 3</label>
												<select class="form-control" id="sltProducto3PersonaNuevo">
													<option value="00000000-0000-0000-0000-000000000000" hidden>
														Seleccione</option>
													<?php
												/*$rsSiNo = llenaCombo($conn, 481, 1003);
												while($siNo = sqlsrv_fetch_array($rsSiNo)){
													echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
												}*/
					?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>W (qué producto)</label>
												<input type="text" id="txtWProducto3" value="" class="form-control"
													placeholder="¿Qué producto?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>H (cuánto)</label>
												<input type="text" id="txtHProducto3" value="" class="form-control"
													placeholder="¿Cuánto?" />
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>A (por qué)</label>
												<input type="text" id="txtAProducto3" value="" class="form-control"
													placeholder="¿Por qué?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>T (qué haré)</label>
												<input type="text" id="txtTProducto3" value="" class="form-control"
													placeholder="¿Qué voy a hacer?" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Producto 4</label>
												<select class="form-control" id="sltProducto4PersonaNuevo">
													<option value="00000000-0000-0000-0000-000000000000" hidden>
														Seleccione</option>
													<?php
												/*$rsSiNo = llenaCombo($conn, 481, 1004);
												while($siNo = sqlsrv_fetch_array($rsSiNo)){
													echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
												}*/
					?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>W (qué producto)</label>
												<input type="text" id="txtWProducto4" value="" class="form-control"
													placeholder="¿Qué producto?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>H (cuánto)</label>
												<input type="text" id="txtHProducto4" value="" class="form-control"
													placeholder="¿Cuánto?" />
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>A (por qué)</label>
												<input type="text" id="txtAProducto4" value="" class="form-control"
													placeholder="¿Por qué?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>T (qué haré)</label>
												<input type="text" id="txtTProducto4" value="" class="form-control"
													placeholder="¿Qué voy a hacer?" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>Producto 5</label>
												<select class="form-control" id="sltProducto5PersonaNuevo">
													<option value="00000000-0000-0000-0000-000000000000" hidden>
														Seleccione</option>
													<?php
												/*$rsSiNo = llenaCombo($conn, 481, 1005);
												while($siNo = sqlsrv_fetch_array($rsSiNo)){
													echo '<option value="'.$siNo['id'].'">'.$siNo['nombre'].'</option>';
												}*/
					?>
												</select>
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>W (qué producto)</label>
												<input type="text" id="txtWProducto5" value="" class="form-control"
													placeholder="¿Qué producto?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>H (cuánto)</label>
												<input type="text" id="txtHProducto5" value="" class="form-control"
													placeholder="¿Cuánto?" />
											</div>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>A (por qué)</label>
												<input type="text" id="txtAProducto5" value="" class="form-control"
													placeholder="¿Por qué?" />
											</div>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>T (qué haré)</label>
												<input type="text" id="txtTProducto5" value="" class="form-control"
													placeholder="¿Qué voy a hacer?" />
											</div>
										</div>
									</div>
								</div>

								<div id="tabs-7">
									<div class="row">
										<div
											class="col-lg-6 col-md-6 col-sm-6 col-xs-6 font-bold col-indigo align-left">
											Preguntas
										</div>
										<div
											class="col-lg-6 col-md-6 col-sm-6 col-xs-6 font-bold col-indigo align-right">
											Respuestas
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 p-t-11">
											<span class="font-bold">GANAR </span> (¿Porque no le gusta mi producto?)
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<select class="form-control" id="sltPregunta1PersonaNuevo">
												<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
												<?php
													/*$rsSiNo = llenaCombo($conn, 481, 8);
													while($siNo = sqlsrv_fetch_array($rsSiNo)){
														echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
													}*/
?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 p-t-11">
											<span class="font-bold">DESARROLLAR </span> (¿Que le hace falta para
											incrementar su Rx?)
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<select class="form-control" id="sltPregunta2PersonaNuevo">
												<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
												<?php
													/*$rsSiNo = llenaCombo($conn, 481, 9);
													while($siNo = sqlsrv_fetch_array($rsSiNo)){
														echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
													}*/
?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 p-t-11">
											<span class="font-bold">DEFENDER </span> (¿Que es lo que más le gusta del
											producto?)
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<select class="form-control" id="sltPregunta3PersonaNuevo">
												<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
												<?php
													/*$rsSiNo = llenaCombo($conn, 481, 10);
													while($siNo = sqlsrv_fetch_array($rsSiNo)){
														echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
													}*/
?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 p-t-11">
											<span class="font-bold">EVALUAR </span> (¿Porque no le gusta mi producto?)
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<select class="form-control" id="sltPregunta4PersonaNuevo">
												<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
												<?php
													/*$rsSiNo = llenaCombo($conn, 481, 11);
													while($siNo = sqlsrv_fetch_array($rsSiNo)){
														echo '<option value="'.$siNo['id'].'">'.utf8_encode($siNo['nombre']).'</option>';
													}*/
?>
											</select>
										</div>
									</div>
								</div>-->
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
													echo '<td style="width:8%;">Ruta</td>
													<td style="width:11%;">Tipo</td>
													<td style="width:15%;">Nombre</td>
													<td style="width:15%;">Dirección</td>
													<td style="width:15%;">Colonia</td>
													<td style="width:8%;">CP</td>
													<td style="width:15%;">Población</td>
													<td style="width:13%;">Estado</td>';
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
<?php							
											$instituciones = "select i.inst_snr, it.name as tipo, i.name as nombre, i.STREET1 as calle, 
												city.name as colonia, d.NAME as delegacion, state.NAME as estado, city.zip as cp 
												from inst i 
												inner join USER_TERRIT ut on i.INST_SNR = ut.INST_SNR 
												inner join INST_TYPE it on it.INST_TYPE = i.INST_TYPE 
												inner join city on city.CITY_SNR = i.CITY_SNR 
												inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR 
												inner join STATE on state.STATE_SNR = city.STATE_SNR 
												inner join BRICK bri on bri.brick_snr = city.brick_snr 
												where user_snr = '".$idUsuario."' 
												and i.rec_stat = 0
												and ut.REC_STAT = 0
												order by nombre ";
												
												//echo $instituciones."<br>";
				
												$rsInst = sqlsrv_query($conn, $instituciones);
												while($inst = sqlsrv_fetch_array($rsInst)){
													echo "<tr onClick=\"instSeleccionada('".$inst['inst_snr']."');\">
														<td align=\"left\">".$inst['tipo']."</td>
														<td align=\"left\">".$inst['nombre']."</td>
														<td align=\"left\">".$inst['calle']."</td>
														<td align=\"left\">".$inst['colonia']."</td>
														<td align=\"left\">".$inst['cp']."</td>
														<td align=\"left\">".$inst['delegacion']."</td>
														<td align=\"left\">".$inst['estado']."</td>
													</tr>";
												}
?>
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