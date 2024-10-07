<input type="hidden" id="hdnIdVisita" value="" />
<input type="hidden" id="hdnPantallaVisitas" value="" />


<script src="plugins/signature-pad-master/assets/numeric-1.2.6.min.js"></script> 
	<script src="plugins/signature-pad-master/assets/bezier.js"></script>


<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Visitas
					</h2>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-10 col-xs-11 align-center m-t-10 m-b-10 display-inline">
					<button id="btnEliminarVisitaPerson" type="button" class="btn bg-indigo waves-effect btn-indigo" <?=($tipoUsuario
					 !=2) ? "disabled" : "" ?>>
						Borrar
					</button>
					<button id="btnSiguienteVisita" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Planear Siguiente Visita
					</button>
					<!--<button id="btnEncuesta" type="button"  class="btn bg-indigo waves-effect btn-indigo m-l-10">
							Encuesta
						</button>-->
					<button id="btnGuardarVisitas" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-1 align-right m-t-10">
					<p id="btnCancelarVisitas" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div id="tabsVisitas">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--20">
						<ul class="nav nav-tabs m-l--35 m-r--35 tab-col-blue p-l-5" role="tablist"
							style="background-color:#efefef;">
							<li role="presentation" class="active">
								<a id="tabVisitaPersona" href="#tabVisita" data-toggle="tab"
									class="show-tooltip p-t-14">
									<i class="fas fa-handshake top-0 p-b-3"></i>
									<div class="divTooltip">Visita</div>
									<span class="hidden-txt-tabs">Visita</span>
								</a>
							</li>
							<li role="presentation">
								<a href="#tabProductos" data-toggle="tab" class="show-tooltip p-t-14">
									<i class="fas fa-capsules top-0 p-b-3"></i>
									<div class="divTooltip">Productos</div>
									<span class="hidden-txt-tabs">Productos</span>
								</a>
							</li>
							<li role="presentation">
								<a href="#tabMuestras" data-toggle="tab" class="show-tooltip p-t-14">
									<i class="fas fa-vial top-0 p-b-3"></i>
									<div class="divTooltip">Muestras</div>
									<span class="hidden-txt-tabs">Muestras</span>
								</a>
							</li>
						</ul>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="new-div add-scroll-y" id="cargandoInfVisitaMed">
							<div id="tabVisita" class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="bg-cyan nombre-cuenta font-16">
												<i class="fas fa-user-md font-16"></i>
												<span id="lblPersonaVisita" class="p-l-5"></span>
											</div>
											<div class="card margin-0 card-plan-visita">
												<div class="body" id="datosVisitas">
													<p><span id="lblEspecialidadVisita"
															class="label bg-red label-esp"></span></p>
													<b>Dirección: </b> <span id="lblDireccionVisita"></span><br>
													<b>Brick: </b><span id="lblBrickVisita"></span>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

											<div id="tblVisita">
												<div class="row">
													<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Representante *</label>
															<select id="sltRepreVisita" class="form-control">
															</select>
														</div>
													</div>
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Fecha de la visita *</label>
															<input type="text" size="10" class="form-control"
																id="txtFechaVisita" value="" />
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
														<div class="form-group  margin-0">
															<label class="col-red">Hora de la visita *</label>
															<div class="display-flex">
																<select id="lstHoraVisita" class="form-control">
<?php
																	for($i=0;$i<24;$i++){
																		echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
																	}
?>
																</select>
																<span id="spnPuntosHora">:</span>

																<select id="lstMinutosVisita" class="form-control">
