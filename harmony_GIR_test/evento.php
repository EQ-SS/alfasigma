<input type="hidden" id="hdnIdEventoEditar" value="" />

<div class="row m-r--15 m-l--15" >
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Evento
					</h2>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-10 col-xs-11 align-center m-t-10 m-b-10 display-inline">
					<button id="btnGuardarEventoEditar" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-1 align-right m-t-10">
					<p id="btnCancelarEventoEditar" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div id="tabsEventoEditar">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--20">
						<ul class="nav nav-tabs m-l--35 m-r--35 tab-col-blue p-l-5" role="tablist"
							style="background-color:#efefef;">
							
							<li role="presentation" class="active">
								<a href="#tabEventoEditar" data-toggle="tab"
									class="show-tooltip p-t-14">
									<i class="fas fa-clipboard top-0 p-b-3"></i>
									<div class="divTooltip">Información</div>
									<span class="hidden-txt-tabs">Información</span>
								</a>
							</li>
							
							<li role="presentation">
								<a href="#tabInvitados" data-toggle="tab" class="show-tooltip p-t-14">
									<i class="fas fa-tasks top-0 p-b-3"></i>
									<div class="divTooltip">Asistentes</div>
									<span class="hidden-txt-tabs">Asistentes</span>
								</a>
							</li>
						</ul>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="new-div add-scroll-y" id="cargandoInfVisitaMed">
							<div id="tabEventoEditar" class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div id="tblVisita">
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Representante *</label>
															<select id="sltRepreEventoEditar" class="form-control">
															</select>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Tipo de evento *</label>
															<select id="lstTipoEventoEditar" class="form-control">
																<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
																	$rsCodigo = llenaCombo($conn, 700, 12);
																	while($codigo = sqlsrv_fetch_array($rsCodigo)){
																		echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
																	}
?>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Lugar</label>
															<input type="text" class="form-control"
																id="txtLugarEventoEditar" value="" />
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Nombre</label>
															<input type="text" class="form-control"
																id="txtNombreEventoEditar" value="" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label>Fecha Inicial</label>
															<input type="text" size="10" class="form-control"
																id="txtFechaInicialEventoEditar" value="" />
														</div>
													</div>
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
														<div class="form-group  margin-0">
															<label>Hora Inicial</label>
															<div class="display-flex">
																<select id="lstHoraInicialEventoEditar" class="form-control">
<?php
																	for($i=0;$i<24;$i++){
																		echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
																	}
?>
																</select>
																<span id="spnPuntosHora">:</span>

																<select id="lstMinutosInicialEventoEditar" class="form-control">
<?php
																	for($i=0;$i<60;$i++){
																		echo '<option value="'.str_pad ($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
																	}
?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label>Fecha Final</label>
															<input type="text" size="10" class="form-control"
																id="txtFechaFinalEventoEditar" value="" />
														</div>
													</div>
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
														<div class="form-group  margin-0">
															<label>Hora Final</label>
															<div class="display-flex">
																<select id="lstHoraFinalEventoEditar" class="form-control">
<?php
																	for($i=0;$i<24;$i++){
																		echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
																	}
?>
																</select>
																<span id="spnPuntosHora">:</span>

																<select id="lstMinutosFinalEventoEditar" class="form-control">
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
															<label class="col-red">Tipo de Participación *</label>
															<select id="lstTipoParticipacionEventoEditar" class="form-control">
																<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
																	$rsCodigo = llenaCombo($conn, 700, 13);
																	while($codigo = sqlsrv_fetch_array($rsCodigo)){
																		echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
																	}
?>
															</select>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
														<div class="form-group margin-0">
															<label class="col-red">Número de Participantes *</label>
															<input type="text" class="form-control"
																id="txtNumeroParticipantesEventoEditar" value="" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label>Especialidad</label>
															<select id="lstEspecialidadEventoEditar" class="form-control">
																<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
																$rsCodigo = llenaCombo($conn, 19, 1);
																while($codigo = sqlsrv_fetch_array($rsCodigo)){
																	echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
																}
?>
															</select>
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label>Grupo Terapeútico</label>
															<select id="lstGrupoTerapeuticoEventoEditar" class="form-control">
																<option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
																/*$rsCodigo = llenaCombo($conn, 33,69);
																while($codigo = sqlsrv_fetch_array($rsCodigo)){
																	echo '<option value="'.$codigo['id'].'" >'.utf8_encode($codigo['nombre']).'</option>';
																}*/
?>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="form-group margin-0">
															<label>Comentarios</label>
															<textarea rows="5" id="txtComentariosEventoEditar"
																class="text-notas2"></textarea>
														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>

							<div id="tabInvitados" class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-center m-t-10 m-b-10 display-inline">
									<button id="btnBuscarEventoEditar" type="button" class="btn bg-indigo waves-effect btn-indigo m-l-10">
										Buscar
									</button>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="div-tbl-aprob-pers">
										<table border="0" id="tblInvitadosEditar" class="table table-striped">
											<thead class="bg-cyan align-center">
												<tr>
													<td style="width:5%;">Eliminar</td>
													<td style="width:25%;">Médico</td>
													<td style="width:15%;">Especialidad</td>
													<td style="width:45%;">Dirección</td>
													<td style="width:10%;">Categoría</td>
												</tr>
											</thead>
											<tbody  style="height:400px;">
											</tbody>
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