<?php
																	for($i=0;$i<60;$i++){
																		echo '<option value="'.str_pad ($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
																	}
?>
																</select>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Código de la visita *</label>
															<select id="lstCodigoVisita" class="form-control">
																<option value="0">Seleccione</option>
<?php
																$rsCodigo = llenaCombo($conn, 33,69);
																while($codigo = sqlsrv_fetch_array($rsCodigo)){
																	echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
																}
?>
															</select>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label>Visita acompañada</label>
														<form>
															<div class="multiselect">
																<div class="selectBox" onclick="showCheckboxes()">
																	<select class="form-control">
																		<option id="sltMultiSelect">Selecciona</option>
																	</select>
																	<div class="overSelect"></div>
																</div>
																<div id="checkboxesVisitas" style="display:none;">
<?php
																	$rsAcom = llenaCombo($conn, 33, 142);
																	$contadorChecks = 0;
																	$idChecks = '';
																	$descripcionesCheck = '';
																	while($acom = sqlsrv_fetch_array($rsAcom)){
																		$contadorChecks++;
																		$idChk = "acompa".$contadorChecks;
																		echo '<input type="checkbox" id="'.$idChk.'" class="filled-in chk-col-red"  onclick="agregaDesVisAcompa(\''.$acom['nombre'].'\',\''.$idChk.'\');" value="'.$acom['id'].'"/>';
																		echo '<label for="'.$idChk.'">'.$acom['nombre'].'</label>';
																	}
?>
																</div>
															</div>
															<input type="hidden" id="hdnTotalChecksVisitas" value="<?= $contadorChecks ?>">
															<input type="hidden" id="hdnDescripcionChkVisitas" value="<?= ($descripcionesCheck == '') ? "Seleccione" : $descripcionesCheck ?>" />
														</form>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Resultado de la visita *</label>
															<textarea rows="5" id="txtComentariosVisita"
																class="text-notas2"></textarea>
														</div>
													</div>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0">
														<div class="form-group margin-0">
															<label class="col-red">Objetivo de la siguiente visita *</label>
															<textarea rows="5" id="txtInfoSiguienteVisita"
																class="text-notas2"></textarea>
														</div>
													</div>

													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"
														style="display:none;">
														<div class="form-group margin-0">
															<label>Enfermedades que atiende</label>
															<textarea rows="4" id="txtComentariosMedico"
																class="text-notas2"></textarea>
														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>

							<div id="tabProductos" class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table border="0" id="tblProductosVisitas" class="table table-striped">
										<thead class="bg-cyan align-center">
											<tr>
												<td>Posición</td>
												<td>Producto</td>
												<td>% tiempo</td>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>

							<div id="tabMuestras">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
										style="max-height:190px; overflow:auto;">
										<table id="tblMuestras" class="table table-striped">
											<thead class="bg-cyan">
												<tr>
													<td class="negrita">Producto</td>
													<td class="negrita">Presentación</td>
													<td class="negrita">Lote</td>
													<td class="negrita">Cantidad <small>(Cantidades enteras)</small></td>
												</tr>
											</thead>
											<tbody>
											</tbody>
											<tfoot>
												<tr>
													<td>
														<input type="hidden" id="hdnTotalPromociones" value="" />
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="pull-center">
									<div class="sigPad" id="tblFirma" style="width:422px;">
										<ul class="sigNav pull-right">
											<li class="clearButton">
												<a href="#clear">
													<button id="btnLimpiarFirma" class="btn btn-default waves-effect btn-indigo2">
														Limpiar
													</button>
												</a>
											</li>
											<li>
												<button id="btnGuardarFirma" onClick="guardarFirma2();" class="btn bg-light-blue waves-effect btn-light-blue">
													Guardar
												</button>
											</li>
										</ul>
										<div class="sig sigWrapper canvaPer">
											<div class="typed"></div>
											<canvas id="canvasFirmaVisitas2" class="pad" width="400" height="250"></canvas>
											<input type="hidden" id="hdnFirma" name="output-2" class="output">
										</div>
										<div style="margin:10px;" class="align-center">
											Firmar sin salir del recuadro
										</div>
									</div>

									<img id="imgFirma"/>
									<input class="form-control" type="hidden" id="f64" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="divEncuesta" style="display:none;">
	<table width="100%" bgcolor="#FFFFFF" border="0">
		<tr>
			<td class="titulo" colspan="2">
				Encuesta de visita médica
			</td>
		</tr>
		<tr>
			<td colspan="2" class="negrita">
				1. Dr(a) por favor mencione 3 médicos que para usted, desde el punto de vista científico, sean los más
				respetados
				en el área de Diabetes en su país.
			</td>
		</tr>
		<tr>
			<td width="20%">
				Médico 1:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="20%">
				Médico 2:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="20%">
				Médico 3:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="negrita"><br>
				2. Dr(a) ¿Cuál es su pasatiempo favorito?
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="negrita"><br>
				3. Dr(a) ¿Cuáles son las 3 fechas más importantes que usted celebra?
			</td>
		</tr>
		<tr>
			<td width="10%">
				Fecha 1:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="10%">
				Fecha 2:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="10%">
				Fecha 3:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<form enctype="multipart/form-data" action="subir-archivos.php" method="POST" class="negrita">
					<input type="hidden" name="MAX_FILE_SIZE" value="250000" />
					Subir fotografía:
					<input name="archivo-a-subir" type="file" />
					<!--<button id="btnGuardarEncuesta" type="button">
						<img src="iconos/upload.png" width="15px"> Subir Imagen
					</button>&nbsp;&nbsp;&nbsp;-->
				</form>
			</td>
		</tr>
		<tr>
			<td width="10%" colspan="2"><br>
				<button id="btnGuardarEncuesta" type="button">
					<img src="iconos/ok.png" width="15px"> Guardar Encuesta
				</button>
				<button id="btnCancelarEncuesta" type="button">
					<img src="iconos/tache.png" width="15px"> Cancelar Encuesta
				</button>
			</td>
		</tr>
	</table>
</div>
